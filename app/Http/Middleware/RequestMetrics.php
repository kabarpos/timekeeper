<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestMetrics
{
    /**
     * Handle an incoming request and track metrics
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start_time = microtime(true);
        $start_memory = memory_get_usage(true);

        // Increment total requests counter
        $this->incrementCounter('total_requests');

        // Track unique visitors (simplified)
        $this->trackUniqueVisitor($request);

        // Process the request
        $response = $next($request);

        // Calculate metrics
        $end_time = microtime(true);
        $end_memory = memory_get_usage(true);
        
        $response_time = round(($end_time - $start_time) * 1000, 2); // in milliseconds
        $memory_usage = $end_memory - $start_memory;

        // Track response metrics
        $this->trackResponseMetrics($request, $response, $response_time, $memory_usage);

        // Add performance headers
        $response->headers->set('X-Response-Time', $response_time . 'ms');
        $response->headers->set('X-Memory-Usage', $this->formatBytes($memory_usage));

        return $response;
    }

    /**
     * Track response metrics
     */
    private function trackResponseMetrics(Request $request, Response $response, float $response_time, int $memory_usage): void
    {
        $status_code = $response->getStatusCode();
        $method = $request->method();
        $route = $request->route()?->getName() ?? 'unknown';

        // Track by status code
        $this->incrementCounter("responses.{$status_code}");
        
        // Track by method
        $this->incrementCounter("requests.method.{$method}");

        // Track errors
        if ($status_code >= 400) {
            $this->incrementCounter('error_requests');
            
            if ($status_code >= 500) {
                $this->incrementCounter('server_errors');
                
                // Log server errors with context
                Log::error('Server error tracked', [
                    'status_code' => $status_code,
                    'method' => $method,
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'response_time' => $response_time,
                    'memory_usage' => $memory_usage,
                ]);
            } else {
                $this->incrementCounter('client_errors');
            }
        }

        // Track slow requests (> 1 second)
        if ($response_time > 1000) {
            $this->incrementCounter('slow_requests');
            
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $method,
                'response_time' => $response_time,
                'memory_usage' => $memory_usage,
                'route' => $route,
            ]);
        }

        // Track high memory usage (> 10MB)
        if ($memory_usage > 10 * 1024 * 1024) {
            $this->incrementCounter('high_memory_requests');
            
            Log::warning('High memory usage detected', [
                'url' => $request->fullUrl(),
                'method' => $method,
                'memory_usage' => $this->formatBytes($memory_usage),
                'response_time' => $response_time,
                'route' => $route,
            ]);
        }

        // Store detailed metrics for analysis
        $this->storeDetailedMetrics($request, $response, $response_time, $memory_usage);
    }

    /**
     * Track unique visitors
     */
    private function trackUniqueVisitor(Request $request): void
    {
        $visitor_key = 'visitor_' . hash('sha256', $request->ip() . $request->userAgent());
        $today = now()->format('Y-m-d');
        
        if (!Cache::has($visitor_key . '_' . $today)) {
            Cache::put($visitor_key . '_' . $today, true, now()->endOfDay());
            $this->incrementCounter('unique_visitors_' . $today);
        }
    }

    /**
     * Store detailed metrics for analysis
     */
    private function storeDetailedMetrics(Request $request, Response $response, float $response_time, int $memory_usage): void
    {
        $metrics = [
            'timestamp' => now()->toISOString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route()?->getName(),
            'status_code' => $response->getStatusCode(),
            'response_time' => $response_time,
            'memory_usage' => $memory_usage,
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'referer' => $request->header('referer'),
        ];

        // Store in cache with TTL (keep for 24 hours)
        $key = 'request_metrics_' . now()->format('Y-m-d-H');
        $existing = Cache::get($key, []);
        $existing[] = $metrics;
        
        // Keep only last 1000 requests per hour to prevent memory issues
        if (count($existing) > 1000) {
            $existing = array_slice($existing, -1000);
        }
        
        Cache::put($key, $existing, now()->addDay());
    }

    /**
     * Increment counter in cache
     */
    private function incrementCounter(string $key): void
    {
        $current = Cache::get($key, 0);
        Cache::put($key, $current + 1, now()->addWeek());
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get metrics summary
     */
    public static function getMetricsSummary(): array
    {
        $keys = [
            'total_requests',
            'error_requests',
            'server_errors',
            'client_errors',
            'slow_requests',
            'high_memory_requests',
        ];

        $metrics = [];
        foreach ($keys as $key) {
            $metrics[$key] = Cache::get($key, 0);
        }

        // Add unique visitors for today
        $today = now()->format('Y-m-d');
        $metrics['unique_visitors_today'] = Cache::get('unique_visitors_' . $today, 0);

        // Calculate error rate
        $total = $metrics['total_requests'];
        $metrics['error_rate'] = $total > 0 ? round(($metrics['error_requests'] / $total) * 100, 2) : 0;

        return $metrics;
    }

    /**
     * Get detailed metrics for a specific hour
     */
    public static function getDetailedMetrics(string $hour = null): array
    {
        $hour = $hour ?? now()->format('Y-m-d-H');
        $key = 'request_metrics_' . $hour;
        
        return Cache::get($key, []);
    }

    /**
     * Clear all metrics
     */
    public static function clearMetrics(): void
    {
        $keys = [
            'total_requests',
            'error_requests',
            'server_errors',
            'client_errors',
            'slow_requests',
            'high_memory_requests',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear unique visitors for current month
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        
        while ($start->lte($end)) {
            Cache::forget('unique_visitors_' . $start->format('Y-m-d'));
            $start->addDay();
        }
    }
}