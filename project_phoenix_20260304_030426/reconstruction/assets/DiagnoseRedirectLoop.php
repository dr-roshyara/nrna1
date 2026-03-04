<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Diagnostic Middleware - Detect Redirect Loops
 *
 * Tracks redirect chain and identifies circular redirects
 * Remove after debugging
 */
class DiagnoseRedirectLoop
{
    private const MAX_REDIRECTS = 10;

    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $referer = $request->headers->get('referer');
        $sessionKey = "request_chain_{$request->getClientIp()}";

        // Get current redirect chain from session
        $chain = session($sessionKey, []);

        // Add current request
        $chain[] = [
            'path' => $path,
            'time' => now()->format('H:i:s'),
            'referer' => $referer,
        ];

        Log::warning('🔄 Redirect Chain Detected', [
            'path' => $path,
            'referer' => $referer,
            'chain_length' => count($chain),
            'chain' => array_map(fn($item) => $item['path'], $chain),
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
        ]);

        // Check for circular redirect
        if (count($chain) > self::MAX_REDIRECTS) {
            Log::error('🚨 CIRCULAR REDIRECT DETECTED!', [
                'client_ip' => $request->getClientIp(),
                'path_chain' => array_map(fn($item) => $item['path'], $chain),
                'user_id' => auth()->id(),
            ]);

            // Clear the chain
            session()->forget($sessionKey);

            // Redirect to safe location
            return redirect()->route('dashboard')
                ->with('error', 'Redirect loop detected. Please clear your browser cache and try again.');
        }

        // Store updated chain
        session([$sessionKey => $chain]);

        return $next($request);
    }
}
