<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Messaging;

use App\Contexts\Shared\Application\Inbox\InboxHandler;

/**
 * Consumer Discovery capability of the Messaging Platform (ADR-MP-06): which consumers
 * receive a published integration event? The dispatcher depends on THIS, never on the
 * registry directly — the registry is one implementation behind it.
 *
 * Contract: DETERMINISTIC ORDERED discovery — given identical registry state and event
 * type, the identical ordered consumer set is returned, ordered by a **stable consumer
 * identifier** (currently `consumerContext()` ascending; the contract is stability, not
 * any particular comparison).
 */
interface ConsumerResolver
{
    /**
     * @return list<InboxHandler> ordered by stable consumer identifier
     */
    public function consumersFor(string $eventType): array;
}
