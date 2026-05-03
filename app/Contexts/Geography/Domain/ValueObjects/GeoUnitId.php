<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * GeoUnitId Value Object
 *
 * Represents the ID of a geographic administrative unit.
 * Immutable with validation.
 */
readonly class GeoUnitId
{
    private int $value;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("GeoUnitId must be positive integer, got {$value}");
        }

        $this->value = $value;
    }

    /**
     * Create from integer
     */
    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    /**
     * Create from string
     */
    public static function fromString(string $value): self
    {
        if (!is_numeric($value) || (int) $value <= 0) {
            throw new InvalidArgumentException("GeoUnitId must be positive integer string, got '{$value}'");
        }

        return new self((int) $value);
    }

    /**
     * Get integer value
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * Get string representation
     */
    public function toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Check equality with another GeoUnitId
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}