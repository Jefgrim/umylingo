<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
{
    /** @var Response $response */
    $response = $next($request);

    // Common sources for both environments
    $scripts = "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://static.cloudflareinsights.com";
    $styles = "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.googleapis.com";
    $fonts = "font-src 'self' data: https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.gstatic.com";
    $connect = "connect-src 'self' https://cdn.jsdelivr.net";
    $imgs = "img-src 'self' data: https:;"; // Added 'data:' and 'https:' for QR codes/external images

    if (config('app.debug', false)) {
        // Debug Policy (Very loose)
        $policy = "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: https:; $scripts; $styles; $imgs; $fonts; $connect;";
    } else {
        // Production Policy (Strict but corrected)
        $policy = "default-src 'self'; $scripts; $styles; $imgs; $fonts; $connect; frame-ancestors 'none'; base-uri 'self'; form-action 'self';";
    }

    $response->headers->set('Content-Security-Policy', $policy);

    return $response;
}
}
