<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Events;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ContestedOutcomeRef;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;
use App\Contexts\Contestation\Domain\DomainEvent;
use DateTimeImmutable;

/**
 * Internal domain event (Contestation) — NOT part of the published language;
 * may evolve in place until promoted to a published integration event
 * (ADR-PL-01). Carries the ContestedOutcomeRef the challenge contests (ADR-UL-01).
 */
final readonly class ChallengeRaised implements DomainEvent
{
    public function __construct(
        public ChallengeId $challengeId,
        public RaiserStandingRef $raiser,
        public ContestedOutcomeRef $contestedOutcome,
        public SubmittedContent $content,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
