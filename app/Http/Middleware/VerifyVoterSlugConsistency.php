<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Election;
use Illuminate\Support\Facades\Log;

class VerifyVoterSlugConsistency
{
    /**
     * VERIFICATION LEVEL 3: Consistency (CRITICAL)
     *
     * Validates that voter_slug, election, and organisation ALL match:
     * 1. Election exists and matches slug's election_id
     * 2. Organisation matches across all three entities
     * 3. Election type matches route context (demo vs real)
     * 4. No inconsistencies detected
     */
    public function handle($request, Closure $next)
    {
        $voterSlug = $request->attributes->get('voter_slug');

        if (!$voterSlug) {
            Log::critical('❌ [VerifyVoterSlugConsistency] No voter slug in request');
            abort(500, 'Voting session context missing');
        }

        Log::info('🔒 [VerifyVoterSlugConsistency] Starting consistency check', [
            'voter_slug_id' => $voterSlug->id,
            'voter_slug_election_id' => $voterSlug->election_id,
            'voter_slug_org_id' => $voterSlug->organisation_id,
        ]);

        // CHECK 1: Does the referenced election exist?
        // Use optimized query with selective columns
        $election = Election::withoutGlobalScopes()
            ->withEssentialRelations()
            ->find($voterSlug->election_id);

        if (!$election) {
            Log::critical('❌ [VerifyVoterSlugConsistency] Election not found', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $voterSlug->election_id,
            ]);
            abort(500, 'Referenced election not found');
        }

        // CHECK 2: Organisation consistency (THE GOLDEN RULE)
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        $electionIsPlatform = $election->organisation_id === 0;
        $userIsPlatform = $voterSlug->organisation_id === 0;

        // Valid if: same org OR election is platform OR user is platform
        $orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;

        if (!$orgsValid) {
            Log::critical('❌ [VerifyVoterSlugConsistency] ORGANISATION MISMATCH', [
                'voter_slug_id' => $voterSlug->id,
                'voter_slug_org_id' => $voterSlug->organisation_id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
                'orgsMatch' => $orgsMatch,
                'electionIsPlatform' => $electionIsPlatform,
                'userIsPlatform' => $userIsPlatform,
            ]);
            abort(500, 'Organisation inconsistency detected');
        }

        // CHECK 3: Election type matches route context
        $routeName = $request->route()->getName();
        $isDemoRoute = str_contains($routeName, 'demo-');
        $isDemoElection = $election->type === 'demo';

        if ($isDemoRoute && !$isDemoElection) {
            Log::error('❌ [VerifyVoterSlugConsistency] Demo route with non-demo election', [
                'route' => $routeName,
                'election_id' => $election->id,
                'election_type' => $election->type,
            ]);
            abort(403, 'Invalid election type for this route');
        }

        // CHECK 4: Vote completion status
        if ($voterSlug->vote_completed) {
            Log::info('ℹ️ [VerifyVoterSlugConsistency] Vote already completed', [
                'voter_slug_id' => $voterSlug->id,
            ]);

            // Allow access to thank-you page only
            if (!str_contains($routeName, 'thank-you') && !str_contains($routeName, 'verify_to_show')) {
                return redirect()->route('vote.verify_to_show')
                    ->with('info', 'You have already completed voting.');
            }
        }

        // Store election in request for controllers
        $request->attributes->set('election', $election);

        // Store validation results for debugging
        $request->attributes->set('consistency_check', [
            'orgs_valid' => $orgsValid,
            'orgs_match' => $orgsMatch,
            'election_is_platform' => $electionIsPlatform,
            'user_is_platform' => $userIsPlatform,
            'route_matches_type' => (!$isDemoRoute || $isDemoElection),
        ]);

        Log::info('✅ [VerifyVoterSlugConsistency] All checks passed', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'organisation_id' => $voterSlug->organisation_id,
            'orgs_valid' => $orgsValid,
        ]);

        return $next($request);
    }
}
