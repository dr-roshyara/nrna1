<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * CSV Row Validation Error Value Object
 *
 * Immutable value object representing a validation error for a CSV row
 * Used in global political platform for ANY country's validation rules
 */
final readonly class CsvRowValidationError
{
    /**
     * @param int $rowNumber Row number in CSV (1-indexed)
     * @param string $field Field name that failed validation
     * @param string $value The invalid value
     * @param string $errorCode Machine-readable error code (e.g., 'INVALID_EMAIL', 'MISSING_REQUIRED_FIELD')
     * @param string $errorMessage Human-readable error message
     * @param array<string, mixed> $context Additional context for error (e.g., expected format, allowed values)
     */
    public function __construct(
        public int $rowNumber,
        public string $field,
        public string $value,
        public string $errorCode,
        public string $errorMessage,
        public array $context = []
    ) {
        if ($rowNumber < 1) {
            throw new \InvalidArgumentException('Row number must be positive');
        }

        if (empty($field)) {
            throw new \InvalidArgumentException('Field name cannot be empty');
        }

        if (empty($errorCode)) {
            throw new \InvalidArgumentException('Error code cannot be empty');
        }

        if (empty($errorMessage)) {
            throw new \InvalidArgumentException('Error message cannot be empty');
        }
    }

    /**
     * Check if this error is for a specific field
     */
    public function isForField(string $fieldName): bool
    {
        return $this->field === $fieldName;
    }

    /**
     * Check if this error matches a specific error code
     */
    public function hasErrorCode(string $code): bool
    {
        return $this->errorCode === $code;
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
            'field' => $this->field,
            'value' => $this->value,
            'error_code' => $this->errorCode,
            'error_message' => $this->errorMessage,
            'context' => $this->context,
        ];
    }
}
