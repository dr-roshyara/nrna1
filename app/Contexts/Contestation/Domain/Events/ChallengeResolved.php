<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Events;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\DomainEvent;
use DateTimeImmutable;

final readonly class ChallengeResolved implements DomainEvent
{
    public function __construct(
        public ChallengeId $challengeId,
        public DeterminationId $determinationId,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
