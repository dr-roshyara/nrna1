<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * GeographicCode Value Object
 *
 * Represents official geographic code (e.g., "NP-P1", "NP-DIST-01").
 * Immutable with validation for non-empty string.
 */
readonly class GeographicCode
{
    private string $value;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Create from string
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * Validate code
     */
    private function validate(string $value): void
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('GeographicCode cannot be empty');
        }

        // Basic validation - can be extended for country-specific formats
        if (strlen($value) > 100) {
            throw new InvalidArgumentException('GeographicCode cannot exceed 100 characters');
        }

        // Optional: Validate format based on common patterns
        // e.g., NP-P1, NP-DIST-01, IN-UP, US-CA
        // This could be enhanced with country-specific validation
    }

    /**
     * Get string value
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Check if code matches pattern
     * Useful for country-specific validation
     */
    public function matchesPattern(string $pattern): bool
    {
        return preg_match($pattern, $this->value) === 1;
    }

    /**
     * Check if code starts with prefix
     */
    public function startsWith(string $prefix): bool
    {
        return str_starts_with($this->value, $prefix);
    }

    /**
     * Extract country code from geographic code
     * Assumes format like "NP-P1" where "NP" is country code
     */
    public function extractCountryCode(): ?string
    {
        // Try to extract first 2 letters before dash
        if (preg_match('/^([A-Z]{2})[-_]/', $this->value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check equality with another GeographicCode
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Case-insensitive equality check
     */
    public function equalsIgnoreCase(self $other): bool
    {
        return strcasecmp($this->value, $other->value) === 0;
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}