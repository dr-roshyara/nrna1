<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * TEST ONLY (PBDIGIT-345 task 7, T-19): shaped like the 25 legacy ShouldQueue classes: no connection of its own,
 * so it follows the application's default queue connection.
 */
final class LegacyStyleJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    /** @var list<string> */
    public static array $ran = [];

    public function __construct(public readonly string $label)
    {
    }

    public function handle(): void
    {
        self::$ran[] = $this->label;
    }
}
