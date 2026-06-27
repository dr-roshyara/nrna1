<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * Geography Reference Value Object (DEPRECATED)
 *
 * @deprecated Use App\Contexts\Geography\Domain\ValueObjects\GeoReference instead.
 * This class is maintained for backward compatibility. New code should use the
 * canonical GeoReference in the Geography domain and MembershipGeoReferenceAdapter
 * for translation between contexts.
 *
 * Represents a validated reference to a geographic unit in the Geography context.
 * This is a string path that identifies a specific location in the hierarchy.
 *
 * Business Rule: Geography is OPTIONAL for member registration
 * Design Decision: Store as string reference, not foreign key (decoupling)
 *
 * Format: Dot-separated path (country.level1.level2....)
 * Examples:
 * - "np.3.15.234.1.2" → Nepal → Province 3 → District 15 → Municipality 234 → Ward 1 → Tole 2
 * - "us.ca.la" → USA → California → Los Angeles
 * - "in.mh.mumbai" → India → Maharashtra → Mumbai
 *
 * Validation:
 * - Must match pattern: country.[level]+
 * - Max length: 500 characters
 * - Country code: 2 lowercase letters
 * - Levels: numeric IDs or slugs
 */
final readonly class GeoReference
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    /**
     * Create GeoReference from string
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        // Empty check
        if (empty(trim($value))) {
            throw new \InvalidArgumentException(
                'Geography reference cannot be empty'
            );
        }

        // Length check
        if (strlen($value) > 500) {
            throw new \InvalidArgumentException(
                'Geography reference cannot exceed 500 characters'
            );
        }

        // Format check: country.[level]+
        // Country code: 2 lowercase letters
        // Levels: numeric or alphanumeric slugs
        $pattern = '/^[a-z]{2}(\.[a-z0-9\-_]+)+$/';
        if (!preg_match($pattern, strtolower($value))) {
            throw new \InvalidArgumentException(
                "Invalid geography reference format: {$value}. " .
                "Expected format: country.level1.level2... (e.g., 'np.3.15.234')"
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * Get country code (first segment)
     */
    public function countryCode(): string
    {
        return explode('.', $this->value)[0];
    }

    /**
     * Get levels (all segments except country)
     */
    public function levels(): array
    {
        $parts = explode('.', $this->value);
        return array_slice($parts, 1);
    }

    /**
     * Get depth (number of levels excluding country)
     */
    public function depth(): int
    {
        return count($this->levels());
    }

    public function equals(GeoReference $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Get path prefix for LIKE queries
     * Used to find all children of this geography
     *
     * Example: "np.3.15" → "np.3.15." (matches np.3.15.234, np.3.15.235, etc.)
     */
    public function pathPrefix(): string
    {
        return $this->value . '.';
    }

    /**
     * Check if this geography is within or equal to another geography
     *
     * Used for geographic committee validation: member must live within
     * or at the same level as committee's operational geography.
     *
     * Examples:
     * - Committee: np.3 (Province 3)
     * - Member: np.3.15 (District 15 within Province 3) → true
     * - Member: np.4.15 (District 15 in Province 4) → false
     * - Member: np.3 (Same province) → true
     *
     * @param GeoReference $other The committee's operational geography
     * @return bool True if this geography is within or equal to the other
     */
    public function isWithinOrEqual(GeoReference $other): bool
    {
        // If exactly equal, return true
        if ($this->equals($other)) {
            return true;
        }

        // Check if $this is a descendant of $other
        // e.g., np.3.15 starts with np.3.
        $otherPrefix = $other->value . '.';
        return str_starts_with($this->value . '.', $otherPrefix);
    }

    /**
     * Returns unit IDs (int > 0) from the path.
     * Filters out zero values and non-numeric levels.
     * Domain logic: adapters must call this, not parse themselves.
     */
    public function getValidUnitIds(): array
    {
        return array_values(
            array_filter(array_map('intval', $this->levels()), fn(int $id) => $id > 0)
        );
    }

    /**
     * Returns true if all required levels in $structure are non-zero in this reference.
     * Used for validating geo_reference completeness against organisation's level config.
     */
    public function isCompleteForStructure(object $structure): bool
    {
        $levelValues = array_map('intval', $this->levels());
        foreach ($structure->getLevels() as $level) {
            // Check if the level value at the position is present and > 0
            if ($level->required && (!isset($levelValues[$level->index - 1]) || $levelValues[$level->index - 1] <= 0)) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
