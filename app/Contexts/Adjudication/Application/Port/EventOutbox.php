<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Shared\Application\Messaging\EventProvenance;

/**
 * Application Port (NOT an infrastructure service): the application service
 * enqueues domain events to the outbox within the same transaction as the
 * aggregate write. The service NEVER publishes directly — publication/dispatch
 * is an infrastructure concern behind this port (real adapter deferred).
 *
 * Event flow: aggregate records → service pullEvents() → EventOutbox.enqueue()
 * → (same txn) commit.
 */
interface EventOutbox
{
    /**
     * Provenance is supplied EXPLICITLY at publish time (ADR-MP-06 invariant): a chain-
     * starting producer passes EventProvenance::start(); a reacting producer propagates
     * the consumed message's correlation + records it as the cause.
     */
    public function enqueue(EventProvenance $provenance, DomainEvent ...$events): void;
}
