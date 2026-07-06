<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Outbox;

use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Relay Registry — OutboxEventProcessor delegates hydration to the Event
 * Registry; the hardcoded match is gone.
 *
 * Traceability:
 *   Blueprint: Push B §6 (Relay Registry), §7 F2 (unregistered → immediate
 *              dead-letter, no retry), §16 step 5
 *   ADR:       ADR-T3
 *   Matrix:    Push B — Relay Registry refactor (G-1)
 *   Context:   Shared Infrastructure
 */
final class OutboxEventProcessorRegistryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Chief Architect pre-condition for Step 5: given an event registered in
     * the Registry, when the processor runs, the hydrator is called exactly
     * once — proving the registry path has replaced the old implementation.
     */
    public function test_processor_delegates_to_registered_hydrator_exactly_once(): void
    {
        Event::fake();

        $spy = new class implements EventHydrator {
            public int $calls = 0;

            public function eventType(): string
            {
                return 'RegistrySpyEvent';
            }

            public function hydrate(array $payload): object
            {
                $this->calls++;

                return new \stdClass();
            }
        };

        $this->app->make(EventHydratorRegistry::class)->register($spy);

        $row = $this->outboxRow('RegistrySpyEvent');

        $this->app->make(OutboxEventProcessor::class)->processEvent($row);

        $this->assertSame(1, $spy->calls, 'Hydrator must be called exactly once');
        $this->assertSame('completed', $row->fresh()->status);
        Event::assertDispatched(\stdClass::class);
    }

    /**
     * Blueprint §7 F2: unknown event type → IMMEDIATE dead-letter. No retry,
     * no reschedule — retrying cannot fix a missing registration.
     */
    public function test_unregistered_event_type_dead_letters_immediately_without_retry(): void
    {
        Event::fake();

        $row = $this->outboxRow('NeverRegisteredType');

        $this->app->make(OutboxEventProcessor::class)->processEvent($row);

        $fresh = $row->fresh();
        $this->assertSame('failed', $fresh->status, 'Unregistered type must dead-letter immediately');

        // Neither the domain event nor the integration envelope may dispatch
        // for a dead-lettered row (Eloquent lifecycle events are irrelevant here).
        Event::assertNotDispatched(\stdClass::class);
        Event::assertNotDispatched(\App\Contexts\Shared\Infrastructure\Outbox\IntegrationEvent::class);
    }

    private function outboxRow(string $eventType): OutboxEvent
    {
        $organisation = Organisation::factory()->create();
        $id = (string) Str::uuid();

        return OutboxEvent::create([
            'id' => $id,
            'event_id' => $id,
            'organisation_id' => $organisation->id,
            'aggregate_type' => 'Test',
            'aggregate_id' => (string) Str::uuid(),
            'event_type' => $eventType,
            'payload' => ['probe' => true],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]);
    }
}
