<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/**
 * PBDIGIT-345 task 8: what one fan-out run did. Organisation ids with a reason or an error CLASS only: no message, no
 * locator, no business content (ISO-10).
 */
final readonly class FanOutReport
{
    /**
     * @param list<string>          $dispatched organisation ids that got their job
     * @param array<string, string> $skipped    organisation id => routing refusal (not-operational, schema-not-current, unknown)
     * @param array<string, string> $failed     organisation id => error class of the failed dispatch
     */
    public function __construct(public array $dispatched, public array $skipped, public array $failed)
    {
    }

    public function hasFailures(): bool
    {
        return $this->failed !== [];
    }
}
