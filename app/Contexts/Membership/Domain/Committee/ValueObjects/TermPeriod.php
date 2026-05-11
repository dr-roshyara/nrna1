<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use DateTimeImmutable;
use DomainException;

final readonly class TermPeriod
{
    public function __construct(
        private DateTimeImmutable $start,
        private DateTimeImmutable $end,
    ) {
        if ($end <= $start) {
            throw new DomainException(
                'Term end must be after start'
            );
        }
    }

    public static function from(
        DateTimeImmutable $start,
        DateTimeImmutable $end,
    ): self {
        return new self($start, $end);
    }

    public function isActive(
        DateTimeImmutable $now,
    ): bool {
        return $now >= $this->start
            && $now <= $this->end;
    }

    public function start(): DateTimeImmutable
    {
        return $this->start;
    }

    public function end(): DateTimeImmutable
    {
        return $this->end;
    }

    public function equals(TermPeriod $other): bool
    {
        return $this->start == $other->start
            && $this->end == $other->end;
    }
}
