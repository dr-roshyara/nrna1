<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\ValueObjects;

use DateTimeImmutable;

final readonly class FeePeriod
{
    private int $year;
    private int $month;

    private function __construct(int $year, int $month)
    {
        if ($month < 1 || $month > 12) {
            throw new \InvalidArgumentException('Month must be between 1 and 12');
        }

        $this->year = $year;
        $this->month = $month;
    }

    public static function fromYearMonth(int $year, int $month): self
    {
        return new self($year, $month);
    }

    public static function current(): self
    {
        $now = new DateTimeImmutable();
        return new self((int) $now->format('Y'), (int) $now->format('m'));
    }

    public static function fromString(string $period): self
    {
        // Format: YYYY-MM
        if (!preg_match('/^\d{4}-\d{2}$/', $period)) {
            throw new \InvalidArgumentException("Invalid period format: {$period}. Expected YYYY-MM");
        }

        [$year, $month] = explode('-', $period);
        return new self((int) $year, (int) $month);
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }

    public function toString(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }

    public function equals(self $other): bool
    {
        return $this->year === $other->year && $this->month === $other->month;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
