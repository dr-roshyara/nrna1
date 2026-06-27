<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * GeographyLevel Value Object
 *
 * Represents administrative level in geographic hierarchy (0-10).
 * Immutable with validation. Neutral - root/leaf semantics are tenant-specific.
 *
 * Levels:
 * 0: Continent (Asia, Europe, etc.)
 * 1: Country (Nepal, Germany, etc.)
 * 2: Province/State
 * 3: District/County
 * 4: Local Level/City
 * 5: Ward/ZIP Code
 * 6: Neighborhood/Tole
 * 7: Street/Block
 * 8: House Number
 * 9: Block/Unit (Apartment blocks, office units)
 * 10: Reserved for future (Apartment/Unit/Room)
 */
readonly class GeographyLevel
{
    private int $value;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(int $value)
    {
        if ($value < 0 || $value > 10) {
            throw new InvalidArgumentException("GeographyLevel must be between 0 and 10, got {$value}");
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
     * Create root level (level 1)
     * @deprecated Root semantics are tenant-specific. Use TenantGeographyProfile instead.
     */
    public static function root(): self
    {
        trigger_error('GeographyLevel::root() is deprecated. Root semantics are tenant-specific. Use TenantGeographyProfile instead.', E_USER_DEPRECATED);
        return new self(1);
    }

    /**
     * Get integer value
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * Check if level is root (level 1)
     * @deprecated Root semantics are tenant-specific. Use TenantGeographyProfile::isRootLevel() instead.
     */
    public function isRoot(): bool
    {
        trigger_error('GeographyLevel::isRoot() is deprecated. Root semantics are tenant-specific. Use TenantGeographyProfile::isRootLevel() instead.', E_USER_DEPRECATED);
        return $this->value === 1;
    }

    /**
     * Check if level is leaf (level 10 or highest for country)
     * @deprecated Leaf semantics are tenant-specific. Use TenantGeographyProfile::isLeafLevel() instead.
     */
    public function isLeaf(): bool
    {
        trigger_error('GeographyLevel::isLeaf() is deprecated. Leaf semantics are tenant-specific. Use TenantGeographyProfile::isLeafLevel() instead.', E_USER_DEPRECATED);
        return $this->value === 10;
    }

    /**
     * Get next level (level + 1)
     * Returns null if already at max level (10)
     */
    public function next(): ?self
    {
        if ($this->value >= 10) {
            return null;
        }

        return new self($this->value + 1);
    }

    /**
     * Get previous level (level - 1)
     * Returns null if already at minimum level (0)
     */
    public function previous(): ?self
    {
        if ($this->value <= 0) {
            return null;
        }

        return new self($this->value - 1);
    }

    /**
     * Check if this level is higher than another level
     */
    public function isHigherThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    /**
     * Check if this level is lower than another level
     */
    public function isLowerThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    /**
     * Get level name based on standard mapping
     * This should be overridden by country-specific configuration
     */
    public function getName(): string
    {
        $names = [
            0 => 'Continent',
            1 => 'Country',
            2 => 'Province/State',
            3 => 'District/County',
            4 => 'Local Level/City',
            5 => 'Ward/ZIP Code',
            6 => 'Neighborhood/Tole',
            7 => 'Street/Block',
            8 => 'House Number',
            9 => 'Block/Unit',
            10 => 'Reserved',
        ];

        return $names[$this->value] ?? "Level {$this->value}";
    }

    /**
     * Check equality with another GeographyLevel
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
        return (string) $this->value;
    }
}