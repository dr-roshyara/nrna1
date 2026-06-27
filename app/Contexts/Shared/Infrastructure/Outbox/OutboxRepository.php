<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

class OutboxRepository
{
    public function getUnprocessed(string $eventClass): array
    {
        // TODO: Query outbox for unprocessed events of given type
        return [];
    }

    public function markProcessed(string $eventId): void
    {
        // TODO: Update outbox record to mark as processed
    }
}
