<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;

class DatabaseException extends TimeKeeperException
{
    public static function queryFailed(string $operation, QueryException $previous): self
    {
        return new self(
            message: "Database operation '{$operation}' failed: " . $previous->getMessage(),
            code: 500,
            previous: $previous,
            context: [
                'operation' => $operation,
                'sql' => $previous->getSql() ?? 'N/A',
                'bindings' => $previous->getBindings() ?? [],
                'error_code' => $previous->getCode(),
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'error'
        );
    }
    
    public static function recordNotFound(string $model, $id): self
    {
        return new self(
            message: "Record not found in {$model} with ID: {$id}",
            code: 404,
            context: [
                'model' => $model,
                'id' => $id,
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'warning'
        );
    }
    
    public static function validationFailed(string $model, array $errors): self
    {
        return new self(
            message: "Validation failed for {$model}",
            code: 422,
            context: [
                'model' => $model,
                'validation_errors' => $errors,
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'warning'
        );
    }
    
    public static function connectionFailed(\Exception $previous): self
    {
        return new self(
            message: "Database connection failed: " . $previous->getMessage(),
            code: 503,
            previous: $previous,
            context: [
                'timestamp' => now()->toISOString()
            ],
            logLevel: 'critical'
        );
    }
}