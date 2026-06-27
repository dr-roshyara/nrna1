<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Events;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;
use App\Contexts\Contestation\Domain\Challenge\TargetRef;
use App\Contexts\Contestation\Domain\DomainEvent;
use DateTimeImmutable;

final readonly class ChallengeRaised implements DomainEvent
{
    public function __construct(
        public ChallengeId $challengeId,
        public RaiserStandingRef $raiser,
        public TargetRef $target,
        public SubmittedContent $content,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
