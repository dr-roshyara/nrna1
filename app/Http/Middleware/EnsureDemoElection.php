<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * EnsureDemoElection Middleware
 *
 * Ensures that the current election is a demo election.
 * Blocks access to demo voting routes if the election is not a demo election.
 *
 * Usage in routes:
 * Route::middleware(['election.demo'])->group(function () { ... });
 */
class EnsureDemoElection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $election = $request->attributes->get('election');

        // Election should be set by ElectionMiddleware
        if (!$election) {
            abort(404, 'No election context found.');
        }

        // Only allow demo elections
        if ($election->type !== 'demo') {
            abort(403, 'Demo voting is only available for demo elections.');
        }

        \Log::info('✅ [EnsureDemoElection] Demo election confirmed', [
            'election_id' => $election->id,
            'election_type' => $election->type,
            'election_name' => $election->name,
        ]);

        return $next($request);
    }
}
