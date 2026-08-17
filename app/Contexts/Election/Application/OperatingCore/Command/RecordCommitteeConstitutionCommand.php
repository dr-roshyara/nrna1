<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Command;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;

/**
 * EM-IMPL-002 — UC-5 command DTO (name standing: D-7/EM-OPEN-045 placeholder).
 * Constitution facts arrive only from the external authority (EM-GOV-026/028);
 * this record carries the request shape only (A-2; D-8).
 */
final readonly class RecordCommitteeConstitutionCommand
{
    /** @var list<CommitteeSeatId> the one documented typed-list exception to the no-arrays rule (Rule 4) */
    public array $seatIds;

    public function __construct(
        public ElectionId $electionId,
        CommitteeSeatId ...$seatIds,
    ) {
        $this->seatIds = array_values($seatIds);
    }
}
