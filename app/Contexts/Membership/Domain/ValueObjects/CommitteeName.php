<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * CommitteeName Value Object
 *
 * Represents a committee name with validation rules.
 *
 * Business Rules:
 * - Must be non-empty
 * - Maximum 200 characters (database column size)
 * - Must contain at least one letter
 * - Allows letters, numbers, spaces, and basic punctuation
 * - Automatically trims and normalizes whitespace
 */
final class CommitteeName
{
    private string $value;

    public function __construct(string $name)
    {
        $this->validate($name);
        $this->value = $this->normalize($name);
    }

    /**
     * Create CommitteeName from string
     */
    public static function fromString(string $name): self
    {
        return new self($name);
    }

    private function validate(string $name): void
    {
        $trimmed = trim($name);

        if (empty($trimmed)) {
            throw new InvalidArgumentException('Committee name cannot be empty');
        }

        if (strlen($trimmed) > 200) {
            throw new InvalidArgumentException('Committee name cannot exceed 200 characters');
        }

        // Must contain at least one letter
        if (!preg_match('/[a-zA-Z]/', $trimmed)) {
            throw new InvalidArgumentException('Committee name must contain at least one letter');
        }

        // Allow letters, numbers, spaces, hyphens, apostrophes, parentheses, and basic punctuation
        if (!preg_match('/^[a-zA-Z0-9\s\-\.\',\(\)\&]+$/u', $trimmed)) {
            throw new InvalidArgumentException(
                'Committee name can only contain letters, numbers, spaces, hyphens, apostrophes, parentheses, periods, and ampersands'
            );
        }
    }

    private function normalize(string $name): string
    {
        $name = trim($name);

        // Replace multiple spaces with single space
        $name = preg_replace('/\s+/', ' ', $name);

        return $name;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(CommitteeName $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Get the first word of the committee name (useful for abbreviations)
     */
    public function firstWord(): string
    {
        $words = explode(' ', $this->value);
        return $words[0] ?? '';
    }

    /**
     * Get abbreviation (first letters of first 3 words)
     */
    public function abbreviation(): string
    {
        $words = explode(' ', $this->value);
        $abbrev = '';

        for ($i = 0; $i < min(3, count($words)); $i++) {
            if (!empty($words[$i])) {
                $abbrev .= strtoupper(substr($words[$i], 0, 1));
            }
        }

        return $abbrev;
    }
}