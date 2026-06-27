<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Election;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\ElectionNotFoundException;
use App\Exceptions\Voting\OrganisationMismatchException;
use App\Exceptions\Voting\ElectionMismatchException;

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
            throw new InvalidVoterSlugException('Voting session context missing', [
                'middleware' => 'VerifyVoterSlugConsistency',
            ]);
        }

        Log::info('🔒 [VerifyVoterSlugConsistency] Starting consistency check', [
            'voter_slug_id' => $voterSlug->id,
            'voter_slug_election_id' => $voterSlug->election_id,
            'voter_slug_org_id' => $voterSlug->organisation_id,
        ]);

        // CHECK 1: Does the referenced election exist?
        // Use optimized query - removed withEssentialRelations() to prevent schema introspection
        $election = Election::withoutGlobalScopes()
            ->find($voterSlug->election_id);

        if (!$election) {
            Log::critical('❌ [VerifyVoterSlugConsistency] Election not found', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $voterSlug->election_id,
            ]);
            throw new ElectionNotFoundException('Voter slug references missing election', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $voterSlug->election_id,
            ]);
        }

        // CHECK 2: Organisation consistency (THE GOLDEN RULE)
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        $electionIsPlatform = $election->organisation_id === 1;
        $userIsPlatform = $voterSlug->organisation_id === 1;

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
            throw new OrganisationMismatchException('Organisation consistency check failed', [
                'voter_slug_org_id' => $voterSlug->organisation_id,
                'election_org_id' => $election->organisation_id,
                'orgs_match' => $orgsMatch,
                'election_is_platform' => $electionIsPlatform,
                'user_is_platform' => $userIsPlatform,
            ]);
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
            throw new ElectionMismatchException('Election type mismatch for route context', [
                'route' => $routeName,
                'election_id' => $election->id,
                'election_type' => $election->type,
                'is_demo_route' => $isDemoRoute,
                'is_demo_election' => $isDemoElection,
            ]);
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
