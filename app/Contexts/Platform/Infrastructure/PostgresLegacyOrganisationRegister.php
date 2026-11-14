<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/** L-2 (tactical design §3.7): an id present in the legacy `organisations` table (central database) is legacy. Read only. */
final class PostgresLegacyOrganisationRegister implements LegacyOrganisationRegister
{
    public function __construct(private readonly Connection $central)
    {
    }

    public function contains(TenantId $id): bool
    {
        return $this->central->selectOne('SELECT 1 AS x FROM organisations WHERE id = ?::uuid', [$id->value()]) !== null;
    }
}
