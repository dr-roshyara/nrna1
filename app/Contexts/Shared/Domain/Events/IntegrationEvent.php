<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Events;

final readonly class IntegrationEvent
{
    public function __construct(
        public string $eventType,
        public string $aggregateType,
        public string $aggregateId,
        public string $organisationId,
        public array $payload,
        public \DateTimeInterface $occurredAt,
    ) {}

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getAggregateType(): string
    {
        return $this->aggregateType;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getOrganisationId(): string
    {
        return $this->organisationId;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }
}
