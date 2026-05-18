<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Domain\Election\Enum\ElectionMode;

/**
 * BulkAssignVotersCommand — Bulk assign multiple voters to an election
 *
 * Responsibility: Carry command data from controller to handler.
 * No logic — pure DTO.
 *
 * Fields:
 * - userIds: Array of user IDs to assign (pre-validated at request layer)
 * - electionId, organisationId: Voter identity (explicit tenant isolation)
 * - mode: Election-only or full membership (routes to correct policy)
 * - assignedBy: Audit trail (admin who performed bulk assignment)
 * - idempotencyKey: Optional (Phase C.4 deduplication)
 * - chunkSize: Configurable batch size (default 500, override in tests)
 */
final readonly class BulkAssignVotersCommand
{
    public function __construct(
        public array $userIds,
        public string $electionId,
        public string $organisationId,
        public ElectionMode $mode,
        public ?string $assignedBy = null,
        public ?string $idempotencyKey = null,
        public int $chunkSize = 500,
    ) {}
}
