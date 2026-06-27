<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

/**
 * SimilarityScore Value Object
 *
 * Represents a similarity score between 0.0 and 1.0 inclusive.
 * Used for fuzzy matching results.
 */
final class SimilarityScore
{
    private const MIN_SCORE = 0.0;
    private const MAX_SCORE = 1.0;

    private function __construct(
        private readonly float $value
    ) {
        if ($value < self::MIN_SCORE || $value > self::MAX_SCORE) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Similarity score must be between %.1f and %.1f, got %.2f',
                    self::MIN_SCORE,
                    self::MAX_SCORE,
                    $value
                )
            );
        }
    }

    /**
     * Create from float value
     */
    public static function fromFloat(float $value): self
    {
        return new self($value);
    }

    /**
     * Create from percentage integer (0-100)
     */
    public static function fromPercentage(int $percentage): self
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException(
                sprintf('Percentage must be between 0 and 100, got %d', $percentage)
            );
        }

        return new self($percentage / 100.0);
    }

    /**
     * Get the float value
     */
    public function toFloat(): float
    {
        return $this->value;
    }

    /**
     * Get as percentage (0-100)
     */
    public function toPercentage(): int
    {
        return (int) round($this->value * 100);
    }

    /**
     * Check if score is exact match (1.0)
     */
    public function isExact(): bool
    {
        return $this->value === 1.0;
    }

    /**
     * Check if score is above threshold
     */
    public function isAbove(float $threshold): bool
    {
        return $this->value >= $threshold;
    }

    /**
     * Check if score is below threshold
     */
    public function isBelow(float $threshold): bool
    {
        return $this->value < $threshold;
    }

    /**
     * Compare with another score
     */
    public function equals(self $other): bool
    {
        return abs($this->value - $other->value) < PHP_FLOAT_EPSILON;
    }

    /**
     * Check if this score is greater than another
     */
    public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    /**
     * Get string representation
     */
    public function __toString(): string
    {
        return sprintf('%.4f', $this->value);
    }
}