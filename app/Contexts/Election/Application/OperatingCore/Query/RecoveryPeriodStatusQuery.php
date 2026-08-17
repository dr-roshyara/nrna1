<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Query;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UQ-4 (GREEN-1 surface; behaviour arrives in GREEN-7).
 * Expiry is a QUESTION (EM-OPEN-047 resolution): asking is not reporting; no
 * consequence flows from a query (§2b).
 */
final class RecoveryPeriodStatusQuery
{
    public function __construct(
        private readonly RecoveryProcessRepository $recoveries,
    ) {
    }

    public function execute(ElectionId $electionId, PeriodKind $kind, RecordedInstant $at): bool
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-7 pending: UQ-4 is not implemented yet.');
    }
}
