<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteEligibility
{
    /**
     * ✅ FIXED: Handle an incoming request with proper type checking
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        \Illuminate\Support\Facades\Log::info('🔵 [VoteEligibility] Middleware START', [
            'user_id' => $user?->id,
            'has_voter_slug' => $request->attributes->get('voter_slug') !== null,
            'route' => $request->route()->getName(),
        ]);

        // ✅ Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access voting.');
        }

        // ✅ DEMO ELECTIONS: Allow any authenticated user to vote in demo elections
        $electionType = session('selected_election_type');
        if ($electionType === 'demo') {
            // Demo elections bypass voter registration requirements
            return $next($request);
        }

        // New ElectionMembership system: if a voter_slug is present, the full
        // upstream middleware stack (VerifyVoterSlug → EnsureElectionVoter →
        // EnsureVoterStepOrder) has already validated eligibility, voting status,
        // and session window. Skip the legacy is_voter / can_vote column check.
        if ($request->attributes->get('voter_slug')) {
            \Illuminate\Support\Facades\Log::info('✅ [VoteEligibility] Voter slug present - bypassing legacy check');
            return $next($request);
        }

        // Election-scoped check: verify the user is registered as a voter for
        // THIS specific election, not just any election (legacy flow).
        $electionId = $request->input('election_id') ?? $request->route('election');
        if ($electionId && ! $user->isVoterInElection((string) $electionId)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are not registered as a voter for this election.',
                ], 403);
            }
            return redirect()->route('dashboard')
                ->with('error', 'You are not registered as a voter for this election.');
        }

        // ✅ REAL ELECTIONS (legacy flow): Use the improved eligibility check
        if (!$user->isEligibleToVote()) {
            // ✅ Get detailed status for better error message
            $ballotAccess = $user->getBallotAccessStatus();

            $errorMessage = 'You are not eligible to vote.';
            if (isset($ballotAccess['error_message_english'])) {
                $errorMessage = $ballotAccess['error_message_english'];
            }

            // ✅ FIXED: Use the correct route name
            return redirect()->route('dashboard') // or 'electiondashboard' if that's your route name
                ->with('error', $errorMessage)
                ->with('ballot_access_error', $ballotAccess);
        }

        return $next($request);
    }
}