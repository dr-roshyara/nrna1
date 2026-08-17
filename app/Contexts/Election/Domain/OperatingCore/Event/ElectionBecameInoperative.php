<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the DERIVED condition "unable to function" (non-vacant seats <
 * required Committee Votes — aggregate arithmetic, never a property of one seat)
 * became true, and Election Inoperative began — at the recorded instant of the
 * vacancy event that breached the arithmetic, with no declaration and no determiner
 * (EM-GOV-065: onset at the recorded vacancy event).
 *
 * The vacancy events themselves are separate recorded INPUT facts
 * (`CommitteeSeatVacated`); this event records the derived CONSEQUENCE becoming
 * true and deliberately carries no seat identity (reviewer correction, 2026-08-17 —
 * G-1/G-4: the model must not imply single-seat causation Governance never
 * defined). Where a halt preceded, both facts are retained (EM-GOV-059(b)).
 * @immutable
 */
final readonly class ElectionBecameInoperative implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public RecordedInstant $onset,
    ) {
    }
}
