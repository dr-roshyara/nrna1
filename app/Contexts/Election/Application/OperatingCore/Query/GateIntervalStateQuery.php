<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Query;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UQ-1 (GREEN-1 surface; behaviour arrives in GREEN-7).
 * Derivation-only (§2b): records nothing, stores nothing, decides nothing —
 * the answer is the domain's I-11 derivation, returned, never held (W-4/DD-1).
 */
final class GateIntervalStateQuery
{
    public function __construct(
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly ElectionCommitteeRepository $committees,
    ) {
    }

    public function execute(ElectionId $electionId, GateDesignation $gate): GateIntervalState
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-7 pending: UQ-1 is not implemented yet.');
    }
}
