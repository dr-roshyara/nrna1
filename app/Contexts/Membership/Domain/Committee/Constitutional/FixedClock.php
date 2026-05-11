<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final class FixedClock implements GovernanceClock
{
    public function __construct(private readonly \DateTimeImmutable $fixedAt) {}

    public function now(): \DateTimeImmutable
    {
        return $this->fixedAt;
    }
}
