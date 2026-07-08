<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

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
    public function enqueue(object ...$events): void;
}
