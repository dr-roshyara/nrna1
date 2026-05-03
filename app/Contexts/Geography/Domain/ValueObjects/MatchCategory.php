<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

/**
 * MatchCategory Enum
 *
 * Categorizes fuzzy matching results by similarity level.
 */
enum MatchCategory: string
{
    case EXACT = 'exact';           // 1.0
    case VERY_HIGH = 'very_high';   // ≥ 0.95
    case HIGH = 'high';             // ≥ 0.85
    case MEDIUM = 'medium';         // ≥ 0.70
    case LOW = 'low';               // ≥ 0.50
    case NO_MATCH = 'no_match';     // < 0.50 or no matches

    /**
     * Determine category from similarity score
     */
    public static function fromSimilarityScore(SimilarityScore $score): self
    {
        return match (true) {
            $score->isExact() => self::EXACT,
            $score->isAbove(0.95) => self::VERY_HIGH,
            $score->isAbove(0.85) => self::HIGH,
            $score->isAbove(0.70) => self::MEDIUM,
            $score->isAbove(0.50) => self::LOW,
            default => self::NO_MATCH,
        };
    }

    /**
     * Get minimum threshold for this category
     */
    public function minThreshold(): float
    {
        return match ($this) {
            self::EXACT => 1.0,
            self::VERY_HIGH => 0.95,
            self::HIGH => 0.85,
            self::MEDIUM => 0.70,
            self::LOW => 0.50,
            self::NO_MATCH => 0.0,
        };
    }

    /**
     * Get maximum threshold for this category
     */
    public function maxThreshold(): float
    {
        return match ($this) {
            self::EXACT => 1.0,
            self::VERY_HIGH => 1.0,
            self::HIGH => 0.95,
            self::MEDIUM => 0.85,
            self::LOW => 0.70,
            self::NO_MATCH => 0.50,
        };
    }

    /**
     * Check if category indicates a match (not NO_MATCH)
     */
    public function isMatch(): bool
    {
        return $this !== self::NO_MATCH;
    }

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::EXACT => 'Exact Match',
            self::VERY_HIGH => 'Very High Match',
            self::HIGH => 'High Match',
            self::MEDIUM => 'Medium Match',
            self::LOW => 'Low Match',
            self::NO_MATCH => 'No Match',
        };
    }

    /**
     * Get CSS class for UI display
     */
    public function cssClass(): string
    {
        return match ($this) {
            self::EXACT => 'match-exact',
            self::VERY_HIGH => 'match-very-high',
            self::HIGH => 'match-high',
            self::MEDIUM => 'match-medium',
            self::LOW => 'match-low',
            self::NO_MATCH => 'match-none',
        };
    }
}