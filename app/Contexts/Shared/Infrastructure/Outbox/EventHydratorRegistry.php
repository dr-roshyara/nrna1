<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

/**
 * Event Registry — maps canonical event type names to context-owned hydrators.
 *
 * Replaces hardcoded event matching in the outbox relay (Gap G-1): contexts
 * register their hydrators in their own service providers; the relay looks up
 * by `outbox_events.event_type`. Unknown types fail loudly (UnregisteredEventType
 * → dead-letter, Blueprint §7 F2). Duplicate registration for the same type is
 * rejected — one event, one producer, one hydration authority (AT-EVT-001 analogue).
 *
 * Traceability — Blueprint: Push B §6, §16 step 4 · ADR: ADR-T3, ADR-T5 ·
 * Matrix: Push B — Event Registry · Context: Shared Infrastructure (container
 * singleton; entries owned per context).
 */
final class EventHydratorRegistry
{
    /** @var array<string, EventHydrator> */
    private array $hydrators = [];

    public function register(EventHydrator $hydrator): void
    {
        $type = $hydrator->eventType();

        if (isset($this->hydrators[$type])) {
            throw new \LogicException(sprintf(
                'A hydrator for event type "%s" is already registered (%s). One event type has exactly one hydration authority.',
                $type,
                $this->hydrators[$type]::class,
            ));
        }

        $this->hydrators[$type] = $hydrator;
    }

    /**
     * @throws UnregisteredEventType when no hydrator is registered for the type
     */
    public function hydratorFor(string $eventType): EventHydrator
    {
        return $this->hydrators[$eventType]
            ?? throw new UnregisteredEventType($eventType);
    }

    public function has(string $eventType): bool
    {
        return isset($this->hydrators[$eventType]);
    }
}
