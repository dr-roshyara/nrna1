<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Repository;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;

/**
 * Persistence-independent contract for AG-1 (repositories exist for aggregates
 * only — repo Rule 9). Persisted state is a PROJECTION of recorded facts —
 * rebuildable, never contradicting the protocol, never richer than the record
 * (B-7; P-2H). Derived classifications (unable-to-function) are computed, never
 * stored as authoritative columns (EM-GOV-065; DD-1). No storage technology is
 * chosen (G-6); no adapter exists in this increment.
 */
interface ElectionCommitteeRepository
{
    public function find(ElectionId $electionId): ?ElectionCommittee;

    public function save(ElectionCommittee $committee): void;
}
