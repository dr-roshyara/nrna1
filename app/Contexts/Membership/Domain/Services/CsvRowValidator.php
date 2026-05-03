<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

use App\Contexts\Membership\Domain\ValueObjects\CsvRowValidationError;
use App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * CSV Row Validator Domain Service
 *
 * Validates individual CSV rows according to membership business rules
 * Global Platform: Supports ANY political party worldwide
 *
 * Note: Not marked final to enable mocking in unit tests.
 * Domain Services can be mocked for Application Service testing.
 */
class CsvRowValidator
{
    /**
     * Validate a single CSV row
     *
     * @param TenantId $tenantId
     * @param array<string, string> $row
     * @param int $rowNumber
     * @return array{valid: bool, errors: array<CsvRowValidationError>, warnings: array<CsvRowWarning>}
     */
    public function validateRow(TenantId $tenantId, array $row, int $rowNumber): array
    {
        $errors = [];
        $warnings = [];

        // Validate required fields
        if (empty($row['full_name'] ?? '')) {
            $errors[] = new CsvRowValidationError(
                rowNumber: $rowNumber,
                field: 'full_name',
                value: '',
                errorCode: 'MISSING_REQUIRED_FIELD',
                errorMessage: 'Full name is required',
                context: []
            );
        }

        // Validate email format
        if (empty($row['email'] ?? '')) {
            $errors[] = new CsvRowValidationError(
                rowNumber: $rowNumber,
                field: 'email',
                value: '',
                errorCode: 'MISSING_REQUIRED_FIELD',
                errorMessage: 'Email is required',
                context: []
            );
        } elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = new CsvRowValidationError(
                rowNumber: $rowNumber,
                field: 'email',
                value: $row['email'],
                errorCode: 'INVALID_EMAIL',
                errorMessage: 'Invalid email format',
                context: ['provided_email' => $row['email']]
            );
        }

        // Validate phone (optional field, but if provided must be reasonable)
        if (!empty($row['phone'] ?? '')) {
            $phone = preg_replace('/[^0-9+]/', '', $row['phone']);
            if (strlen($phone) < 7 || strlen($phone) > 20) {
                $warnings[] = new CsvRowWarning(
                    rowNumber: $rowNumber,
                    warningType: CsvRowWarning::WARNING_FORMAT_SUGGESTION,
                    message: 'Phone number format may be invalid',
                    severity: CsvRowWarning::SEVERITY_LOW,
                    context: ['phone' => $row['phone']]
                );
            }
        }

        $isValid = empty($errors);

        return [
            'valid' => $isValid,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Validate multiple rows in batch
     *
     * @param TenantId $tenantId
     * @param array<int, array<string, string>> $rows
     * @return array{total: int, valid: int, invalid: int, errors: array<CsvRowValidationError>, warnings: array<CsvRowWarning>}
     */
    public function validateRows(TenantId $tenantId, array $rows): array
    {
        $totalRows = count($rows);
        $validRows = 0;
        $invalidRows = 0;
        $allErrors = [];
        $allWarnings = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because header is row 1, data starts at row 2

            $result = $this->validateRow($tenantId, $row, $rowNumber);

            if ($result['valid']) {
                $validRows++;
            } else {
                $invalidRows++;
            }

            $allErrors = array_merge($allErrors, $result['errors']);
            $allWarnings = array_merge($allWarnings, $result['warnings']);
        }

        return [
            'total' => $totalRows,
            'valid' => $validRows,
            'invalid' => $invalidRows,
            'errors' => $allErrors,
            'warnings' => $allWarnings,
        ];
    }
}
