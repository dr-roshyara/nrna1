<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the Committee was restored and the election RETURNS to its prior
 * condition at the UNRESOLVED gate — restored ability to decide, never a deemed
 * decision (EM-GOV-059(c), 005). @immutable
 */
final readonly class ElectionRestored implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public GateDesignation $returnsToGate,
        public RecordedInstant $restoredAt,
    ) {
    }
}
