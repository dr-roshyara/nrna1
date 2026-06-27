<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Models;

use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use DomainException;

/**
 * TenantGeographyProfile Aggregate
 *
 * Represents tenant-specific geography configuration.
 * Defines which geography levels are root and leaf for a specific tenant type.
 * Replaces deprecated GeographyLevel::isRoot() and GeographyLevel::isLeaf() methods.
 *
 * Tenant types:
 * - Diaspora: Root at continent (Level 0), Leaf at city (Level 4)
 * - Nepal Political Party: Root at country (Level 1), Leaf at ward (Level 5)
 * - German NGO: Root at country (Level 1), Leaf at block/unit (Level 9)
 *
 * Immutable aggregate - configuration changes require new profile.
 */
class TenantGeographyProfile
{
    private function __construct(
        private GeographyLevel $rootLevel,
        private GeographyLevel $leafLevel,
        private string $tenantType
    ) {
        $this->validate();
    }

    /**
     * Validate profile invariants
     */
    private function validate(): void
    {
        // Root level must be lower (numerically smaller) than leaf level
        if (!$this->rootLevel->isLowerThan($this->leafLevel)) {
            throw new DomainException(
                "Root level {$this->rootLevel->toInt()} must be lower than leaf level {$this->leafLevel->toInt()}"
            );
        }

        // Both levels must be within valid range (0-10)
        $rootValue = $this->rootLevel->toInt();
        $leafValue = $this->leafLevel->toInt();

        if ($rootValue < 0 || $rootValue > 10) {
            throw new DomainException("Root level must be between 0 and 10, got {$rootValue}");
        }

        if ($leafValue < 0 || $leafValue > 10) {
            throw new DomainException("Leaf level must be between 0 and 10, got {$leafValue}");
        }
    }

    /**
     * Create profile for diaspora tenant
     * Root: Continent (Level 0), Leaf: City (Level 4)
     */
    public static function forDiaspora(): self
    {
        return new self(
            GeographyLevel::fromInt(0), // Continent
            GeographyLevel::fromInt(4), // City
            'diaspora'
        );
    }

    /**
     * Create profile for Nepal political party
     * Root: Country (Level 1), Leaf: Ward (Level 5)
     */
    public static function forNepalParty(): self
    {
        return new self(
            GeographyLevel::fromInt(1), // Country
            GeographyLevel::fromInt(5), // Ward
            'nepal_party'
        );
    }

    /**
     * Create profile for German NGO
     * Root: Country (Level 1), Leaf: Block/Unit (Level 9)
     */
    public static function forGermanyNGO(): self
    {
        return new self(
            GeographyLevel::fromInt(1), // Country
            GeographyLevel::fromInt(9), // Block/Unit
            'germany_ngo'
        );
    }

    /**
     * Create custom profile with specific root and leaf levels
     */
    public static function createCustom(
        GeographyLevel $rootLevel,
        GeographyLevel $leafLevel,
        string $tenantType
    ): self {
        return new self($rootLevel, $leafLevel, $tenantType);
    }

    /**
     * Check if a geography level is root for this tenant
     */
    public function isRootLevel(GeographyLevel $level): bool
    {
        return $level->equals($this->rootLevel);
    }

    /**
     * Check if a geography level is leaf for this tenant
     */
    public function isLeafLevel(GeographyLevel $level): bool
    {
        return $level->equals($this->leafLevel);
    }

    /**
     * Get root level
     */
    public function getRootLevel(): GeographyLevel
    {
        return $this->rootLevel;
    }

    /**
     * Get leaf level
     */
    public function getLeafLevel(): GeographyLevel
    {
        return $this->leafLevel;
    }

    /**
     * Get tenant type
     */
    public function getTenantType(): string
    {
        return $this->tenantType;
    }

    /**
     * Get all enabled levels for this tenant (inclusive range)
     * Returns array of level numbers from root to leaf
     */
    public function getEnabledLevels(): array
    {
        $root = $this->rootLevel->toInt();
        $leaf = $this->leafLevel->toInt();

        return range($root, $leaf);
    }

    /**
     * Check if a level is within enabled range for this tenant
     */
    public function isLevelEnabled(GeographyLevel $level): bool
    {
        $value = $level->toInt();
        $root = $this->rootLevel->toInt();
        $leaf = $this->leafLevel->toInt();

        return $value >= $root && $value <= $leaf;
    }

    /**
     * Get the depth of geography hierarchy for this tenant
     */
    public function getHierarchyDepth(): int
    {
        return $this->leafLevel->toInt() - $this->rootLevel->toInt();
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        return sprintf(
            'TenantGeographyProfile[type=%s, root=%d, leaf=%d]',
            $this->tenantType,
            $this->rootLevel->toInt(),
            $this->leafLevel->toInt()
        );
    }
}