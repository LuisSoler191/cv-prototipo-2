<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isDev = config('app.env') !== 'production';

        $cspScript = $isDev
            ? "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:*;"
            : "script-src 'self' 'unsafe-inline' 'unsafe-eval';";

        $cspStyle = $isDev
            ? "style-src 'self' 'unsafe-inline' http://localhost:*;"
            : "style-src 'self' 'unsafe-inline';";

        $cspConnect = $isDev
            ? "connect-src 'self' http://localhost:* ws://localhost:*;"
            : "connect-src 'self';";

        $cspImg = $isDev
            ? "img-src 'self' data: blob: http://localhost:* https://cdn.simpleicons.org;"
            : "img-src 'self' data: blob: https://cdn.simpleicons.org;";

        $headers = [
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy' =>
                "default-src 'self'; ".
                $cspScript . " ".
                $cspStyle . " ".
                $cspImg . " ".
                "font-src 'self' data:; ".
                $cspConnect . " ".
                "frame-ancestors 'none'; ",
        ];

        if ($request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
