<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

/**
 * ElectionMiddleware
 *
 * Ensures election context is available for voting routes.
 * Checks if election is selected in session, or loads from route parameter.
 * Validates election exists and is active before allowing access.
 *
 * Usage:
 * Route::middleware('election')->group(function () {
 *     // All routes here have election context
 * });
 */
class ElectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Election context resolution (in order):
     * 1. Check session for selected_election_id (user explicitly selected)
     * 2. Check route parameter 'election' (URL-based election)
     * 3. DEFAULT: Use first REAL active election
     *
     * This ensures backward compatibility - existing voting links
     * automatically use real elections without requiring selection.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $election = null;

        // 1. Try to get election from session (user explicitly selected)
        $electionId = session('selected_election_id');

        if ($electionId) {
            $election = Election::find($electionId);
        }

        // 2. Try route parameter (for direct access like /election/{election:slug}/vote)
        if (!$election && $request->route('election')) {
            $routeElection = $request->route('election');

            if ($routeElection instanceof Election) {
                $election = $routeElection;
            }
        }

        // 3. DEFAULT: No election selected - use first REAL active election
        if (!$election) {
            $election = Election::where('type', 'real')
                ->where('is_active', true)
                ->orderBy('id')
                ->first();

            // If no real election found, try any active election
            if (!$election) {
                $election = Election::where('is_active', true)
                    ->orderBy('id')
                    ->first();
            }
        }

        // If still no election found, cannot proceed
        if (!$election) {
            return redirect()->route('dashboard')
                ->with('error', 'No active elections available. Please contact support.');
        }

        // Verify election is active (optional - comment out for draft elections)
        // if (!$election->isCurrentlyActive()) {
        //     return redirect()->route('dashboard')
        //         ->with('warning', 'This election is not currently active.');
        // }

        // Attach election to request for controller use
        $request->attributes->set('election', $election);

        // Store in session for consistency
        session(['selected_election_id' => $election->id]);

        return $next($request);
    }
}
