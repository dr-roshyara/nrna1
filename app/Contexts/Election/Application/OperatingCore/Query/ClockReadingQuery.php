<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Query;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\ClockReading;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UQ-3 (GREEN-1 surface; behaviour arrives in GREEN-7).
 * A clock reading is the domain's DD-1 computation over recorded intervals —
 * this query adds no arithmetic of its own (§2b).
 */
final class ClockReadingQuery
{
    public function __construct(
        private readonly RecoveryProcessRepository $recoveries,
    ) {
    }

    public function execute(ElectionId $electionId, PeriodKind $kind, RecordedInstant $at): ClockReading
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-7 pending: UQ-3 is not implemented yet.');
    }
}
