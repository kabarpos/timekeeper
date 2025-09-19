<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Exceptions\TimeKeeperException;

trait HandlesErrors
{
    /**
     * Handle dan log exception dengan context
     */
    protected function handleException(\Exception $e, string $operation, array $context = []): void
    {
        $logContext = array_merge([
            'operation' => $operation,
            'class' => static::class,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], $context);
        
        // Tentukan log level berdasarkan jenis exception
        $logLevel = $this->getLogLevel($e);
        
        Log::log($logLevel, "Error in {$operation}", $logContext);
    }
    
    /**
     * Tentukan log level berdasarkan jenis exception
     */
    protected function getLogLevel(\Exception $e): string
    {
        if ($e instanceof \Illuminate\Database\QueryException) {
            return 'error';
        }
        
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 'warning';
        }
        
        if ($e instanceof TimeKeeperException) {
            return 'warning';
        }
        
        return 'error';
    }
    
    /**
     * Create user-friendly error message
     */
    protected function getUserFriendlyMessage(\Exception $e): string
    {
        if ($e instanceof \Illuminate\Database\QueryException) {
            return 'Terjadi kesalahan database. Silakan coba lagi.';
        }
        
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 'Data yang dicari tidak ditemukan.';
        }
        
        if ($e instanceof TimeKeeperException) {
            return $e->getMessage();
        }
        
        return 'Terjadi kesalahan sistem. Silakan coba lagi.';
    }
    
    /**
     * Execute operation dengan error handling
     */
    protected function executeWithErrorHandling(callable $operation, string $operationName, array $context = [])
    {
        try {
            return $operation();
        } catch (\Exception $e) {
            $this->handleException($e, $operationName, $context);
            
            // Re-throw jika dalam mode debug atau untuk critical errors
            if (config('app.debug') || $this->isCriticalError($e)) {
                throw $e;
            }
            
            return null;
        }
    }
    
    /**
     * Tentukan apakah error adalah critical
     */
    protected function isCriticalError(\Exception $e): bool
    {
        return $e instanceof \Error || 
               $e instanceof \ParseError ||
               $e instanceof \TypeError;
    }
}