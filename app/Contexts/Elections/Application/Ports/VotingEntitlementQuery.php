<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;

/**
 * VotingEntitlementQuery — the ONE authoritative answer to
 * "is this voter entitled to vote in this election?" (PBDIGIT-213 C1).
 *
 * Every entitlement site (middleware, controllers, the in-transaction check
 * at vote.store) asks this port; none re-states the predicate
 * (tests/Architecture/VotingEntitlementSinglePointTest).
 *
 * Scope is derived from the election, never from ambient tenant context
 * (ADR-002 clarification (i), PBDIGIT-65/69).
 */
interface VotingEntitlementQuery
{
    /** The voter's standing in the election (registration, role, scope, membership status). */
    public function standingOf(string $userId, string $electionId): VotingEntitlementReason;

    /** Standing, then whether the election can be voted in now (lifecycle). */
    public function decide(string $userId, string $electionId): VotingEntitlementReason;
}
