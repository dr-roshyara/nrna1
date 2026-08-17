<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the vacancy EVENT that alone makes a seat vacant — ground and
 * reason recorded (EM-GOV-064, 005). The reason is a constrained surface
 * (ADR-T11; EM-OPEN-091②). @immutable
 */
final readonly class CommitteeSeatVacated implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public CommitteeSeatId $seatId,
        public VacancyGround $ground,
        public ?VacancyReason $reason,
        public RecordedInstant $vacatedAt,
    ) {
    }
}
