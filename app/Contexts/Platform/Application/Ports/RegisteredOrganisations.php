<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 8 (E-3): every organisation in the registry, in a stable order (a read model; whether one may be
 * reached is decided by ResolutionService, never here). Unreadable registry: throws (the fan-out fails closed).
 */
interface RegisteredOrganisations
{
    /** @return list<TenantId> */
    public function ids(): array;
}
