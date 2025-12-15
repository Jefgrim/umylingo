<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // This is the "Working" policy that includes everything you need
        $policy = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://static.cloudflareinsights.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.googleapis.com; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' data: https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.gstatic.com; " .
            "connect-src 'self' https://cdn.jsdelivr.net; " .
            "frame-ancestors 'none'; base-uri 'self'; form-action 'self';";

        // We use set() to overwrite any existing CSP header set by packages
        $response->headers->set('Content-Security-Policy', $policy, true);

        return $response;
    }
}
