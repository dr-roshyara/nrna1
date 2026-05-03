<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * CSV Row Warning Value Object
 *
 * Immutable value object representing a non-blocking warning for a CSV row
 * Used for issues that don't prevent import but should be reviewed
 * Examples: duplicate detection, low-confidence geography matches, format suggestions
 *
 * Global Platform: Supports ANY political party worldwide
 */
final readonly class CsvRowWarning
{
    // Standard warning types for global platform
    public const WARNING_DUPLICATE_DETECTED = 'DUPLICATE_DETECTED';
    public const WARNING_LOW_GEOGRAPHY_MATCH = 'LOW_GEOGRAPHY_MATCH';
    public const WARNING_FORMAT_SUGGESTION = 'FORMAT_SUGGESTION';
    public const WARNING_MISSING_OPTIONAL_FIELD = 'MISSING_OPTIONAL_FIELD';
    public const WARNING_DATE_FORMAT_INFERRED = 'DATE_FORMAT_INFERRED';
    public const WARNING_PHONE_FORMAT_NORMALIZED = 'PHONE_FORMAT_NORMALIZED';
    public const WARNING_NAME_TRANSLITERATED = 'NAME_TRANSLITERATED';

    // Severity constants for type safety
    public const SEVERITY_LOW = 'low';
    public const SEVERITY_MEDIUM = 'medium';
    public const SEVERITY_HIGH = 'high';

    /**
     * @param int $rowNumber Row number in CSV (1-indexed)
     * @param string $warningType Type of warning (use constants)
     * @param string $message Human-readable warning message
     * @param string $severity Severity level (use constants)
     * @param array<string, mixed> $context Additional context
     */
    public function __construct(
        public int $rowNumber,
        public string $warningType,
        public string $message,
        public string $severity = self::SEVERITY_MEDIUM,
        public array $context = []
    ) {
        if ($rowNumber < 1) {
            throw new \InvalidArgumentException('Row number must be positive');
        }

        if (empty($warningType)) {
            throw new \InvalidArgumentException('Warning type cannot be empty');
        }

        if (empty($message)) {
            throw new \InvalidArgumentException('Warning message cannot be empty');
        }

        if (!in_array($severity, [self::SEVERITY_LOW, self::SEVERITY_MEDIUM, self::SEVERITY_HIGH], true)) {
            throw new \InvalidArgumentException('Severity must be one of: low, medium, high');
        }
    }

    /**
     * Check if warning is high severity
     */
    public function isHighSeverity(): bool
    {
        return $this->severity === 'high';
    }

    /**
     * Get array representation for serialization
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'row_number' => $this->rowNumber,
            'warning_type' => $this->warningType,
            'message' => $this->message,
            'severity' => $this->severity,
            'context' => $this->context,
        ];
    }
}
