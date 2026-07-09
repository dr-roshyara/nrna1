<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

use App\Contexts\Shared\Application\Messaging\EventProvenance;

/**
 * Application port through which the Election reaction publishes the domain events it
 * decided (e.g. {@see \App\Contexts\Election\Domain\Events\ElectionCorrectionApplied}).
 *
 * The port is transport-agnostic: the concrete infrastructure adapter records the
 * events on the transactional outbox in the same transaction as the aggregate write
 * (ADR-T1). The application layer depends only on this interface, never on Eloquent.
 */
interface ReactionEventOutbox
{
    /**
     * Provenance is supplied EXPLICITLY at publish time (ADR-MP-06 invariant): the
     * reaction propagates the consumed message's correlation and records it as the cause.
     */
    public function enqueue(EventProvenance $provenance, object ...$events): void;
}
