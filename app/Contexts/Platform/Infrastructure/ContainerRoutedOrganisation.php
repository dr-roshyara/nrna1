<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Infrastructure\Published\NotTheRoutedOrganisation;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Connection;

/**
 * Platform's ONE adapter behind its published routing contract (PBDIGIT-345 task 10a; D-233). It reads the operation's
 * OrganisationContext, bound only by the E-1 middleware (per request) and the E-2 job middleware (per attempt), and
 * asks ResolutionService on every isRegistryRouted call. Consistency (proposal §8.2): isolation never depends on timing
 * (immutable locator, identity row, the current organisation's connection only); eligibility is checked per call.
 */
final class ContainerRoutedOrganisation implements RoutedOrganisation, RoutedOrganisationConnection
{
    public function __construct(
        private readonly Container $container,
        private readonly ResolutionService $resolution,
    ) {
    }

    public function current(): ?TenantId
    {
        return $this->context()?->organisationId;
    }

    public function isRegistryRouted(TenantId $organisation): bool
    {
        if ($this->current()?->value() !== $organisation->value()) {
            return false;
        }
        try {
            $this->resolution->resolve($organisation->value());
        } catch (OrganisationNotRoutable) {
            return false;   // the registry answered: not routable now. Any other failure propagates (fail closed).
        }

        return true;
    }

    public function connectionFor(TenantId $organisation): Connection
    {
        $context = $this->context();
        if ($context === null || $context->organisationId->value() !== $organisation->value()) {
            throw new NotTheRoutedOrganisation('A connection is given only for the organisation this operation is routed to.');
        }

        return $context->connection;
    }

    private function context(): ?OrganisationContext
    {
        return $this->container->bound(OrganisationContext::class) ? $this->container->make(OrganisationContext::class) : null;
    }
}
