<?php

declare(strict_types=1);

namespace Tests\Support\Adjudication;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Domain\DomainEvent;

/** In-memory test double for the EventOutbox port. */
final class InMemoryEventOutbox implements EventOutbox
{
    /** @var list<DomainEvent> */
    private array $events = [];

    public function enqueue(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    /** @return list<DomainEvent> */
    public function enqueued(): array
    {
        return $this->events;
    }
}
