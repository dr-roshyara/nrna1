<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\CsvRowValidationError;
use App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning;

/**
 * CSV Import Preview DTO for Global Political Platform
 *
 * Pure data container - NO business logic
 * Supports ANY political party worldwide with tenant-defined structures
 *
 * Used to display preview results before actual import:
 * - Validation errors that will block import
 * - Warnings that won't block but need review
 * - Geography matching results for fuzzy matching
 * - Duplicate detection results
 * - Import statistics
 */
final readonly class CsvImportPreviewDto
{
    /**
     * @param TenantId $tenantId Tenant identifier (mandatory for tenant isolation)
     * @param int $totalRows Total rows in CSV file (excluding header)
     * @param int $validRows Rows that passed all validation rules
     * @param int $invalidRows Rows that failed validation
     * @param array<int, CsvRowValidationError> $errors Structured validation errors
     * @param array<int, CsvRowWarning> $warnings Non-blocking warnings
     * @param int $duplicateCount Number of duplicate members detected
     * @param int $geographyUnmatchedCount Rows with unmatched/ambiguous geography
     * @param array<string, int> $statistics Tenant-customizable statistics
     * @param array<string, mixed> $metadata Import metadata (file info, encoding, delimiter)
     */
    public function __construct(
        public TenantId $tenantId,
        public int $totalRows,
        public int $validRows,
        public int $invalidRows,
        public array $errors,
        public array $warnings,
        public int $duplicateCount,
        public int $geographyUnmatchedCount,
        public array $statistics,
        public array $metadata
    ) {
        // Pure data container - NO validation logic
        // Client code decides what to do with this data
    }
}
