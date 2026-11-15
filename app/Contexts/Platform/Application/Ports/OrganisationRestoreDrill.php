<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 12b (T-17; D-234 Option A): rehearse a restore. Back up the organisation's database, restore the
 * backup into an isolated SCRATCH database that no registry entry points to (never routable), read the L-1 facts on
 * that copy, then remove the copy and the backup. Nothing of the content is disclosed: only the technical facts.
 */
interface OrganisationRestoreDrill
{
    /** @throws ProvisioningFailed the backup or the restore itself failed */
    public function rehearse(TenantId $id): OrganisationDatabaseFacts;
}
