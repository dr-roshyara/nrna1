<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\DTOs\CsvImportPreviewDto;
use App\Contexts\Membership\Application\DTOs\CsvImportResultDto;
use App\Contexts\Membership\Application\Interfaces\FeatureGateAdapterInterface;
use App\Contexts\Membership\Application\Interfaces\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Services\CsvRowValidator;
use App\Contexts\Membership\Infrastructure\Services\CsvParser;
use App\Contexts\Geography\Application\Interfaces\GeographyLookupInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\UploadedFile;

/**
 * CSV Import Application Service
 *
 * Thin Orchestrator Pattern (ADR-001):
 * - NO business logic (delegates to Domain Services)
 * - NO validation (delegates to CsvRowValidator)
 * - Coordinates: Parsing → Validation → Persistence
 * - Cross-Context Coordination: TenantAuth ↔ Membership
 *
 * Architecture:
 * - Subscription gating via FeatureGateAdapterInterface (Anti-Corruption Layer)
 * - CSV parsing via CsvParser (Infrastructure)
 * - Domain validation via CsvRowValidator (Domain)
 * - TenantUser creation via TenantUserProvisioningInterface (Cross-Context)
 * - Member creation via Member aggregate factory
 * - Persistence via MemberRepositoryInterface
 *
 * Global Platform: Supports ANY political party worldwide
 */
final class CsvImportService
{
    public function __construct(
        private readonly CsvParser $csvParser,
        private readonly CsvRowValidator $csvRowValidator,
        private readonly MemberRepositoryInterface $memberRepository,
        private readonly FeatureGateAdapterInterface $featureGate,
        private readonly TenantUserProvisioningInterface $tenantUserProvisioning,
        private readonly ?GeographyLookupInterface $geographyLookup = null
    ) {
    }

    /**
     * Preview CSV import without persisting data
     *
     * Business Rules:
     * - Subscription check happens FIRST
     * - Returns validation errors and warnings
     * - Detects duplicates (email already exists)
     * - Validates geography references if module installed
     * - NO data persistence occurs
     *
     * @param TenantId $tenantId The tenant identifier
     * @param UploadedFile $file The CSV file to preview
     * @param array<string, mixed> $options Optional parameters
     * @return CsvImportPreviewDto Preview results with statistics
     * @throws \DomainException If subscription required or file invalid
     */
    public function preview(TenantId $tenantId, UploadedFile $file, array $options = []): CsvImportPreviewDto
    {
        // 1. Subscription check (FIRST - fail fast)
        if (!$this->featureGate->canImportCsv($tenantId)) {
            throw new \DomainException('Subscription required for CSV import feature');
        }

        // 2. Parse CSV (Infrastructure layer)
        $parseResult = $this->csvParser->parse($file, $options);

        // 3. Validate empty file
        if ($parseResult->totalRows === 0) {
            throw new \InvalidArgumentException('CSV file is empty');
        }

        // 4. Domain validation (delegate to Domain Service)
        $validationResult = $this->csvRowValidator->validateRows($tenantId, $parseResult->rows);

        // 5. Duplicate detection (check existing members by email)
        $duplicateCount = 0;
        $warnings = $validationResult['warnings'];

        foreach ($parseResult->rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because header is row 1

            if (!empty($row['email'] ?? '')) {
                if ($this->memberRepository->existsByEmailForTenant($tenantId, $row['email'])) {
                    $duplicateCount++;
                    $warnings[] = new \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning(
                        rowNumber: $rowNumber,
                        warningType: \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning::WARNING_DUPLICATE_DETECTED,
                        message: 'Email already exists in system',
                        severity: \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning::SEVERITY_HIGH,
                        context: ['email' => $row['email']]
                    );
                }
            }
        }

        // 6. Geography validation (optional module)
        $geographyUnmatchedCount = 0;
        if ($this->geographyLookup !== null) {
            foreach ($parseResult->rows as $index => $row) {
                $rowNumber = $index + 2;

                if (!empty($row['geography'] ?? '')) {
                    $match = $this->geographyLookup->findByName($tenantId, $row['geography']);
                    if ($match === null) {
                        $geographyUnmatchedCount++;
                        $warnings[] = new \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning(
                            rowNumber: $rowNumber,
                            warningType: \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning::WARNING_LOW_GEOGRAPHY_MATCH,
                            message: 'Geography not found in system',
                            severity: \App\Contexts\Membership\Domain\ValueObjects\CsvRowWarning::SEVERITY_MEDIUM,
                            context: ['geography' => $row['geography']]
                        );
                    }
                }
            }
        }

        // 7. Build preview DTO (pure data container)
        return new CsvImportPreviewDto(
            tenantId: $tenantId,
            totalRows: $validationResult['total'],
            validRows: $validationResult['valid'],
            invalidRows: $validationResult['invalid'],
            errors: $validationResult['errors'],
            warnings: $warnings,
            duplicateCount: $duplicateCount,
            geographyUnmatchedCount: $geographyUnmatchedCount,
            statistics: [
                'encoding' => $parseResult->encoding,
                'delimiter' => $parseResult->delimiter,
                'file_size' => $parseResult->fileSize,
            ],
            metadata: $options
        );
    }

