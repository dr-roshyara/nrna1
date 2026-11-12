<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** The trusted working organisation of the current request (INV-ORG-SCOPE-1); null when none is established. */
interface WorkingOrganisationPort
{
    public function current(): ?TenantId;
}
