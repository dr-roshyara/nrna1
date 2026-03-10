<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreloadAssets
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only add Link headers on HTML responses (not API/JSON)
        if (!$response->headers->has('Content-Type') ||
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Link', implode(', ', [
                '<https://fonts.googleapis.com>; rel=preconnect',
                '<https://fonts.gstatic.com>; rel=preconnect; crossorigin',
            ]));
        }

        return $response;
    }
}
