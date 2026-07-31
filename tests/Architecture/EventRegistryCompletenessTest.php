<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use Tests\TestCase;

/**
 * Architecture fitness: every domain event produced through the outbox has
 * exactly one registered hydrator, and the relay contains no hardcoded
 * event-type matching (the Event Registry is the ONLY hydration path).
 *
 * Traceability:
 *   Blueprint: Push B §6, §16 steps 4-5
 *   ADR:       ADR-T3, ADR-T5
 *   Matrix:    Push B — Event Registry / Relay Registry
 *   Context:   Shared Infrastructure (governance)
 */
final class EventRegistryCompletenessTest extends TestCase
{
    /**
     * Every event type written to `outbox_events` by a producer MUST have a
     * registered hydrator. Extend this list when a new outbox adapter ships —
     * the Architecture Review Checklist enforces that step.
     */
    private const PRODUCED_EVENT_TYPES = [
        'FeePaid',              // Membership (OutboxWriter / OutboxService)
        'DeterminationIssued',  // Adjudication (OutboxEventAdapter)
        'AdjudicationExpired',  // Adjudication (OutboxEventAdapter) -- WP-6
    ];

    public function test_every_produced_event_type_has_exactly_one_hydrator(): void
    {
        $registry = $this->app->make(EventHydratorRegistry::class);

        foreach (self::PRODUCED_EVENT_TYPES as $eventType) {
            $this->assertTrue(
                $registry->has($eventType),
                "Produced event type '{$eventType}' has no registered hydrator — "
                . 'register one in the owning context\'s service provider.'
            );
        }
    }

    /**
     * Chief Architect pre-condition: the registry must have REPLACED the old
     * mechanism, not run beside it. No match on event_type, no per-event
     * hydrate methods may remain in the shared processor.
     */
    public function test_processor_contains_no_hardcoded_event_matching(): void
    {
        $source = file_get_contents(
            app_path('Contexts/Shared/Infrastructure/Outbox/OutboxEventProcessor.php')
        );

        $this->assertIsString($source);
        $this->assertStringNotContainsString(
            'match($event->event_type)',
            $source,
            'Hardcoded event-type match must be replaced by the Event Registry'
        );
        $this->assertStringNotContainsString(
            'hydrateFeePaid',
            $source,
            'Per-event hydrate methods must move to context-owned hydrators'
        );
    }
}
