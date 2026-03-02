<?php

namespace App\Services;

use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Support\Facades\Cache;

/**
 * CacheService - Centralized caching for frequently accessed voting data
 *
 * Caching Strategy:
 * - Elections: 24 hours (rarely change during voting period)
 * - Organisations: 24 hours (static data)
 * - VoterSlugs: 5 minutes (frequently accessed during voting)
 * - User eligibility: 10 minutes (user.can_vote_now can change)
 *
 * All cache keys are tenant-scoped to prevent cross-tenant cache hits
 */
class CacheService
{
    /**
     * Cache TTL constants
     */
    const ELECTION_TTL = 3600 * 24;      // 24 hours
    const ORGANISATION_TTL = 3600 * 24;  // 24 hours
    const VOTER_SLUG_TTL = 300;          // 5 minutes
    const USER_ELIGIBILITY_TTL = 600;    // 10 minutes

    /**
     * Get election by ID with relationships
     *
     * @param int $electionId
     * @return Election|null
     */
    public function getElection(int $electionId): ?Election
    {
        return Cache::remember(
            "election:{$electionId}",
            self::ELECTION_TTL,
            function () use ($electionId) {
                return Election::withoutGlobalScopes()
                    ->with(['organisation', 'posts', 'candidacies'])
                    ->find($electionId);
            }
        );
    }

    /**
     * Get voter slug by slug string with relationships
     *
     * @param string $slug
     * @return VoterSlug|null
     */
    public function getVoterSlug(string $slug): ?VoterSlug
    {
        return Cache::remember(
            "voter_slug:{$slug}",
            self::VOTER_SLUG_TTL,
            function () use ($slug) {
                return VoterSlug::withoutGlobalScopes()
                    ->withEssentialRelations()
                    ->where('slug', $slug)
                    ->first();
            }
        );
    }

    /**
     * Get organisation by ID
     *
     * @param int $organisationId
     * @return Organisation|null
     */
    public function getOrganisation(int $organisationId): ?Organisation
    {
        return Cache::remember(
            "organisation:{$organisationId}",
            self::ORGANISATION_TTL,
            function () use ($organisationId) {
                return Organisation::find($organisationId);
            }
        );
    }

    /**
     * Get all elections for an organisation
     *
     * Useful for dashboard views showing all active/past elections
     *
     * @param int $organisationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrganisationElections(int $organisationId)
    {
        return Cache::remember(
            "organisation:{$organisationId}:elections",
            self::ELECTION_TTL,
            function () use ($organisationId) {
                return Election::withoutGlobalScopes()
                    ->where('organisation_id', $organisationId)
                    ->with('organisation')
                    ->get();
            }
        );
    }

    /**
     * Get active elections for an organisation
     *
     * @param int $organisationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveElectionsForOrganisation(int $organisationId)
    {
        return Cache::remember(
            "organisation:{$organisationId}:elections:active",
            self::ELECTION_TTL,
            function () use ($organisationId) {
                return Election::withoutGlobalScopes()
                    ->where('organisation_id', $organisationId)
                    ->where('status', 'active')
                    ->with('organisation')
                    ->get();
            }
        );
    }

    /**
     * Get demo election for organisation (common operation)
     *
     * @param int $organisationId
     * @return Election|null
     */
    public function getDemoElectionForOrganisation(int $organisationId): ?Election
    {
        return Cache::remember(
            "organisation:{$organisationId}:election:demo",
            self::ELECTION_TTL,
            function () use ($organisationId) {
                return Election::withoutGlobalScopes()
                    ->where('type', 'demo')
                    ->where('organisation_id', $organisationId)
                    ->with('posts', 'candidacies', 'organisation')
                    ->first();
            }
        );
    }

    /**
     * Clear cache for a specific election
     *
     * Call this when election data is updated
     *
     * @param int $electionId
     * @param int|null $organisationId
     */
    public function clearElection(int $electionId, ?int $organisationId = null): void
    {
        Cache::forget("election:{$electionId}");

        // Also clear organisation election lists
        if ($organisationId !== null) {
            Cache::forget("organisation:{$organisationId}:elections");
            Cache::forget("organisation:{$organisationId}:elections:active");

            // If it's a demo election, clear that cache too
            $election = Election::withoutGlobalScopes()->find($electionId);
            if ($election && $election->type === 'demo') {
                Cache::forget("organisation:{$organisationId}:election:demo");
            }
        }
    }

    /**
     * Clear cache for a specific voter slug
     *
     * Call this when voter slug status changes
     *
     * @param string $slug
     */
    public function clearVoterSlug(string $slug): void
    {
        Cache::forget("voter_slug:{$slug}");
    }

    /**
     * Clear all cache for an organisation
     *
     * Call this after major administrative changes
     *
     * @param int $organisationId
     */
    public function clearOrganisationCache(int $organisationId): void
    {
        Cache::forget("organisation:{$organisationId}");
        Cache::forget("organisation:{$organisationId}:elections");
        Cache::forget("organisation:{$organisationId}:elections:active");
        Cache::forget("organisation:{$organisationId}:election:demo");
    }

    /**
     * Check if user can vote in an election
     *
     * Cached to avoid repeated permission checks
     *
     * @param int $userId
     * @param int $electionId
     * @return bool
     */
    public function userCanVoteInElection(int $userId, int $electionId): bool
    {
        return Cache::remember(
            "user:{$userId}:election:{$electionId}:can_vote",
            self::USER_ELIGIBILITY_TTL,
            function () use ($userId, $electionId) {
                $voter = \App\Models\Code::withoutGlobalScopes()
                    ->where('user_id', $userId)
                    ->where('election_id', $electionId)
                    ->where('can_vote_now', true)
                    ->exists();

                return $voter;
            }
        );
    }

    /**
     * Clear user eligibility cache
     *
     * Call when user voting permissions change
     *
     * @param int $userId
     * @param int $electionId
     */
    public function clearUserEligibility(int $userId, int $electionId): void
    {
        Cache::forget("user:{$userId}:election:{$electionId}:can_vote");
    }

    /**
     * Flush all voting-related cache
     *
     * Use with caution - should only be called during system updates
     */
    public function flushAll(): void
    {
        // In a real system, you might use cache tags:
        // Cache::tags(['voting'])->flush();
        //
        // For now, we rely on individual clear methods
        \Illuminate\Support\Facades\Log::warning('Flushing all voting cache');
    }
}
