<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Command;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;

/**
 * EM-IMPL-002 — UC-3 command DTO (name standing: D-7/EM-OPEN-045 placeholder).
 * The entry point a FUTURE external-authority adapter would call; no internal
 * caller exists (D-1; A-3). The appointee is an opaque reference (A-2; D-8).
 */
final readonly class FillCommitteeSeatCommand
{
    public function __construct(
        public ElectionId $electionId,
        public CommitteeSeatId $seatId,
        public string $appointeeReference,
    ) {
    }
}
