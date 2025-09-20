<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check for sensitive data in URL parameters FIRST
        $sensitiveParams = ['password', 'password_confirmation'];
        $hasSensitiveData = false;
        
        foreach ($sensitiveParams as $param) {
            if ($request->has($param)) {
                $hasSensitiveData = true;
                
                // Log security incident
                \Log::warning('Security Alert: Sensitive parameter in URL', [
                    'parameter' => $param,
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                    'timestamp' => now()
                ]);
            }
        }
        
        // If sensitive data found in URL, redirect to clean URL
        if ($hasSensitiveData) {
            $cleanUrl = $request->url();
            
            return redirect($cleanUrl)->with([
                'error' => 'Invalid request detected. Please try again.',
                'security_alert' => true
            ]);
        }
        
        $response = $next($request);
        
        // Skip CSP for development debugging
        if (config('app.debug')) {
            // Only add basic security headers for development
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // Changed from DENY to SAMEORIGIN for dev
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            
            // Remove server information
            $response->headers->remove('Server');
            $response->headers->remove('X-Powered-By');
            
            return $response;
        }
        
        // Full security headers for production
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy
        $csp = $this->buildContentSecurityPolicy($request);
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (HTTP Strict Transport Security) - hanya untuk HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
    
    /**
     * Build Content Security Policy header
     */
    protected function buildContentSecurityPolicy(Request $request): string
    {
        // Kebijakan dasar untuk production
        $policies = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
            "font-src 'self' https://fonts.bunny.net data:",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' ws: wss: https://fonts.bunny.net",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "object-src 'none'"
        ];

        // Kebijakan khusus untuk development
        if (config('app.debug')) {
            $policies = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* http://[::1]:* https://cdn.tailwindcss.com https://unpkg.com",
                "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* http://[::1]:* https://cdn.tailwindcss.com https://fonts.googleapis.com https://fonts.bunny.net",
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:",
                "img-src 'self' data: https: blob:",
                "connect-src 'self' ws: wss: http://localhost:* http://127.0.0.1:* http://[::1]:* https://localhost:* https://127.0.0.1:* https://[::1]:* https://fonts.bunny.net",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "object-src 'none'"
            ];
        }

        return implode('; ', $policies);
    }
}