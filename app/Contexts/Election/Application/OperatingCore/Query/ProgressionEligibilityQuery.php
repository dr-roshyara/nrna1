<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Query;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UQ-2 (GREEN-1 surface; behaviour arrives in GREEN-7).
 * A-7 QUERY FORM: the answer-shape of Meaning-2's deferral, never the act-shape
 * (R-3) — the query answers; performing progression stays the Chief's (B-1).
 */
final class ProgressionEligibilityQuery
{
    public function __construct(
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly ElectionCommitteeRepository $committees,
    ) {
    }

    public function execute(ElectionId $electionId, GateDesignation $gate): bool
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-7 pending: UQ-2 is not implemented yet.');
    }
}
