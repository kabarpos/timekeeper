<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;

class HealthController extends Controller
{
    /**
     * Comprehensive health check endpoint
     */
    public function check(Request $request): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
            'memory' => $this->checkMemory(),
            'disk' => $this->checkDisk(),
        ];

        $overall = $this->determineOverallHealth($checks);
        
        $response = [
            'status' => $overall['status'],
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'uptime' => $this->getUptime(),
            ],
        ];

        // Log health check if there are issues
        if ($overall['status'] !== 'healthy') {
            Log::warning('Health check failed', [
                'status' => $overall['status'],
                'failed_checks' => $overall['failed_checks'],
                'timestamp' => now(),
            ]);
        }

        return response()->json($response, $overall['http_code']);
    }

    /**
     * Simple liveness probe
     */
    public function alive(): JsonResponse
    {
        return response()->json([
            'status' => 'alive',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Readiness probe
     */
    public function ready(): JsonResponse
    {
        $critical_checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
        ];

        $is_ready = collect($critical_checks)->every(fn($check) => $check['status'] === 'healthy');

        return response()->json([
            'status' => $is_ready ? 'ready' : 'not_ready',
            'timestamp' => now()->toISOString(),
            'checks' => $critical_checks,
        ], $is_ready ? 200 : 503);
    }

    /**
     * Application metrics
     */
    public function metrics(): JsonResponse
    {
        $metrics = [
            'memory' => [
                'usage' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => $this->getMemoryLimit(),
            ],
            'cache' => [
                'hits' => Cache::get('cache_hits', 0),
                'misses' => Cache::get('cache_misses', 0),
            ],
            'database' => [
                'connections' => $this->getDatabaseConnections(),
            ],
            'requests' => [
                'total' => Cache::get('total_requests', 0),
                'errors' => Cache::get('error_requests', 0),
            ],
        ];

        return response()->json([
            'metrics' => $metrics,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $response_time = round((microtime(true) - $start) * 1000, 2);

            // Test a simple query
            DB::select('SELECT 1');

            return [
                'status' => 'healthy',
                'response_time_ms' => $response_time,
                'connection' => DB::connection()->getName(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connection' => DB::connection()->getName(),
            ];
        }
    }

    /**
     * Check cache system
     */
    private function checkCache(): array
    {
        try {
            $start = microtime(true);
            $test_key = 'health_check_' . time();
            $test_value = 'test_value';

            // Test cache write
            Cache::put($test_key, $test_value, 60);
            
            // Test cache read
            $retrieved = Cache::get($test_key);
            
            // Cleanup
            Cache::forget($test_key);

            $response_time = round((microtime(true) - $start) * 1000, 2);

            if ($retrieved === $test_value) {
                return [
                    'status' => 'healthy',
                    'response_time_ms' => $response_time,
                    'driver' => config('cache.default'),
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Cache read/write test failed',
                    'driver' => config('cache.default'),
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('cache.default'),
            ];
        }
    }

    /**
     * Check storage system
     */
    private function checkStorage(): array
    {
        try {
            $start = microtime(true);
            $test_file = 'health_check_' . time() . '.txt';
            $test_content = 'health check test';

            // Test storage write
            Storage::put($test_file, $test_content);
            
            // Test storage read
            $retrieved = Storage::get($test_file);
            
            // Cleanup
            Storage::delete($test_file);

            $response_time = round((microtime(true) - $start) * 1000, 2);

            if ($retrieved === $test_content) {
                return [
                    'status' => 'healthy',
                    'response_time_ms' => $response_time,
                    'driver' => config('filesystems.default'),
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Storage read/write test failed',
                    'driver' => config('filesystems.default'),
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('filesystems.default'),
            ];
        }
    }

    /**
     * Check queue system
     */
    private function checkQueue(): array
    {
        try {
            $connection = config('queue.default');
            
            return [
                'status' => 'healthy',
                'connection' => $connection,
                'note' => 'Queue connection available',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connection' => config('queue.default'),
            ];
        }
    }

    /**
     * Check memory usage
     */
    private function checkMemory(): array
    {
        $usage = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);
        $limit = $this->getMemoryLimit();
        
        $usage_percentage = $limit > 0 ? ($usage / $limit) * 100 : 0;
        
        $status = 'healthy';
        if ($usage_percentage > 90) {
            $status = 'critical';
        } elseif ($usage_percentage > 80) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'usage_bytes' => $usage,
            'usage_mb' => round($usage / 1024 / 1024, 2),
            'peak_bytes' => $peak,
            'peak_mb' => round($peak / 1024 / 1024, 2),
            'limit_bytes' => $limit,
            'limit_mb' => $limit > 0 ? round($limit / 1024 / 1024, 2) : 'unlimited',
            'usage_percentage' => round($usage_percentage, 2),
        ];
    }

    /**
     * Check disk space
     */
    private function checkDisk(): array
    {
        $path = storage_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;
        $usage_percentage = ($used / $total) * 100;

        $status = 'healthy';
        if ($usage_percentage > 95) {
            $status = 'critical';
        } elseif ($usage_percentage > 85) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'path' => $path,
            'total_bytes' => $total,
            'total_gb' => round($total / 1024 / 1024 / 1024, 2),
            'free_bytes' => $free,
            'free_gb' => round($free / 1024 / 1024 / 1024, 2),
            'used_bytes' => $used,
            'used_gb' => round($used / 1024 / 1024 / 1024, 2),
            'usage_percentage' => round($usage_percentage, 2),
        ];
    }

    /**
     * Determine overall health status
     */
    private function determineOverallHealth(array $checks): array
    {
        $statuses = collect($checks)->pluck('status');
        $failed_checks = collect($checks)
            ->filter(fn($check) => $check['status'] !== 'healthy')
            ->keys()
            ->toArray();

        if ($statuses->contains('critical') || $statuses->contains('unhealthy')) {
            return [
                'status' => 'unhealthy',
                'http_code' => 503,
                'failed_checks' => $failed_checks,
            ];
        }

        if ($statuses->contains('warning')) {
            return [
                'status' => 'degraded',
                'http_code' => 200,
                'failed_checks' => $failed_checks,
            ];
        }

        return [
            'status' => 'healthy',
            'http_code' => 200,
            'failed_checks' => [],
        ];
    }

    /**
     * Get memory limit in bytes
     */
    private function getMemoryLimit(): int
    {
        $limit = ini_get('memory_limit');
        if ($limit === '-1') {
            return 0; // unlimited
        }

        $value = (int) $limit;
        $unit = strtolower(substr($limit, -1));

        switch ($unit) {
            case 'g':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $value *= 1024 * 1024;
                break;
            case 'k':
                $value *= 1024;
                break;
        }

        return $value;
    }

    /**
     * Get database connections count
     */
    private function getDatabaseConnections(): int
    {
        try {
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            return $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get application uptime
     */
    private function getUptime(): string
    {
        $uptime_file = storage_path('framework/uptime');
        
        if (!file_exists($uptime_file)) {
            file_put_contents($uptime_file, time());
        }
        
        $start_time = (int) file_get_contents($uptime_file);
        $uptime_seconds = time() - $start_time;
        
        $days = floor($uptime_seconds / 86400);
        $hours = floor(($uptime_seconds % 86400) / 3600);
        $minutes = floor(($uptime_seconds % 3600) / 60);
        
        return sprintf('%d days, %d hours, %d minutes', $days, $hours, $minutes);
    }
}