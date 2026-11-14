<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Founding step 3 (tactical design §3.6): prepare the organisation's OWN database (the organisation migration set,
 * then its `organisation_identity` row) at the locator the registry holds for it. The production adapter does this in
 * a DEDICATED PROCESS, one organisation per process (task 4 limit: the migrator switches the default connection while
 * it runs). Idempotent: preparing a prepared database changes nothing.
 */
interface OrganisationProvisioner
{
    /** @throws ProvisioningFailed */
    public function prepare(TenantId $id): void;
}
