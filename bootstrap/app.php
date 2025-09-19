<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->append([
            \App\Http\Middleware\ErrorHandling::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\RequestMetrics::class,
        ]);

        // Web middleware group - More generous limits for better UX
        $middleware->web(append: [
            \App\Http\Middleware\RateLimiting::class . ':300,1', // 300 requests per minute (5 per second)
        ]);

        // API middleware group - Reasonable limits for API usage
        $middleware->api(append: [
            \App\Http\Middleware\RateLimiting::class . ':100,1', // 100 requests per minute
        ]);

        // Middleware aliases
        $middleware->alias([
            'error.handling' => \App\Http\Middleware\ErrorHandling::class,
            'rate.limiting' => \App\Http\Middleware\RateLimiting::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'request.metrics' => \App\Http\Middleware\RequestMetrics::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom exception handling untuk TimeKeeper
        $exceptions->render(function (\App\Exceptions\TimeKeeperException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'context' => $e->getContext()
                ], $e->getCode() ?: 500);
            }
            
            // Untuk web request, redirect dengan error message
            return redirect()->back()->with('error', $e->getMessage());
        });
        
        // Handle database connection errors
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            \Illuminate\Support\Facades\Log::error('Database Query Error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Database error occurred',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        });
        
        // Handle general exceptions
        $exceptions->render(function (\Exception $e, $request) {
            \Illuminate\Support\Facades\Log::error('Unhandled Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'An unexpected error occurred',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        });
    })->create();
