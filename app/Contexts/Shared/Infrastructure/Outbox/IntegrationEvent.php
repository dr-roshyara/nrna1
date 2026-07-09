<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use DateTimeInterface;

final readonly class IntegrationEvent
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public string $eventId,
        public string $eventType,
        public string $aggregateType,
        public string $aggregateId,
        public string $organisationId,
        public array $payload,
        public DateTimeInterface $occurredAt,
        // D-1 (ADR-MP-06, additive): messaging concerns propagated to consumer inboxes
        // (D-06); nullable because outbox rows may predate correlation stamping.
        public ?string $correlationId = null,
        public ?string $causationId = null,
    ) {}
}
