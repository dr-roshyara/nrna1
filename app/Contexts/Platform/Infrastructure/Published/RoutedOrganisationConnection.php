<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Published;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/**
 * PUBLISHED by Platform at INFRASTRUCTURE level (D-233 D-1: a narrowly scoped, allowlisted integration contract): the
 * current routed organisation's own database connection, for other contexts' INFRASTRUCTURE adapters only (never their
 * Domain or Application; architecture-tested). It checks identity, not the registry (one registry read per call is
 * RoutedOrganisation::isRegistryRouted's job; proposal §8.2).
 */
interface RoutedOrganisationConnection
{
    /** @throws NotTheRoutedOrganisation if $organisation is not the current routed organisation, or there is none */
    public function connectionFor(TenantId $organisation): Connection;
}
