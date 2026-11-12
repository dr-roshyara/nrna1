<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/** Internal to LedgerClockCloser: a due scheduled resignation could not be executed, so the close rolls back. */
final class ScheduledExecutionFailed extends \RuntimeException
{
    public function __construct(\Throwable $cause)
    {
        parent::__construct('A due scheduled resignation could not be executed: ' . $cause->getMessage(), 0, $cause);
    }
}
