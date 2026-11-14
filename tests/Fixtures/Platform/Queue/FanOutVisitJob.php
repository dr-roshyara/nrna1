<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;

/**
 * TEST ONLY (PBDIGIT-345 task 8, T-5): the share of a fanned-out operation for one organisation. It records the visit
 * in ITS organisation's database (`t8_visits`); an organisation without that table makes the job fail there only.
 */
final class FanOutVisitJob extends OrganisationJob
{
    public int $tries = 1;

    public function handle(): void
    {
        $this->organisation()->connection->insert('INSERT INTO t8_visits (identity) SELECT id::text FROM organisation_identity');
    }
}
