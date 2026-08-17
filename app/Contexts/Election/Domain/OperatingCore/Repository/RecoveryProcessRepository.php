<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Repository;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;

/**
 * Persistence-independent contract for AG-3 — one process per governed period kind
 * per election: the Committee-restoration allowance is an ELECTION-LEVEL allowance,
 * never per-failure (EM-GOV-061(b)), so a second instance of the same kind must
 * never be created for the same election. Clock readings are computed from the
 * recorded intervals, never stored (DD-1; EM-GOV-065). No storage technology is
 * chosen (G-6); no adapter exists in this increment.
 */
interface RecoveryProcessRepository
{
    public function find(ElectionId $electionId, PeriodKind $kind): ?RecoveryProcess;

    public function save(RecoveryProcess $process): void;
}
