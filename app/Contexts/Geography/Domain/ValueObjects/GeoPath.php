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

    /**
     * Create from a dot-separated path string (e.g. "1.5.23")
     */
    public static function fromString(string $path): self
    {
        if ($path === '') {
            return self::empty();
        }
        return self::fromArray(explode('.', $path));
    }

    /**
     * Create from an array of positive integer IDs
     */
    public static function fromIds(array $ids): self
    {
        return self::fromArray($ids);
    }

    public function depth(): int
    {
        return count($this->units);
    }

    /**
     * Alias for depth(), used by entity
     */
    public function getDepth(): int
    {
        return $this->depth();
    }

    /**
     * Get the ordered list of unit IDs in this path
     */
    public function getLevelIds(): array
    {
        return $this->units;
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

    /**
     * Check if this path is a descendant of the given ancestor path.
     * This path is a descendant if the ancestor path is a strict prefix.
     */
    public function isDescendantOf(self $ancestorPath): bool
    {
        if (count($ancestorPath->units) >= count($this->units)) {
            return false;
        }

        foreach ($ancestorPath->units as $i => $unitId) {
            if (!isset($this->units[$i]) || $this->units[$i] !== $unitId) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
