<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

use App\Contexts\Shared\Infrastructure\Outbox\ConnectionOutboxProcessor;

/**
 * The per-organisation relay E-5 (PBDIGIT-345 task 9b; tactical design §3.4): one organisation job that relays ITS
 * organisation's outbox, on its organisation's connection, inside its context (EstablishOrganisationContext), so
 * consumers run in the same organisation context. Fanned out to every operational organisation as the operation
 * `outbox.relay` (`php artisan platform:fan-out outbox.relay`). Idempotent: completed rows are never claimed again.
 */
final class RelayOrganisationOutbox extends OrganisationJob
{
    public int $tries = 1;

    public function handle(ConnectionOutboxProcessor $processor): void
    {
        $context = $this->organisation();
        $processor->process($context->connection, $context->organisationId->value());
    }
}
