<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Events;

use App\Contexts\Shared\Domain\DomainEvent;
use DateTimeImmutable;

final readonly class CommitteeCreated implements DomainEvent
{
    public function __construct(
        public string $aggregateId,
        public string $name,
        public int $levelIndex,
        public DateTimeImmutable $occurredAt,
        public int $eventVersion = 1,
        public ?string $correlationId = null,
        public ?string $causationId = null,
    ) {}

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function eventVersion(): int
    {
        return $this->eventVersion;
    }

    public function correlationId(): ?string
    {
        return $this->correlationId;
    }

    public function causationId(): ?string
    {
        return $this->causationId;
    }
}
