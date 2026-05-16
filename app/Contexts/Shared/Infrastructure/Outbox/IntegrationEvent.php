<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use DateTimeInterface;

final readonly class IntegrationEvent
{
    public function __construct(
        public string $eventId,
        public string $eventType,
        public string $aggregateType,
        public string $aggregateId,
        public string $organisationId,
        public array $payload,
        public DateTimeInterface $occurredAt,
    ) {}
}
