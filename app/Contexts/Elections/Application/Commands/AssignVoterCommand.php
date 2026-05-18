<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Domain\Election\Enum\ElectionMode;

/**
 * AssignVoterCommand — Assign a single voter to an election
 *
 * Responsibility: Carry command data from controller to handler.
 * No logic — pure DTO.
 *
 * Fields:
 * - userId, electionId, organisationId: Voter identity (explicit tenant isolation)
 * - mode: Election-only or full membership (routes to correct policy)
 * - assignedBy: Audit trail (admin who performed assignment)
 * - metadata: Optional context (selection reason, import batch ID, etc.)
 */
final readonly class AssignVoterCommand
{
    public function __construct(
        public string $userId,
        public string $electionId,
        public string $organisationId,
        public ElectionMode $mode,
        public ?string $assignedBy = null,
        public array $metadata = [],
    ) {}
}
