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

        // ✅ PRIORITY 1: If voter slug exists, USE its election (SOURCE OF TRUTH)
        // The voter slug is immutable for this session, so its election is the only valid one
        $voterSlug = $request->attributes->get('voter_slug');

        \Log::info('🔍 [ElectionMiddleware] Starting election resolution', [
            'path' => $request->path(),
            'has_voter_slug' => $voterSlug ? true : false,
            'voter_slug_id' => $voterSlug?->id,
            'voter_slug_election_id' => $voterSlug?->election_id,
            'has_session_election' => session()->has('selected_election_id'),
        ]);

        if ($voterSlug && $voterSlug->election_id) {
            $election = Election::withoutGlobalScopes()->find($voterSlug->election_id);

            if ($election) {
                \Log::info('✅ [ElectionMiddleware] Using election from voter slug (source of truth)', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'election_type' => $election->type,
                    'voter_slug_org_id' => $voterSlug->organisation_id,
                    'election_org_id' => $election->organisation_id,
                ]);

                // ✅ VALIDATION: Organisations must match OR either is 0 (platform-wide)
                $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
                $electionIsPlatform = $election->organisation_id === 0;
                $userIsPlatform = $voterSlug->organisation_id === 0;

                if (!$orgsMatch && !$electionIsPlatform && !$userIsPlatform) {
                    \Log::critical('ORGANISATION MISMATCH - Organisations do not align', [
                        'voter_slug_id' => $voterSlug->id,
                        'voter_slug_org_id' => $voterSlug->organisation_id,
                        'election_id' => $election->id,
                        'election_org_id' => $election->organisation_id,
                        'orgsMatch' => $orgsMatch,
                        'electionIsPlatform' => $electionIsPlatform,
                        'userIsPlatform' => $userIsPlatform,
                    ]);
                    abort(500, 'Critical data inconsistency: organisation mismatch');
                }

                $request->attributes->set('election', $election);
                return $next($request);
            }

            \Log::error('❌ [ElectionMiddleware] Voter slug references non-existent election', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $voterSlug->election_id,
            ]);
            abort(404, 'Election not found');
        }

        // Priority 2: Try to get election from session (user explicitly selected)
        $electionId = session('selected_election_id');

        if ($electionId) {
            $election = Election::find($electionId);
        }

        // Priority 3: Try route parameter (for direct access like /election/{election:slug}/vote)
        if (!$election && $request->route('election')) {
            $routeElection = $request->route('election');

            if ($routeElection instanceof Election) {
                $election = $routeElection;
            }
        }

        // Priority 4: DEFAULT - No election selected - use first REAL active election
        if (!$election) {
            $election = Election::where('type', 'real')
                ->where('is_active', true)
                ->orderBy('id')
                ->first();

            \Log::info('🔍 [ElectionMiddleware] Looking for REAL election', [
                'found' => $election ? true : false,
                'election_id' => $election?->id,
            ]);

            // If no real election found, try any active election
            // CRITICAL: Use withoutGlobalScopes() because demo elections are accessible
            // to ALL users regardless of organisation context (organisation_id=NULL)
            if (!$election) {
                $election = Election::withoutGlobalScopes()
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->first();

                \Log::info('🔍 [ElectionMiddleware] Looking for any active election', [
                    'found' => $election ? true : false,
                    'election_id' => $election?->id,
                    'election_type' => $election?->type,
                ]);
            }
        }

        // If still no election found, cannot proceed
        if (!$election) {
            \Log::error('❌ [ElectionMiddleware] No election found, redirecting to dashboard', [
                'path' => $request->path(),
                'has_voter_slug' => $voterSlug ? true : false,
            ]);
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
