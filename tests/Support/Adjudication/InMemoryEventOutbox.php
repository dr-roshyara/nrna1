<?php

declare(strict_types=1);

namespace Tests\Support\Adjudication;

use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Shared\Application\Messaging\EventProvenance;

/** In-memory test double for the EventOutbox port. */
final class InMemoryEventOutbox implements EventOutbox
{
    /** @var list<DomainEvent> */
    private array $events = [];

    public ?EventProvenance $provenance = null;

    public function enqueue(EventProvenance $provenance, DomainEvent ...$events): void
    {
        $this->provenance = $provenance;
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
