<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the external authority filled the EXISTING seat (EM-GOV-056, 005).
 * This act has no internal originator — it arrives only through the
 * `OrganisationalAppointmentAuthority` port's future adapter (B-3; D-1). @immutable
 */
final readonly class CommitteeSeatFilled implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public CommitteeSeatId $seatId,
        public string $appointeeReference,
        public RecordedInstant $filledAt,
    ) {
    }
}
