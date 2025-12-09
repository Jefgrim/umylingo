<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        // Skip logging for health checks and static assets
        if ($this->shouldSkip($request)) {
            return $response;
        }

        $logData = [
            'method' => $request->getMethod(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username ?? 'guest',
        ];

        // Log failed requests (4xx, 5xx)
        if ($response->getStatusCode() >= 400) {
            Log::warning('HTTP Request Failed', $logData);
        } else {
            Log::info('HTTP Request', $logData);
        }

        return $response;
    }

    private function shouldSkip(Request $request): bool
    {
        $skipPaths = [
            'health',
            'livewire',
            '/css/',
            '/js/',
            '/assets/',
            'favicon.ico',
        ];

        foreach ($skipPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }
}
