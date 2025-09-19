<?php

namespace App\Exceptions;

class CacheException extends TimeKeeperException
{
    public static function operationFailed(string $operation, string $key, ?\Exception $previous = null): self
    {
        return new self(
            message: "Cache operation '{$operation}' failed for key '{$key}'",
            code: 500,
            previous: $previous,
            context: [
                'operation' => $operation,
                'cache_key' => $key,
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'warning'
        );
    }
    
    public static function keyNotFound(string $key): self
    {
        return new self(
            message: "Cache key '{$key}' not found",
            code: 404,
            context: [
                'cache_key' => $key,
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'info'
        );
    }
    
    public static function invalidKey(string $key): self
    {
        return new self(
            message: "Invalid cache key format: '{$key}'",
            code: 400,
            context: [
                'cache_key' => $key,
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'warning'
        );
    }
}