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

        // 1. Define the components
        $default = "default-src 'self';";
        $scripts = "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://static.cloudflareinsights.com;";
        $styles = "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.googleapis.com;";
        $fonts = "font-src 'self' data: https://cdn.jsdelivr.net https://fonts.cdnfonts.com https://fonts.gstatic.com;";
        $connect = "connect-src 'self' https://cdn.jsdelivr.net;";

        // QR codes often need 'data:' for images and 'blob:' if generated via JS
        $imgs = "img-src 'self' data: https: blob:;";

        // If the QR code is an Inline SVG, it sometimes needs this:
        $extra = "purpose 'none'; base-uri 'self'; form-action 'self';";

        // 2. Build the string
        $policy = "{$default} {$scripts} {$styles} {$imgs} {$fonts} {$connect} {$extra}";

        // 3. Set the header
        $response->headers->set('Content-Security-Policy', $policy);

        return $response;
    }
}
