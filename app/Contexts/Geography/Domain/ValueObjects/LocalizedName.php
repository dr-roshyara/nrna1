<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;
use JsonException;

/**
 * LocalizedName Value Object
 *
 * Represents multilingual names for geographic units.
 * Stores names in multiple languages with fallback logic.
 *
 * Example: ['en' => 'Kathmandu', 'np' => 'काठमाडौं']
 */
readonly class LocalizedName
{
    private array $names;

    /**
     * Private constructor - use factory methods
     *
     * @param array<string, string> $names Associative array [language => name]
     */
    private function __construct(array $names)
    {
        $this->validate($names);
        $this->names = $names;
    }

    /**
     * Create from associative array
     *
     * @param array<string, string> $names [language => name]
     */
    public static function fromArray(array $names): self
    {
        return new self($names);
    }

    /**
     * Create from JSON string
     */
    public static function fromJson(string $json): self
    {
        try {
            $names = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidArgumentException("Invalid JSON for LocalizedName: " . $e->getMessage());
        }

        if (!is_array($names)) {
            throw new InvalidArgumentException("JSON must decode to array for LocalizedName");
        }

        return new self($names);
    }

    /**
     * Create single-language name
     */
    public static function fromSingle(string $language, string $name): self
    {
        return new self([$language => $name]);
    }

    /**
     * Create English name only
     */
    public static function english(string $name): self
    {
        return new self(['en' => $name]);
    }

    /**
     * Validate names array
     */
    private function validate(array $names): void
    {
        if (empty($names)) {
            throw new InvalidArgumentException('LocalizedName cannot be empty');
        }

        foreach ($names as $language => $name) {
            // Validate language code (ISO 639-1, 2 letters)
            if (!is_string($language) || !preg_match('/^[a-z]{2}$/', $language)) {
                throw new InvalidArgumentException("Invalid language code: '{$language}'. Must be 2-letter ISO code.");
            }

            // Validate name is non-empty string
            if (!is_string($name) || trim($name) === '') {
                throw new InvalidArgumentException("Name for language '{$language}' cannot be empty");
            }
        }
    }

    /**
     * Get name in specific language
     * Returns null if language not available
     */
    public function get(string $language): ?string
    {
        return $this->names[$language] ?? null;
    }

    /**
     * Get name with fallback logic
     * Tries languages in order, returns first available
     *
     * @param array<string> $preferredLanguages Language codes in preference order
     */
    public function getWithFallback(array $preferredLanguages = ['en', 'np']): string
    {
        foreach ($preferredLanguages as $language) {
            if (isset($this->names[$language])) {
                return $this->names[$language];
            }
        }

        // Return first available name as last resort
        return reset($this->names) ?: '';
    }

    /**
     * Get English name
     */
    public function getEnglish(): ?string
    {
        return $this->get('en');
    }

    /**
     * Get Nepali name
     */
    public function getNepali(): ?string
    {
        return $this->get('np');
    }

    /**
     * Check if language is available
     */
    public function hasLanguage(string $language): bool
    {
        return isset($this->names[$language]);
    }

    /**
     * Get all names as array
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->names;
    }

    /**
     * Get JSON representation
     */
    public function toJson(): string
    {
        try {
            return json_encode($this->names, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (JsonException $e) {
            throw new InvalidArgumentException("Failed to encode LocalizedName to JSON: " . $e->getMessage());
        }
    }

    /**
     * Get available languages
     *
     * @return array<string>
     */
    public function getLanguages(): array
    {
        return array_keys($this->names);
    }

    /**
     * Create new instance with additional language
     */
    public function withLanguage(string $language, string $name): self
    {
        $newNames = $this->names;
        $newNames[$language] = $name;

        return new self($newNames);
    }

    /**
     * Create new instance without language
     */
    public function withoutLanguage(string $language): self
    {
        $newNames = $this->names;
        unset($newNames[$language]);

        if (empty($newNames)) {
            throw new InvalidArgumentException("Cannot remove last language from LocalizedName");
        }

        return new self($newNames);
    }

    /**
     * Check equality with another LocalizedName
     * Compares names arrays (order doesn't matter)
     */
    public function equals(self $other): bool
    {
        return $this->names === $other->names;
    }

    /**
     * String representation (English name or first available)
     */
    public function __toString(): string
    {
        return $this->getWithFallback(['en', 'np']);
    }
}