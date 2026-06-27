<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Events;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\DomainEvent;
use DateTimeImmutable;

/**
 * Legal finality: a binding determination now exists for the challenge
 * (ADR-T20 — `Adjudicated` ≠ `Resolved`). Emitted when the Challenge reacts to
 * `DeterminationIssued`. Distinct from `ChallengeResolved` (operational
 * completion, emitted later on `ElectionCorrectionApplied`).
 */
final readonly class ChallengeAdjudicated implements DomainEvent
{
    public function __construct(
        public ChallengeId $challengeId,
        public DeterminationId $determinationId,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
