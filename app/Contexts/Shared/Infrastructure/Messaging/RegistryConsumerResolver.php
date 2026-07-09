<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Messaging;

use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;

/**
 * Registry-backed Consumer Discovery (ADR-MP-06). Uses ONLY the registry's additive
 * `handlersFor()` query and applies the ordering contract here: stable consumer
 * identifier ascending (currently `consumerContext()`), so the ordered set is
 * reproducible regardless of provider boot/registration order.
 */
final class RegistryConsumerResolver implements ConsumerResolver
{
    public function __construct(private readonly InboxHandlerRegistry $registry)
    {
    }

    /**
     * @return list<InboxHandler>
     */
    public function consumersFor(string $eventType): array
    {
        $consumers = $this->registry->handlersFor($eventType);
        usort(
            $consumers,
            static fn (InboxHandler $a, InboxHandler $b): int => strcmp($a->consumerContext(), $b->consumerContext()),
        );

        return $consumers;
    }
}
