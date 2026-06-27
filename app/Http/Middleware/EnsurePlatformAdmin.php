<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePlatformAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isPlatformAdmin()) {
            abort(403, 'Access restricted to platform administrators.');
        }

        return $next($request);
    }
}
