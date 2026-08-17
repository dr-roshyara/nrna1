<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Command;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;

/**
 * EM-IMPL-002 — UC-2 command DTO (name standing: D-7/EM-OPEN-045 placeholder).
 * The vacancy-recording authority is a NAMED OPEN dependency (M-4/A-6) — this
 * record carries the request shape only (A-2; D-8).
 */
final readonly class RecordVacancyEventCommand
{
    public function __construct(
        public ElectionId $electionId,
        public CommitteeSeatId $seatId,
        public VacancyGround $ground,
        public ?VacancyReason $reason,
    ) {
    }
}
