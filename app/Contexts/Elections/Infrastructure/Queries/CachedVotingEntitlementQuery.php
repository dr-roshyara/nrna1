<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use Illuminate\Support\Facades\Cache;

/**
 * CachedVotingEntitlementQuery — caching decorator for the voter's STANDING only.
 *
 * - standingOf(): cached per (user, election) under VotingEntitlementCacheKey,
 *   TTL config('election.voter_cache_ttl'). Invalidated by ElectionMembership
 *   save/delete (ElectionMembership::booted) and User::invalidateVoterCache().
 * - decide(): never cached — the election lifecycle is always evaluated fresh.
 *
 * The in-transaction check at vote.store bypasses this decorator entirely and
 * asks EloquentVotingEntitlementQuery, so a stale entry can never authorise a ballot.
 */
final class CachedVotingEntitlementQuery implements VotingEntitlementQuery
{
    public function __construct(private readonly EloquentVotingEntitlementQuery $inner) {}

    public function standingOf(string $userId, string $electionId): VotingEntitlementReason
    {
        $value = Cache::remember(
            VotingEntitlementCacheKey::for($userId, $electionId),
            config('election.voter_cache_ttl', 300),
            fn () => $this->inner->standingOf($userId, $electionId)->value,
        );

        return VotingEntitlementReason::from($value);
    }

    public function decide(string $userId, string $electionId): VotingEntitlementReason
    {
        return $this->inner->decide($userId, $electionId);
    }
}
