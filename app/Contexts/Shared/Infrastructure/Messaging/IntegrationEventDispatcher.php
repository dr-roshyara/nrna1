<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Messaging;

use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Infrastructure\Inbox\Inbox;
use App\Contexts\Shared\Infrastructure\Outbox\IntegrationEvent;
use Throwable;

/**
 * The Messaging Platform's DELIVERY capability (ADR-MP-06): carries a published
 * integration event from the relay's output to every consuming context's inbox.
 * Design principle: **Registration ≠ Delivery** — the Inbox does not own delivery;
 * it is one consumer.
 *
 * Four explicit responsibilities:
 *  1. RECEIVE the relay's IntegrationEvent (wired as a listener — D-2; relay unchanged).
 *  2. CONSUMER DISCOVERY via {@see ConsumerResolver} (deterministic ordered set).
 *  3. INBOX MESSAGE CREATION — one InboxMessage per consumer; identity, type, payload,
 *     organisation (ADR-T16), correlation/causation (D-06) propagated from the envelope.
 *     AUDIT CONTINUITY: the InboxMessage carries the SAME event_id as the originating
 *     OutboxEvent — every consumption is traceable to exactly one outbox record.
 *  4. DELIVERY — Inbox::consume per consumer. CONSUMER ATOMICITY/ISOLATION: each
 *     consumer executes in its own inbox transaction (owned by Inbox::consume); one
 *     consumer's park/dead-letter/failure never blocks another, and no partial inbox
 *     state leaks across consumers. A transient infrastructure Throwable is re-thrown
 *     AFTER all consumers were attempted, so the relay's existing redelivery retries
 *     the event (inbox dedupe protects the consumers that already completed).
 *
 * Holds NO state (replay-safe: redelivery lands on inbox dedupe) and NO business
 * decisions (ADR-MP-01). Empty consumer set ⇒ no-op (consumers may not exist yet).
 */
final class IntegrationEventDispatcher
{
    public function __construct(
        private readonly ConsumerResolver $consumers,
        private readonly Inbox $inbox,
    ) {
    }

    public function dispatch(IntegrationEvent $event): void
    {
        $firstTransientFailure = null;

        foreach ($this->consumers->consumersFor($event->eventType) as $consumer) {
            try {
                $this->inbox->consume($this->messageFor($event), $consumer);
            } catch (Throwable $transient) {
                // Consumer isolation: keep delivering to the remaining consumers; the
                // relay's redelivery will retry (dedupe skips completed consumers).
                $firstTransientFailure ??= $transient;
            }
        }

        if ($firstTransientFailure !== null) {
            throw $firstTransientFailure;
        }
    }

    private function messageFor(IntegrationEvent $event): InboxMessage
    {
        return new InboxMessage(
            eventId: $event->eventId,               // audit continuity: same id as the outbox record
            eventType: $event->eventType,
            payload: $event->payload,
            organisationId: $event->organisationId, // tenant propagation (ADR-T16)
            correlationId: $event->correlationId,   // D-06
            causationId: $event->causationId,       // D-06
        );
    }

    /** @codeCoverageIgnore convenience for listener registration */
    public function handle(IntegrationEvent $event): void
    {
        $this->dispatch($event);
    }
}
