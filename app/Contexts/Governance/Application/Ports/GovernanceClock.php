<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

use DateTimeImmutable;

interface GovernanceClock
{
    public function now(): DateTimeImmutable;
}
