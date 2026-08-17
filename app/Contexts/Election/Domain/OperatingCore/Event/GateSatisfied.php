<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the election-wide gate was satisfied under the named rule
 * (EM-GOV-005, 052). The gate decides ACCEPTANCE only; progression remains the
 * Constitution-homed lifecycle's (B-1). @immutable
 */
final readonly class GateSatisfied implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public GateDesignation $gate,
        public RecordedInstant $satisfiedAt,
    ) {
    }
}
