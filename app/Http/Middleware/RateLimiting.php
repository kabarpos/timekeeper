<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimiting
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);
        
        if ($this->tooManyAttempts($key, $maxAttempts)) {
            $this->logRateLimitExceeded($request, $key);
            return $this->buildResponse($key, $maxAttempts, $decayMinutes);
        }
        
        $this->hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
    
    /**
     * Resolve request signature untuk rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $userId = $request->user()?->id ?? 'guest';
        $ip = $request->ip();
        $route = $request->route()?->getName() ?? $request->path();
        
        return "rate_limit:{$userId}:{$ip}:{$route}";
    }
    
    /**
     * Tentukan max attempts berdasarkan user atau route
     */
    protected function resolveMaxAttempts(Request $request, int $default): int
    {
        // Admin users mendapat limit lebih tinggi
        if ($request->user()?->is_admin) {
            return $default * 2;
        }
        
        // API routes mendapat limit lebih ketat
        if ($request->is('api/*')) {
            return max(10, $default / 2);
        }
        
        return $default;
    }
    
    /**
     * Determine if the given key has been "accessed" too many times
     */
    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return Cache::get($key, 0) >= $maxAttempts;
    }
    
    /**
     * Increment the counter for a given key for a given decay time
     */
    protected function hit(string $key, int $decaySeconds): int
    {
        $current = Cache::get($key, 0);
        $new = $current + 1;
        
        Cache::put($key, $new, $decaySeconds);
        
        return $new;
    }
    
    /**
     * Calculate the number of remaining attempts
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return max(0, $maxAttempts - Cache::get($key, 0));
    }
    
    /**
     * Create a 'too many attempts' response
     */
    protected function buildResponse(string $key, int $maxAttempts, int $decayMinutes): Response
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);
        
        if (request()->expectsJson()) {
            return response()->json([
                'error' => 'Rate Limit Exceeded',
                'message' => 'Sistem sedang sibuk. Mohon tunggu sebentar dan coba lagi.',
                'retry_after' => $retryAfter,
                'status' => 'busy'
            ], 429)->withHeaders([
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ]);
        }
        
        return response()->view('errors.429', [
            'retry_after' => $retryAfter
        ], 429)->withHeaders([
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
        ]);
    }
    
    /**
     * Add the limit header information to the given response
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts): Response
    {
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);
    }
    
    /**
     * Get the number of seconds until the next retry
     */
    protected function getTimeUntilNextRetry(string $key): int
    {
        try {
            $store = Cache::getStore();
            
            // Check if using Redis driver
            if (method_exists($store, 'getRedis')) {
                return $store->getRedis()->ttl($key) ?: 60;
            }
            
            // For file/database cache drivers, calculate TTL differently
            if (Cache::has($key)) {
                // Since file cache doesn't provide TTL info, return default retry time
                return 60; // Default 60 seconds
            }
            
            return 0; // Key doesn't exist, no retry needed
        } catch (\Exception $e) {
            // Fallback to default retry time if any error occurs
            Log::warning('Failed to get cache TTL', [
                'key' => $key,
                'error' => $e->getMessage(),
                'cache_driver' => config('cache.default')
            ]);
            return 60;
        }
    }
    
    /**
     * Log rate limit exceeded event
     */
    protected function logRateLimitExceeded(Request $request, string $key): void
    {
        Log::warning('Rate limit exceeded', [
            'key' => $key,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'user_id' => $request->user()?->id
        ]);
    }
}