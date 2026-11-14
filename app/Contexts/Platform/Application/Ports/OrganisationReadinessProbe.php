<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Founding step 5 (task 5c): read the L-1 facts in the organisation's own database, at the locator the registry holds.
 * Reads only; never migrates. Unreachable database: throws (the transition is then not made).
 */
interface OrganisationReadinessProbe
{
    public function inspect(TenantId $id): OrganisationDatabaseFacts;
}
