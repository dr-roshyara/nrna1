<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * GeographyHierarchy Value Object
 *
 * Represents a geographic hierarchy with up to 11 levels (0-10).
 * Validates level sequence according to country-specific rules.
 *
 * Levels mapping:
 * 0: Continent
 * 1: Country
 * 2: Province/State
 * 3: District/County
 * 4: Local Level/City
 * 5: Ward/ZIP Code
 * 6-10: Custom party units (Neighborhood, Street, House, Block/Unit, Reserved)
 */
readonly class GeographyHierarchy
{
    private CountryCode $countryCode;
    private array $levelIds;

    /**
     * @param CountryCode $countryCode ISO country code
     * @param array<int|null> $levelIds Array of 11 level IDs [level0, level1, ..., level10]
     */
    public function __construct(
        CountryCode $countryCode,
        array $levelIds
    ) {
        // Ensure exactly 11 elements (levels 0-10)
        $paddedLevelIds = array_pad($levelIds, 11, null);

        $this->countryCode = $countryCode;
        $this->levelIds = $paddedLevelIds;

        $this->validate();
    }

    /**
     * Create from array of level IDs
     *
     * @param array<int|null> $levelIds Array of level IDs (0-10)
     */
    public static function fromLevelIds(CountryCode $countryCode, array $levelIds): self
    {
        // Pad to exactly 11 elements (levels 0-10) for readonly property
        $paddedLevelIds = array_pad($levelIds, 11, null);
        return new self($countryCode, $paddedLevelIds);
    }

    /**
     * Create empty hierarchy for country
     */
    public static function empty(CountryCode $countryCode): self
    {
        return new self($countryCode, array_fill(0, 11, null));
    }

    /**
     * Validate hierarchy according to country rules
     */
    private function validate(): void
    {
        // 1. Validate level IDs are integers or null
        foreach ($this->levelIds as $index => $id) {
            if ($id !== null && !is_int($id)) {
                throw new InvalidArgumentException(
                    sprintf('Level %d ID must be integer or null, got %s', $index, gettype($id))
                );
            }

            if ($id !== null && $id <= 0) {
                throw new InvalidArgumentException(
                    sprintf('Level %d ID must be positive integer, got %d', $index, $id)
                );
            }
        }

        // 2. Validate no gaps in hierarchy (if level N is set, level N-1 must be set)
        $lastNonNullIndex = -1;
        foreach ($this->levelIds as $index => $id) {
            if ($id !== null) {
                // Check if there's a gap (allow level 0 to be null - continent optional)
                for ($i = $lastNonNullIndex + 1; $i < $index; $i++) {
                    // Skip gap check for level 0 (continent can be null even if level 1+ exists)
                    if ($i === 0) {
                        continue;
                    }
                    if ($this->levelIds[$i] === null) {
                        throw new InvalidArgumentException(
                            sprintf('Missing level %d in hierarchy. Cannot have level %d without level %d.',
                                $i,
                                $index,
                                $i
                            )
                        );
                    }
                }
                $lastNonNullIndex = $index;
            }
        }

        // 3. Validate required levels for country
        $requiredLevels = $this->countryCode->getRequiredLevels();
        foreach ($requiredLevels as $level => $config) {
            $levelIndex = $level; // Array index equals level number (level 0 at index 0)
            if ($this->levelIds[$levelIndex] === null) {
                throw new InvalidArgumentException(
                    sprintf('Level %d (%s) is required for country %s',
                        $level,
                        $config['name'],
                        $this->countryCode->toString()
                    )
                );
            }
        }
    }

    /**
     * Get country code
     */
    public function getCountryCode(): CountryCode
    {
        return $this->countryCode;
    }

    /**
     * Get level IDs array (1-8)
     *
     * @return array<int|null>
     */
    public function getLevelIds(): array
    {
        return $this->levelIds;
    }

    /**
     * Get level ID at specific level (0-based)
     */
    public function getLevelId(int $level): ?int
    {
        if ($level < 0 || $level > 10) {
            throw new InvalidArgumentException('Level must be between 0 and 10');
        }

        return $this->levelIds[$level];
    }

    /**
     * Check if hierarchy is complete (all 11 levels filled)
     */
    public function isComplete(): bool
    {
        return !in_array(null, $this->levelIds, true);
    }

    /**
     * Check if hierarchy has any levels filled
     */
    public function isEmpty(): bool
    {
        return !array_filter($this->levelIds, fn($id) => $id !== null);
    }

    /**
     * Get missing levels (0-10 that are null)
     *
     * @return array<int> Level numbers (0-based) that are missing
     */
    public function getMissingLevels(): array
    {
        $missing = [];
        foreach ($this->levelIds as $index => $id) {
            if ($id === null) {
                $missing[] = $index;
            }
        }
        return $missing;
    }

    /**
     * Get filled levels (0-10 that have values)
     *
     * @return array<int> Level numbers (0-based) that are filled
     */
    public function getFilledLevels(): array
    {
        $filled = [];
        foreach ($this->levelIds as $index => $id) {
            if ($id !== null) {
                $filled[] = $index;
            }
        }
        return $filled;
    }

    /**
     * Get depth (number of filled levels from bottom)
     */
    public function getDepth(): int
    {
        // Count from the end until we find a non-null value
        for ($i = 10; $i >= 0; $i--) {
            if ($this->levelIds[$i] !== null) {
                return $i + 1;
            }
        }
        return 0;
    }

    /**
     * Get GeoPath representation
     */
    public function toGeoPath(): GeoPath
    {
        $filledIds = array_filter($this->levelIds, fn($id) => $id !== null);

        if (empty($filledIds)) {
            throw new InvalidArgumentException('Cannot create GeoPath from empty hierarchy');
        }

        return GeoPath::fromIds($filledIds);
    }

    /**
     * Check if this hierarchy is compatible with another (same filled levels)
     */
    public function isCompatibleWith(self $other): bool
    {
        // Must be same country
        if (!$this->countryCode->equals($other->countryCode)) {
            return false;
        }

        // Check all filled levels match
        for ($i = 0; $i < 11; $i++) {
            $id1 = $this->levelIds[$i];
            $id2 = $other->levelIds[$i];

            // If both have values, they must match
            if ($id1 !== null && $id2 !== null && $id1 !== $id2) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create new hierarchy with updated level
     */
    public function withLevel(int $level, ?int $id): self
    {
        if ($level < 0 || $level > 10) {
            throw new InvalidArgumentException('Level must be between 0 and 10');
        }

        $newLevelIds = $this->levelIds;
        $newLevelIds[$level] = $id;

        return new self($this->countryCode, $newLevelIds);
    }

    /**
     * Get array representation for persistence
     */
    public function toArray(): array
    {
        return [
            'country_code' => $this->countryCode->toString(),
            'level_ids' => $this->levelIds,
        ];
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        $filled = array_filter($this->levelIds);
        if (empty($filled)) {
            return 'Empty hierarchy';
        }

        return sprintf('%s: %s',
            $this->countryCode->toString(),
            implode('.', $filled)
        );
    }
}