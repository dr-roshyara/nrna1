<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;

/** TEST ONLY (PBDIGIT-345 task 7, T-23): an organisation job that always fails; two attempts, a long backoff. */
final class FailingOrganisationJob extends OrganisationJob
{
    public int $tries = 2;
    public int $backoff = 3600;

    public function handle(): void
    {
        throw new \RuntimeException('organisation job failed');
    }
}
