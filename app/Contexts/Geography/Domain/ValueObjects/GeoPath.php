<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

final class GeoPath
{
    private array $units;

    public function __construct(array $units)
    {
        foreach ($units as $u) {
            if (!is_int($u) || $u <= 0) {
                throw new \InvalidArgumentException('GeoPath units must be positive integers');
            }
        }
        $this->units = array_values($units);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromArray(array $raw): self
    {
        $filtered = array_values(
            array_filter(
                array_map('intval', $raw),
                fn($u) => $u > 0
            )
        );
        return new self($filtered);
    }

    public function depth(): int
    {
        return count($this->units);
    }

    public function toArray(): array
    {
        return $this->units;
    }

    public function toString(): string
    {
        return implode('.', $this->units);
    }

    public function isEmpty(): bool
    {
        return empty($this->units);
    }
}
