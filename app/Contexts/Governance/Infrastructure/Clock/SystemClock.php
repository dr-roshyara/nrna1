<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Clock;

use App\Contexts\Governance\Application\Ports\GovernanceClock;
use DateTimeImmutable;

final class SystemClock implements GovernanceClock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
