<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: satisfaction became impossible BY DECISION (R-F2 — at 3/2, two
 * objections). Progression halts at the gate condition; the preceding phase remains
 * completed (EM-GOV-052, 005). A refusal is never a termination (P-2H). @immutable
 */
final readonly class GateFailedByDecision implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public GateDesignation $gate,
        public RecordedInstant $failedAt,
    ) {
    }
}
