<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * L-2 (tactical design §3.7): whether an id belongs to a LEGACY organisation (present in the legacy `organisations`
 * table). Legacy organisations cannot enter the registry in release 1; their cutover is a later, separate plan.
 */
interface LegacyOrganisationRegister
{
    public function contains(TenantId $id): bool;
}
