<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\ConnectionInterface;

/** PBDIGIT-345 task 8: every registry id from the CENTRAL registry, ordered by id. Reads ids only. */
final class PostgresRegisteredOrganisations implements RegisteredOrganisations
{
    public function __construct(private readonly ConnectionInterface $central)
    {
    }

    public function ids(): array
    {
        return array_map(static fn ($row): TenantId => TenantId::fromString($row->id),
            $this->central->select('SELECT id::text AS id FROM platform_served_organisations ORDER BY id'));
    }
}
