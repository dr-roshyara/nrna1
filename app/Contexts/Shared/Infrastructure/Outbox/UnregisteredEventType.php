<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

/**
 * Thrown when the relay encounters an outbox event type with no registered
 * hydrator. Fails loudly by design — the relay maps this to an immediate
 * dead-letter with reason UNREGISTERED_EVENT_TYPE (Blueprint §7 F2: no retry,
 * no silent drop; the fix is registering a hydrator, then re-driving).
 *
 * Traceability — Blueprint: Push B §6, §7 (F2) · ADR: ADR-T3 ·
 * Matrix: Push B — Event Registry · Context: Shared Infrastructure.
 */
final class UnregisteredEventType extends \RuntimeException
{
    public function __construct(private readonly string $eventType)
    {
        parent::__construct(sprintf(
            'No hydrator registered for event type "%s". Register one in the owning context\'s service provider, then re-drive the dead-lettered row.',
            $eventType,
        ));
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    /** Dead-letter reason code recorded by the relay (Blueprint §7 F2). */
    public function deadLetterReason(): string
    {
        return 'UNREGISTERED_EVENT_TYPE';
    }
}
