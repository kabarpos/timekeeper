<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CacheException;
use App\Traits\HandlesErrors;

class CacheService
{
    use HandlesErrors;
    // Cache keys constants untuk konsistensi
    public const ACTIVE_MESSAGE_KEY = 'active_message';
    public const RECENT_MESSAGES_KEY = 'recent_messages_5';
    public const CURRENT_SETTING_KEY = 'current_setting';
    public const MESSAGE_LIST_KEY = 'message_list';
    public const SETTINGS_LIST_KEY = 'settings_list';
    
    // Default cache duration (dalam detik)
    public const DEFAULT_TTL = 3600; // 1 jam
    public const SHORT_TTL = 300;    // 5 menit
    public const LONG_TTL = 86400;   // 24 jam
    
    /**
     * Validate cache key format
     */
    private static function validateKey(string $key): void
    {
        if (empty($key) || strlen($key) > 250) {
            throw CacheException::invalidKey($key);
        }
    }
    
    /**
     * Cache data dengan fallback jika gagal
     */
    public static function remember(string $key, callable $callback, int $ttl = self::DEFAULT_TTL)
    {
        self::validateKey($key);
        
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            // Log error tapi jangan throw exception untuk menjaga aplikasi tetap berjalan
            Log::error("Cache remember operation failed", [
                'key' => $key,
                'ttl' => $ttl,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: jalankan callback langsung tanpa cache
            try {
                return $callback();
            } catch (\Exception $callbackException) {
                Log::critical("Cache fallback callback also failed", [
                    'key' => $key,
                    'callback_error' => $callbackException->getMessage(),
                    'original_error' => $e->getMessage()
                ]);
                throw $callbackException;
            }
        }
    }
    
    /**
     * Hapus cache dengan error handling
     */
    public static function forget(string $key): bool
    {
        self::validateKey($key);
        
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::warning("Cache forget operation failed", [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Forget multiple cache keys
     */
    public static function forgetMany(array $keys): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = self::forget($key);
        }
        return $results;
    }
    
    /**
     * Clear message-related caches
     */
    public static function clearMessageCaches(): array
    {
        return self::forgetMany([
            self::ACTIVE_MESSAGE_KEY,
            self::RECENT_MESSAGES_KEY,
            self::MESSAGE_LIST_KEY
        ]);
    }
    
    /**
     * Clear setting-related caches
     */
    public static function clearSettingCaches(): array
    {
        return self::forgetMany([
            self::CURRENT_SETTING_KEY,
            self::SETTINGS_LIST_KEY
        ]);
    }
    
    /**
     * Clear all application caches
     */
    public static function clearAllCaches(): array
    {
        return self::forgetMany([
            self::ACTIVE_MESSAGE_KEY,
            self::RECENT_MESSAGES_KEY,
            self::CURRENT_SETTING_KEY,
            self::MESSAGE_LIST_KEY,
            self::SETTINGS_LIST_KEY
        ]);
    }
    
    /**
     * Get cache statistics (for monitoring)
     */
    public static function getStats(): array
    {
        try {
            $keys = [
                self::ACTIVE_MESSAGE_KEY,
                self::RECENT_MESSAGES_KEY,
                self::CURRENT_SETTING_KEY,
                self::MESSAGE_LIST_KEY,
                self::SETTINGS_LIST_KEY
            ];
            
            $stats = [
                'cache_driver' => config('cache.default'),
                'cache_prefix' => config('cache.prefix'),
                'keys_status' => [],
                'timestamp' => now()->toISOString()
            ];
            
            foreach ($keys as $key) {
                $stats['keys_status'][$key] = Cache::has($key);
            }
            
            return $stats;
        } catch (\Exception $e) {
            Log::warning("Cache stats retrieval failed", [
                'error' => $e->getMessage()
            ]);
            
            return [
                'error' => 'Failed to retrieve cache stats',
                'timestamp' => now()->toISOString()
            ];
        }
    }
    
    /**
     * Put data into cache with error handling
     */
    public static function put(string $key, $value, int $ttl = self::DEFAULT_TTL): bool
    {
        self::validateKey($key);
        
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::error("Cache put operation failed", [
                'key' => $key,
                'ttl' => $ttl,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get data from cache with default value
     */
    public static function get(string $key, $default = null)
    {
        self::validateKey($key);
        
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::warning("Cache get operation failed", [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $default;
        }
    }
    
    /**
     * Check if cache key exists
     */
    public static function has(string $key): bool
    {
        self::validateKey($key);
        
        try {
            return Cache::has($key);
        } catch (\Exception $e) {
            Log::warning("Cache has operation failed", [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}