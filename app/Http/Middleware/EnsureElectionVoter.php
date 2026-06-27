<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

class EnsureElectionVoter
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        \Illuminate\Support\Facades\Log::info('🔵 [EnsureElectionVoter] START', [
            'user_id' => $user?->id,
            'route' => $request->route()->getName(),
        ]);

        if (! $user) {
            return redirect()->route('login');
        }

        $election = $this->resolveElection($request);

        if (! $election) {
            abort(404, 'Election not found');
        }

        // Demo elections use the legacy code-based system — bypass this check.
        if ($election->type === 'demo') {
            \Illuminate\Support\Facades\Log::info('✅ [EnsureElectionVoter] Demo election - bypassing');
            return $next($request);
        }

        // Real election: verify the user has an active voter membership.
        $isVoter = $user->isVoterInElection($election->id);
        \Illuminate\Support\Facades\Log::info('🔵 [EnsureElectionVoter] Checking voter status', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'is_voter' => $isVoter,
        ]);

        if (! $isVoter) {
            \Illuminate\Support\Facades\Log::warning('❌ [EnsureElectionVoter] NOT voter - redirecting to dashboard');
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are not eligible to vote in this election.',
                ], 403);
            }

            return redirect()->route('election.dashboard')
                ->with('error', 'You are not eligible to vote in this election.');
        }

        \Illuminate\Support\Facades\Log::info('✅ [EnsureElectionVoter] Voter verified - proceeding');

        // Store the resolved election in the request for downstream controllers.
        $request->merge(['verified_election' => $election]);

        return $next($request);
    }

    /**
     * Resolve the Election model from the route.
     * Handles both model-bound {election} parameters and raw UUID strings.
     */
    private function resolveElection(Request $request): ?Election
    {
        // 1. Request attributes — set by VerifyVoterSlugConsistency for slug routes
        //    (slug routes have no {election} route parameter, so this must come first)
        $fromAttributes = $request->attributes->get('election');
        if ($fromAttributes instanceof Election) {
            return $fromAttributes;
        }

        // 2. Route parameters — for org-prefixed routes with explicit {election}
        $value = $request->route('election')
            ?? $request->route('electionId')
            ?? $request->route('id');

        if (! $value) {
            return null;
        }

        if ($value instanceof Election) {
            return $value;
        }

        return Election::withoutGlobalScopes()->find($value);
    }
}
