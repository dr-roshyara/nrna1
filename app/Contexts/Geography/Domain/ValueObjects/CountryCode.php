<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * CountryCode Value Object
 *
 * Represents ISO 3166-1 alpha-2 country code.
 * Immutable with validation.
 *
 * Examples: 'NP', 'IN', 'US'
 */
readonly class CountryCode
{
    private string $code;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(string $code)
    {
        if (!self::isValid($code)) {
            throw new InvalidArgumentException("Invalid ISO 3166-1 alpha-2 country code: '{$code}'");
        }

        $this->code = strtoupper($code);
    }

    /**
     * Create from string
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * Validate ISO 3166-1 alpha-2 format
     */
    public static function isValid(string $code): bool
    {
        // Must be exactly 2 uppercase letters
        return preg_match('/^[A-Z]{2}$/', $code) === 1;
    }

    /**
     * Get code as string
     */
    public function toString(): string
    {
        return $this->code;
    }

    /**
     * Get known country codes (subset for supported countries)
     */
    public static function getSupportedCountries(): array
    {
        return [
            'NP' => 'Nepal',
            'IN' => 'India',
            'US' => 'United States',
            'BD' => 'Bangladesh',
        ];
    }

    /**
     * Check if country is supported in the system
     */
    public function isSupported(): bool
    {
        return array_key_exists($this->code, self::getSupportedCountries());
    }

    /**
     * Get country name in English
     */
    public function getName(): string
    {
        return self::getSupportedCountries()[$this->code] ?? $this->code;
    }

    /**
     * Get hierarchy levels configuration for this country
     * This should eventually come from Country model/configuration
     */
    public function getHierarchyLevels(): array
    {
        // Geography hierarchy levels 0-10
        // Level 0: Continent (global, required false for all countries)
        // Level 1: Country (required true for all)
        // Levels 2-5: Official administrative levels (country-specific)
        // Levels 6-10: Custom party units (required false)
        $configurations = [
            'NP' => [
                0 => ['name' => 'Continent', 'required' => false],
                1 => ['name' => 'Country', 'required' => true],
                2 => ['name' => 'Province', 'required' => true],
                3 => ['name' => 'District', 'required' => true],
                4 => ['name' => 'Local Level', 'required' => false],
                5 => ['name' => 'Ward', 'required' => false],
                6 => ['name' => 'Neighborhood/Tole', 'required' => false],
                7 => ['name' => 'Street/Block', 'required' => false],
                8 => ['name' => 'House Number', 'required' => false],
                9 => ['name' => 'Block/Unit', 'required' => false],
                10 => ['name' => 'Reserved', 'required' => false],
            ],
            'IN' => [
                0 => ['name' => 'Continent', 'required' => false],
                1 => ['name' => 'Country', 'required' => true],
                2 => ['name' => 'State', 'required' => true],
                3 => ['name' => 'District', 'required' => true],
                4 => ['name' => 'Tehsil/Taluk', 'required' => false],
                5 => ['name' => 'Village/Town', 'required' => false],
                6 => ['name' => 'Custom Level 6', 'required' => false],
                7 => ['name' => 'Custom Level 7', 'required' => false],
                8 => ['name' => 'Custom Level 8', 'required' => false],
                9 => ['name' => 'Custom Level 9', 'required' => false],
                10 => ['name' => 'Custom Level 10', 'required' => false],
            ],
            'US' => [
                0 => ['name' => 'Continent', 'required' => false],
                1 => ['name' => 'Country', 'required' => true],
                2 => ['name' => 'State', 'required' => true],
                3 => ['name' => 'County', 'required' => true],
                4 => ['name' => 'City', 'required' => false],
                5 => ['name' => 'ZIP Code', 'required' => false],
                6 => ['name' => 'Custom Level 6', 'required' => false],
                7 => ['name' => 'Custom Level 7', 'required' => false],
                8 => ['name' => 'Custom Level 8', 'required' => false],
                9 => ['name' => 'Custom Level 9', 'required' => false],
                10 => ['name' => 'Custom Level 10', 'required' => false],
            ],
        ];

        return $configurations[$this->code] ?? [
            0 => ['name' => 'Continent', 'required' => false],
            1 => ['name' => 'Country', 'required' => true],
            2 => ['name' => 'Level 2', 'required' => true],
            3 => ['name' => 'Level 3', 'required' => true],
        ];
    }

    /**
     * Get required levels for this country
     */
    public function getRequiredLevels(): array
    {
        return array_filter(
            $this->getHierarchyLevels(),
            fn($level) => $level['required'] === true
        );
    }

    /**
     * Check if level is required for this country
     */
    public function isLevelRequired(int $level): bool
    {
        $levels = $this->getHierarchyLevels();
        return $levels[$level]['required'] ?? false;
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Compare with another CountryCode
     */
    public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }
}