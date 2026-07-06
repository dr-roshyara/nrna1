<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use App\Contexts\Shared\Application\Inbox\InboxHandler;

/**
 * Routes a delivered event to the consuming context's handler, keyed by
 * (consumer_context, event_type). The architectural boundary between generic
 * messaging infrastructure and bounded-context-specific handling: Shared
 * Infrastructure coordinates registration/lookup; it holds NO business
 * knowledge. Consuming contexts register their handlers from their own service
 * providers — a new context registers without modifying Shared Infrastructure
 * (open/closed).
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure (pure PHP — no framework dependency; container singleton via AppServiceProvider)
 * Responsibility: register / look up one handler per (consumer_context, event_type)
 * Traceability: Blueprint §6 · ADR-T4 · D-03 (key includes consumer_context) · Matrix: Inbox
 *
 * Intentional divergence from EventHydratorRegistry (ER-03, justified): keyed by
 * the (consumer_context, event_type) PAIR — the same event is legitimately
 * consumed by multiple contexts (Election + Contestation), each with its own handler.
 */
final class InboxHandlerRegistry
{
    /** @var array<string, InboxHandler> keyed by "consumerContext\0eventType" */
    private array $handlers = [];

    public function register(InboxHandler $handler): void
    {
        $context = $handler->consumerContext();

        foreach ($handler->eventTypes() as $eventType) {
            $key = $this->key($context, $eventType);

            if (isset($this->handlers[$key])) {
                throw new \LogicException(sprintf(
                    'An inbox handler for consumer "%s" + event type "%s" is already registered (%s). One handler per (consumer, event type).',
                    $context,
                    $eventType,
                    $this->handlers[$key]::class,
                ));
            }

            $this->handlers[$key] = $handler;
        }
    }

    /**
     * @throws UnregisteredInboxHandler when no handler is registered for the pair
     */
    public function handlerFor(string $consumerContext, string $eventType): InboxHandler
    {
        return $this->handlers[$this->key($consumerContext, $eventType)]
            ?? throw new UnregisteredInboxHandler($consumerContext, $eventType);
    }

    public function has(string $consumerContext, string $eventType): bool
    {
        return isset($this->handlers[$this->key($consumerContext, $eventType)]);
    }

    private function key(string $consumerContext, string $eventType): string
    {
        return $consumerContext . "\0" . $eventType;
    }
}
