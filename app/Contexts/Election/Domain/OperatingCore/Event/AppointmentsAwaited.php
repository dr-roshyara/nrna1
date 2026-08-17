<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use InvalidArgumentException;

/**
 * Recorded fact: a pre-Chief halt — recovery initiation is assignable to NOBODY
 * inside the election; the awaited appointments are recorded and only the external
 * authority can supply them (EM-GOV-067). No notification duty exists and none is
 * modelled — EM-GOV-067 expressly declined it. @immutable
 */
final readonly class AppointmentsAwaited implements DomainEvent, NonCanonicalEventName
{
    /** @param non-empty-list<string> $awaitedAppointments */
    public function __construct(
        public ElectionId $electionId,
        public array $awaitedAppointments,
        public RecordedInstant $recordedAt,
    ) {
        if ($awaitedAppointments === []) {
            throw new InvalidArgumentException('A pre-Chief halt awaits at least one appointment (EM-GOV-067).');
        }
    }
}
