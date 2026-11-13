<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * A routable organisation and where its database lives, as the registry says at this moment (task 2). Made only by
 * ResolutionService; task 3 turns it into the immutable OrganisationContext and its named connection (D-222).
 */
final readonly class ResolvedOrganisation
{
    public function __construct(public TenantId $organisationId, public DatabaseLocator $locator)
    {
    }
}
