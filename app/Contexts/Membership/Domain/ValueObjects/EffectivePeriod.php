<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

final readonly class EffectivePeriod
{
    public function __construct(
        private DateTimeImmutable $start,
        private ?DateTimeImmutable $end,
    ) {
        if ($end !== null && $end <= $start) {
            throw new InvalidArgumentException(
                'Period end must be after start'
            );
        }
    }

    public static function openEnded(DateTimeImmutable $start): self
    {
        return new self($start, null);
    }

    public static function bounded(
        DateTimeImmutable $start,
        DateTimeImmutable $end,
    ): self {
        return new self($start, $end);
    }

    public function isActive(DateTimeImmutable $now): bool
    {
        if ($now < $this->start) {
            return false;
        }

        if ($this->end === null) {
            return true;
        }

        return $now <= $this->end;
    }

    public function start(): DateTimeImmutable
    {
        return $this->start;
    }

    public function end(): ?DateTimeImmutable
    {
        return $this->end;
    }
}
