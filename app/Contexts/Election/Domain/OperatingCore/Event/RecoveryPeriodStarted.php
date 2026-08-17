<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: a governed period started, with its service-policy version and
 * duration BOUND at start (EM-GOV-050(b), 059(a), 014 Part 2). @immutable
 */
final readonly class RecoveryPeriodStarted implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public PeriodKind $kind,
        public PolicyBinding $policyBinding,
        public RecordedInstant $startedAt,
    ) {
    }
}
