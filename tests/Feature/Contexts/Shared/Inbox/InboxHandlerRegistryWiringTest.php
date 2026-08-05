<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Inbox;

use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use Tests\TestCase;

/**
 * PB-003-C4 — container wiring for the InboxHandlerRegistry.
 *
 * Traceability: Blueprint §6 · ADR-T4 · Matrix: Inbox · Context: Shared Infrastructure
 *
 * Contract: registry is a container singleton (like EventHydratorRegistry) so
 * each consuming context's provider registers its handlers into the one shared
 * instance — no shared-infrastructure edit needed to add a context (open/closed).
 */
final class InboxHandlerRegistryWiringTest extends TestCase
{
    public function test_registry_is_a_container_singleton(): void
    {
        $first = $this->app->make(InboxHandlerRegistry::class);
        $second = $this->app->make(InboxHandlerRegistry::class);

        $this->assertSame($first, $second);
    }
}
