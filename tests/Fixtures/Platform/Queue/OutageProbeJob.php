<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TEST ONLY (PBDIGIT-345 task 12, T-11, T-18): an organisation job that records, in ITS organisation's database, the
 * identity it saw and its PostgreSQL backend. Retried (an outage is a retry, never a run elsewhere).
 */
final class OutageProbeJob extends OrganisationJob
{
    public int $tries = 5;
    public int $backoff = 0;

    public function __construct(TenantId $organisation, public readonly string $label)
    {
        parent::__construct($organisation);
    }

    public function handle(): void
    {
        $this->organisation()->connection->insert('INSERT INTO t12_visits (label, identity, backend_pid) VALUES (?, (SELECT id::text FROM organisation_identity), pg_backend_pid())', [$this->label]);
    }
}
