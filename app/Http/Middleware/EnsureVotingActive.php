<?php

namespace App\Http\Middleware;

use App\Application\Election\Facades\ElectionLifecycle;
use Closure;
use Illuminate\Http\Request;

/**
 * EnsureVotingActive Middleware
 *
 * Constitutional runtime guard: verifies the election is in VotingActive state
 * before allowing access to voting routes.
 *
 * DESIGN DECISION: This middleware DELEGATES to ElectionLifecycle::canVote()
 * rather than duplicating governance logic. It does NOT check state names
 * directly, does NOT hardcode 'voting_active', and does NOT bypass the
 * constitutional engine.
 *
 * INVARIANT A: A voter MUST NOT cast a vote unless canVote() returns true.
 * INVARIANT D: Middleware enforcement is defense-in-depth, not primary authority.
 * INVARIANT H: Suspension overrides — canVote() returns false when suspended.
 *
 * Usage: Route::middleware('voting.active') ...
 */
class EnsureVotingActive
{
    /**
     * Handle an incoming request.
     *
     * Resolves the election from request attributes (set by upstream middleware
     * like ElectionMiddleware or VerifyVoterSlugConsistency) and delegates to
     * the constitutional authority.
     *
     * This is orchestration-layer defense-in-depth. The controller-level
     * canVote() check remains the primary constitutional gate.
     */
    public function handle(Request $request, Closure $next)
    {
        $election = $this->resolveElection($request);

        if (! $election) {
            return redirect()->route('dashboard')
                ->with('error', 'No election context available for voting.');
        }

        $lifecycle = ElectionLifecycle::of($election);

        if (! $lifecycle->canVote()) {
            $blockedReason = $lifecycle->blockedReason()
                ?? 'Voting is not currently active.';

            \Illuminate\Support\Facades\Log::info('🔴 [EnsureVotingActive] Blocked', [
                'election_id' => $election->id,
                'state' => $lifecycle->state()->value,
                'reason' => $blockedReason,
            ]);

            return redirect()->route('dashboard')
                ->with('error', $blockedReason);
        }

        return $next($request);
    }

    /**
     * Resolve the Election model from the request.
     *
     * Priority order:
     * 1. Request attributes (set by VerifyVoterSlugConsistency or ElectionMiddleware)
     * 2. Route parameter (implicit model binding)
     */
    private function resolveElection(Request $request)
    {
        $fromAttributes = $request->attributes->get('election');
        if ($fromAttributes) {
            return $fromAttributes;
        }

        $routeElection = $request->route('election');
        if ($routeElection) {
            return $routeElection;
        }

        return null;
    }
}
