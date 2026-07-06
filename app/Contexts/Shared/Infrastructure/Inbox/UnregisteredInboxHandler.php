<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

/**
 * Thrown when no handler is registered for a (consumer_context, event_type)
 * pair. Fails loudly — the re-drive command (C5) maps this to an immediate
 * dead-letter with reason UNREGISTERED_INBOX_HANDLER (fix = register the
 * handler in the owning context's provider, then re-drive).
 *
 * Owner: Shared Infrastructure · Layer: Infrastructure
 * Traceability: Blueprint §6/§7 · ADR-T4 · Matrix: Inbox
 * (sibling of Outbox's UnregisteredEventType — ER-03 consistency)
 */
final class UnregisteredInboxHandler extends \RuntimeException
{
    public function __construct(
        private readonly string $consumerContext,
        private readonly string $eventType,
    ) {
        parent::__construct(sprintf(
            'No inbox handler registered for consumer "%s" + event type "%s". Register one in the owning context\'s service provider, then re-drive.',
            $consumerContext,
            $eventType,
        ));
    }

    public function consumerContext(): string
    {
        return $this->consumerContext;
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    /** Dead-letter reason code recorded by the re-drive command (Blueprint §7). */
    public function deadLetterReason(): string
    {
        return 'UNREGISTERED_INBOX_HANDLER';
    }
}
