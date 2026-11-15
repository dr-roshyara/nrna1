<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\Ports\RequestTime;

/**
 * PBDIGIT-349 I-2: the request's start (PHP's REQUEST_TIME_FLOAT for this request or command) and now, in the
 * application's time zone. Used only for the freshness and business-date checks of authority evidence (§10.1).
 */
final class SystemRequestTime implements RequestTime
{
    public function requestStart(): \DateTimeImmutable
    {
        $start = (float) ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true));

        return (new \DateTimeImmutable('@' . sprintf('%.6F', $start)))->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }

    public function currentInstant(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
