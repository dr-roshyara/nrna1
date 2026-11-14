<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/** TEST ONLY (PBDIGIT-345 task 7, T-19): a legacy-shaped job that fails. */
final class LegacyStyleFailingJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function handle(): void
    {
        throw new \RuntimeException('legacy-style job failed');
    }
}
