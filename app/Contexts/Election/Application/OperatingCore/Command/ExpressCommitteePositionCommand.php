<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Command;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;

/**
 * EM-IMPL-002 — UC-1 command DTO (name standing: D-7/EM-OPEN-045 placeholder).
 * An immutable request record: no caller surface, no caller-supplied instants
 * (A-2; D-8 — the recording instant arrives from the InstantSource at handling).
 */
final readonly class ExpressCommitteePositionCommand
{
    public function __construct(
        public ElectionId $electionId,
        public GateDesignation $gate,
        public CommitteeSeatId $seatId,
        public AcceptancePosition $position,
    ) {
    }
}
