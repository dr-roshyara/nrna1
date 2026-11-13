<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/**
 * The one routing object (delta §3.1; D-193; ISO-1): ONE organisation and ITS OWN database connection, for one
 * operation. Immutable. Made only by OrganisationConnectionFactory from a ResolvedOrganisation (ISO-2), so it never
 * exists for an organisation that is not routable. Organisation-side code receives this connection explicitly and
 * never reads Laravel's default (central) connection (C-1).
 */
final readonly class OrganisationContext
{
    public function __construct(public TenantId $organisationId, public Connection $connection)
    {
    }
}
