<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\CsvRowValidationError;

/**
 * CSV Import Result DTO for Global Political Platform
 *
 * Pure data container - NO business logic
 * Contains results after actual CSV import execution
 *
 * Supports ANY political party worldwide with tenant-defined structures
 */
final readonly class CsvImportResultDto
{
    /**
     * @param TenantId $tenantId Tenant identifier (mandatory)
     * @param int $totalRows Total rows processed
     * @param int $successCount Successfully imported members
     * @param int $failureCount Failed imports
     * @param int $skippedCount Skipped rows (duplicates, validation failures)
     * @param array<int, CsvRowValidationError> $errors Errors that occurred during import
     * @param array<string, mixed> $importedMemberIds Map of row number to created member IDs
     * @param array<string, int> $statistics Import statistics (processing time, etc.)
     * @param array<string, mixed> $metadata Import metadata
     * @param \DateTimeImmutable $importedAt Timestamp when import completed
     */
    public function __construct(
        public TenantId $tenantId,
        public int $totalRows,
        public int $successCount,
        public int $failureCount,
        public int $skippedCount,
        public array $errors,
        public array $importedMemberIds,
        public array $statistics,
        public array $metadata,
        public \DateTimeImmutable $importedAt
    ) {
        // Pure data container - NO validation or business logic
    }
}
