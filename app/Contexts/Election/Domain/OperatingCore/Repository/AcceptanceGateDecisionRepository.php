<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Repository;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;

/**
 * Persistence-independent contract for AG-2 — one decision record per gate per
 * election (EM-ARCH-001 §2c). The interval state (OPEN/…) is DERIVED and must
 * never be persisted as an authoritative column — storing it would create a second
 * truth that could drift from the record (EM-GOV-068; DD-1; §5e). No storage
 * technology is chosen (G-6); no adapter exists in this increment.
 */
interface AcceptanceGateDecisionRepository
{
    public function find(ElectionId $electionId, GateDesignation $gate): ?AcceptanceGateDecision;

    public function save(AcceptanceGateDecision $decision): void;
}
