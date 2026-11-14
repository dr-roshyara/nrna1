<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\DatabaseManager;

/**
 * The ONLY way to dispatch an organisation job (PBDIGIT-345 task 7; tactical design §3.10 Q-3, §3.10.2; T-20, T-25).
 * Refused (nothing queued):
 * - anything that is not an OrganisationJob;
 * - a connection override (the job must still be on `organisation`);
 * - a job for another organisation than the current OrganisationContext, if one is bound (a request or job may only
 *   queue work for its own organisation; central code, e.g. a fan-out, has no context);
 * - while that organisation's connection is inside a transaction (Q-7: use the organisation's outbox instead).
 */
final class OrganisationJobDispatcher
{
    public function __construct(
        private readonly Dispatcher $bus,
        private readonly Container $container,
        private readonly DatabaseManager $databases,
    ) {
    }

    public function dispatch(object $job): void
    {
        if (!$job instanceof OrganisationJob) {
            throw new OrganisationDispatchRefused('Only an OrganisationJob is dispatched here.');
        }
        if ($job->connection !== OrganisationJob::CONNECTION) {
            throw new OrganisationDispatchRefused('An organisation job stays on the organisation connection.');
        }
        $organisation = TenantId::fromString($job->organisationId);
        if ($this->container->bound(OrganisationContext::class)
            && !$this->container->make(OrganisationContext::class)->organisationId->equals($organisation)) {
            throw new OrganisationDispatchRefused('An organisation job is dispatched for the current organisation only.');
        }
        $name = OrganisationConnectionFactory::connectionName($organisation);
        if (array_key_exists($name, $this->databases->getConnections()) && $this->databases->connection($name)->transactionLevel() > 0) {
            throw new DispatchInsideOrganisationTransactionRefused('Not while the organisation connection is inside a transaction (use the organisation outbox).');
        }
        $this->bus->dispatch($job);
    }
}