    /**
     * Import CSV and persist members
     *
     * Business Rules:
     * - Subscription check happens FIRST
     * - Quota check happens SECOND
     * - Creates TenantUsers for all members (cross-context)
     * - Creates Members linked to TenantUsers
     * - Skips duplicates (doesn't overwrite existing)
     * - Transactional (all or nothing within constraints)
     *
     * @param TenantId $tenantId The tenant identifier
     * @param UploadedFile $file The CSV file to import
     * @param array<string, mixed> $options Optional parameters
     * @return CsvImportResultDto Import results with statistics
     * @throws \DomainException If subscription required or quota exceeded
     */
    public function import(TenantId $tenantId, UploadedFile $file, array $options = []): CsvImportResultDto
    {
        $startTime = microtime(true);

        // 1. Subscription check (FIRST - fail fast)
        if (!$this->featureGate->canImportCsv($tenantId)) {
            throw new \DomainException('Subscription required for CSV import feature');
        }

        // 2. Parse CSV (Infrastructure layer)
        $parseResult = $this->csvParser->parse($file, $options);

        // 3. Quota check (SECOND - before any work)
        if ($this->featureGate->wouldExceedMemberQuota($tenantId, $parseResult->totalRows)) {
            throw new \DomainException('Member quota exceeded for tenant subscription');
        }

        // 4. Domain validation (delegate to Domain Service)
        $validationResult = $this->csvRowValidator->validateRows($tenantId, $parseResult->rows);

        // 5. Process each valid row
        $successCount = 0;
        $failureCount = 0;
        $skippedCount = 0;
        $importedMemberIds = [];
        $errors = $validationResult['errors'];

        foreach ($parseResult->rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because header is row 1

            // 5a. Skip if validation failed
            $rowValidation = $this->csvRowValidator->validateRow($tenantId, $row, $rowNumber);
            if (!$rowValidation['valid']) {
                $failureCount++;
                continue;
            }

            // 5b. Skip duplicates
            if (!empty($row['email']) && $this->memberRepository->existsByEmailForTenant($tenantId, $row['email'])) {
                $skippedCount++;
                continue;
            }

            try {
                // 5c. Create TenantUser (cross-context coordination)
                $tenantUserId = $this->tenantUserProvisioning->createForCsvImport(
                    tenantId: $tenantId,
                    email: $row['email'] ?? '',
                    fullName: $row['full_name'] ?? '',
                    options: [
                        'send_welcome_email' => $options['send_welcome_email'] ?? false,
                        'phone' => $row['phone'] ?? null,
                    ]
                );

                // 5d. Create Member aggregate (Domain factory)
                $member = Member::registerFromCsv(
                    tenantUserId: $tenantUserId,
                    tenantId: $tenantId,
                    csvRow: $row
                );

                // 5e. Save via Repository (ADR-001 compliant)
                $this->memberRepository->saveForTenant($tenantId, $member);

                $successCount++;
                $importedMemberIds[(string)$rowNumber] = $member->id;
            } catch (\Throwable $e) {
                $failureCount++;
                $errors[] = new \App\Contexts\Membership\Domain\ValueObjects\CsvRowValidationError(
                    rowNumber: $rowNumber,
                    field: 'system',
                    value: '',
                    errorCode: 'IMPORT_FAILED',
                    errorMessage: $e->getMessage(),
                    context: ['exception' => get_class($e)]
                );
            }
        }

        $endTime = microtime(true);

        // 6. Build result DTO (pure data container)
        return new CsvImportResultDto(
            tenantId: $tenantId,
            totalRows: $parseResult->totalRows,
            successCount: $successCount,
            failureCount: $failureCount,
            skippedCount: $skippedCount,
            errors: $errors,
            importedMemberIds: $importedMemberIds,
            statistics: [
                'processing_time_seconds' => round($endTime - $startTime, 2),
                'encoding' => $parseResult->encoding,
                'delimiter' => $parseResult->delimiter,
                'file_size' => $parseResult->fileSize,
            ],
            metadata: $options,
            importedAt: new \DateTimeImmutable()
        );
    }
}
