<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

interface GovernanceClock
{
    public function now(): \DateTimeImmutable;
}
