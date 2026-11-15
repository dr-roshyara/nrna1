<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 11 (§3.8 migration repair): re-apply the organisation migration set to the organisation's own
 * database, in the DEDICATED PROCESS (task 5b), and check its identity binding. Technical only: never reads business
 * tables. Idempotent.
 */
interface OrganisationSchemaRepair
{
    /** @throws ProvisioningFailed */
    public function repair(TenantId $id): void;
}
