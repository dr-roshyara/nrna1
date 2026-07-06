<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Outbox;

use App\Contexts\Adjudication\Infrastructure\Outbox\DeterminationIssuedHydrator;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use Tests\TestCase;

/**
 * Container wiring for the Event Registry.
 *
 * Traceability:
 *   Blueprint: Push B §6, §16 step 4
 *   ADR:       ADR-T3
 *   Matrix:    Push B — Event Registry
 *   Context:   Shared Infrastructure (singleton) + Adjudication (registration)
 *
 * Contract: the registry is a container singleton; each context's service
 * provider registers its own hydrators — no shared-infrastructure edit is
 * needed when a context introduces an event.
 */
final class EventHydratorRegistryWiringTest extends TestCase
{
    public function test_registry_is_a_container_singleton(): void
    {
        $first = $this->app->make(EventHydratorRegistry::class);
        $second = $this->app->make(EventHydratorRegistry::class);

        $this->assertSame($first, $second);
    }

    public function test_adjudication_provider_registers_determination_issued_hydrator(): void
    {
        $registry = $this->app->make(EventHydratorRegistry::class);

        $this->assertTrue($registry->has('DeterminationIssued'));
        $this->assertInstanceOf(
            DeterminationIssuedHydrator::class,
            $registry->hydratorFor('DeterminationIssued'),
        );
    }
}
