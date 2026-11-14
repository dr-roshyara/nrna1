<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;

/**
 * TEST ONLY (PBDIGIT-345 task 7): an organisation job that records, in ITS organisation's database, what it saw:
 * the organisation identity, its PostgreSQL backend (a new connection per job shows the purge), and which
 * organisation connections were open in the worker when it ran.
 */
final class RecordVisitJob extends OrganisationJob
{
    public function __construct(TenantId $organisation, public readonly string $label)
    {
        parent::__construct($organisation);
    }

    public function handle(): void
    {
        $db = $this->organisation()->connection;
        $open = array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_')));
        $db->insert('INSERT INTO t7_job_visits (label, identity, backend_pid, open_org) VALUES (?, (SELECT id::text FROM organisation_identity), pg_backend_pid(), ?)',
            [$this->label, json_encode($open, JSON_THROW_ON_ERROR)]);
    }
}
