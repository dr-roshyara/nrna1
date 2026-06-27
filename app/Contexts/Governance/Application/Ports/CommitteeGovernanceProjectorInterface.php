<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

interface CommitteeGovernanceProjectorInterface
{
    public function rebuild(
        CommitteeId $committeeId,
        DateTimeImmutable $now,
        string $eventId,
        DateTimeImmutable $eventOccurredAt,
        ?string $generation = null,
    ): void;

    public function onEvent(
        CommitteeId $committeeId,
        DateTimeImmutable $now,
        string $eventId,
        string $eventType,
        DateTimeImmutable $eventOccurredAt,
        ?string $generation = null,
    ): void;

    public function rebuildAll(DateTimeImmutable $now, ?string $generation = null): void;
}
