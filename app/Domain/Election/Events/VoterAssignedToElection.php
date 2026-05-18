<?php

namespace App\Domain\Election\Events;

use DateTimeImmutable;

/**
 * VoterAssignedToElection — Domain event
 *
 * Fired after a voter is successfully assigned to an election.
 * This is a business-significant event (voter now eligible to participate).
 *
 * Listeners:
 * - Cache invalidation: forget voter_count, voter_stats keys
 * - Audit logging: record assignment in voter_audit channel
 * - Event bus: propagate to other bounded contexts
 *
 * Dispatched OUTSIDE transaction (after commit) to ensure consistency.
 */
final class VoterAssignedToElection
{
    public function __construct(
        public readonly string $userId,
        public readonly string $electionId,
        public readonly string $organisationId,
        public readonly ?string $assignedBy,
        public readonly DateTimeImmutable $occurredAt,
    ) {}
}
