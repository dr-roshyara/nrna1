<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Command;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 — UC-4 command DTO (name standing: D-7/EM-OPEN-045 placeholder).
 * A REPORT, not authority (A-4; EM-OPEN-047 resolution): P-6 alone supplies any
 * consequence and can refuse any report (A-2; D-8).
 */
final readonly class ReportPeriodExpiryCommand
{
    public function __construct(
        public ElectionId $electionId,
        public PeriodKind $kind,
    ) {
    }
}
