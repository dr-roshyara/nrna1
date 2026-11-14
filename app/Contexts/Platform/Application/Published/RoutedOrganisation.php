<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Published;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PUBLISHED by Platform (D-233 D-1, open host; PBDIGIT-345 task 10): the organisation the current operation (an E-1
 * request or an E-2 job attempt) was routed to. For every organisation-owned context's Infrastructure adapters.
 * Laravel-free. Grants routing facts only: never membership, access or authority.
 */
interface RoutedOrganisation
{
    /** The current routed organisation, or null outside one. Never taken from caller input. */
    public function current(): ?TenantId;

    /**
     * True only if $organisation IS the current routed organisation AND the registry routes it NOW (operational, schema
     * current): re-asked on every call (D-233 D-2). A registry that cannot answer throws: never true, never false.
     */
    public function isRegistryRouted(TenantId $organisation): bool;
}
