<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Shared\Outbox;

use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\UnregisteredEventType;
use PHPUnit\Framework\TestCase;

/**
 * Event Registry — eliminates hardcoded event matching in the outbox relay.
 *
 * Traceability:
 *   Blueprint: Push B §6 (Relay Registry), §16 step 4 (Event Registry)
 *   ADR:       ADR-T3 (transactional outbox), ADR-T5 (event versioning)
 *   Matrix:    Push B — Event Registry
 *   Context:   Shared Infrastructure (registry) — entries owned per context
 *
 * Contract under test:
 *   - context-owned registration (hydrators register themselves by event type)
 *   - lookup returns the exact hydrator for a registered type
 *   - unknown event types FAIL LOUDLY with a dedicated exception carrying the
 *     dead-letter reason UNREGISTERED_EVENT_TYPE (Blueprint §6/§7 F2)
 *   - duplicate registration for the same type is rejected (single producer,
 *     single hydration authority — no silent overwrite)
 */
final class EventHydratorRegistryTest extends TestCase
{
    public function test_returns_registered_hydrator_for_its_event_type(): void
    {
        $registry = new EventHydratorRegistry();
        $hydrator = $this->fakeHydrator('DeterminationIssued');

        $registry->register($hydrator);

        $this->assertSame($hydrator, $registry->hydratorFor('DeterminationIssued'));
    }

    public function test_supports_multiple_context_owned_registrations(): void
    {
        $registry = new EventHydratorRegistry();
        $adjudication = $this->fakeHydrator('DeterminationIssued');
        $membership = $this->fakeHydrator('FeePaid');

        $registry->register($adjudication);
        $registry->register($membership);

        $this->assertSame($adjudication, $registry->hydratorFor('DeterminationIssued'));
        $this->assertSame($membership, $registry->hydratorFor('FeePaid'));
    }

    public function test_unknown_event_type_fails_loudly_with_dead_letter_reason(): void
    {
        $registry = new EventHydratorRegistry();

        try {
            $registry->hydratorFor('SomethingNeverRegistered');
            $this->fail('Expected UnregisteredEventType to be thrown');
        } catch (UnregisteredEventType $e) {
            $this->assertStringContainsString('SomethingNeverRegistered', $e->getMessage());
            $this->assertSame('UNREGISTERED_EVENT_TYPE', $e->deadLetterReason());
        }
    }

    public function test_duplicate_registration_for_same_type_is_rejected(): void
    {
        $registry = new EventHydratorRegistry();
        $registry->register($this->fakeHydrator('DeterminationIssued'));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('DeterminationIssued');

        $registry->register($this->fakeHydrator('DeterminationIssued'));
    }

    public function test_reports_whether_a_type_is_registered(): void
    {
        $registry = new EventHydratorRegistry();
        $registry->register($this->fakeHydrator('DeterminationIssued'));

        $this->assertTrue($registry->has('DeterminationIssued'));
        $this->assertFalse($registry->has('FeePaid'));
    }

    private function fakeHydrator(string $eventType): EventHydrator
    {
        return new class($eventType) implements EventHydrator {
            public function __construct(private readonly string $type)
            {
            }

            public function eventType(): string
            {
                return $this->type;
            }

            public function hydrate(array $payload): object
            {
                return new \stdClass();
            }
        };
    }
}
