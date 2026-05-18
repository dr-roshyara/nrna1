<?php

namespace App\Domain\Election\Events;

use DateTimeImmutable;

/**
 * BulkVotersAssignedToElection — Domain event
 *
 * Fired after bulk voter assignment completes (even with failures).
 * Contains aggregate counts for the bulk operation.
 *
 * Listeners:
 * - Cache invalidation: forget all voter-related keys once
 * - Audit logging: log bulk operation summary
 * - Event bus: propagate aggregate result
 *
 * Dispatched OUTSIDE transaction (after final commit) to ensure consistency.
 */
final class BulkVotersAssignedToElection
{
    public function __construct(
        public readonly string $electionId,
        public readonly string $organisationId,
        public readonly int $successCount,
        public readonly int $alreadyExistingCount,
        public readonly int $invalidCount,
        public readonly int $failedCount,
        public readonly ?string $assignedBy,
        public readonly ?string $idempotencyKey,
        public readonly DateTimeImmutable $occurredAt,
    ) {}

    public function totalProcessed(): int
    {
        return $this->successCount + $this->alreadyExistingCount + $this->invalidCount + $this->failedCount;
    }
}
