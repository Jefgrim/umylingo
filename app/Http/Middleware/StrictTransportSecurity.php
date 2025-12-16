<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StrictTransportSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Add HSTS header for HTTPS requests
        // Works with: direct HTTPS, AWS load balancer, Cloudflare Tunnel, etc.
        $isSecure = $request->isSecure() || 
                   strtolower($request->header('X-Forwarded-Proto', '')) === 'https' ||
                   strtolower($request->header('CF-Visitor', '')) === '{"scheme":"https"}' ||
                   config('session.secure', false) ||
                   app()->environment('production');
        
        if ($isSecure) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=63072000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
