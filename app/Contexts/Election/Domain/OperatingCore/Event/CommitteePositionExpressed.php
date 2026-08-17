<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: a seat expressed a position at a gate — including dissent that
 * changes nothing (EM-GOV-005, 031). No voter↔vote linkage (ADR-T11). @immutable
 */
final readonly class CommitteePositionExpressed implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public GateDesignation $gate,
        public CommitteeSeatId $seatId,
        public AcceptancePosition $position,
        public RecordedInstant $expressedAt,
    ) {
    }
}
