<?php

namespace App\Http\Middleware;

use App\Application\Election\Monitoring\ConstitutionalDriftMonitor;
use Closure;
use Illuminate\Http\Request;

final class ElectionSSOTInterceptor
{
    public function __construct(
        private readonly ConstitutionalDriftMonitor $monitor
    ) {}

    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}
