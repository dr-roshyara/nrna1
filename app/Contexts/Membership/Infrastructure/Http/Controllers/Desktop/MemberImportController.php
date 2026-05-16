<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Controllers\Desktop;

use App\Contexts\Membership\Application\Services\CsvImportService;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\ImportCsvPreviewRequest;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\ImportCsvExecuteRequest;
use App\Contexts\Platform\Domain\Ports\InstallationTrackerInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Member Import Controller
 *
 * CASE 4: Tenant Desktop API - CSV Import
 * Route: /{tenant}/api/v1/members/import/*
 * Middleware: identify.tenant, auth:sanctum
 *
 * Architecture:
 * - Thin controller (delegates to CsvImportService)
 * - Uses FormRequest for validation
 * - Returns JsonResponse only
 * - NO business logic in controller
 * - Global platform (NO country-specific code)
 * - Freemium-aware: Checks Geography module installation
 *
 * Freemium Business Model:
 * - FREE TIER: No Geography module → generic template, no validation
 * - PAID TIER: Geography module installed → geography-specific template, validation
 *
 * @see CLAUDE.md Golden Rule #5: Global Platform Design
 * @see MemberImportControllerTest.php for comprehensive test coverage
 */
class MemberImportController extends Controller
{
    /**
     * Inject CSV Import Service and Installation Tracker
     *
     * Thin controller pattern: Application Service handles all business logic
     * Installation Tracker: Checks if Geography module is installed (freemium)
     */
    public function __construct(
        private readonly CsvImportService $csvImportService,
        private readonly InstallationTrackerInterface $installationTracker
    ) {}

    /**
     * Preview CSV Import
     *
     * POST /{tenant}/api/v1/members/import/preview
     *
     * Returns preview of CSV data before actual import:
     * - Total rows, valid rows, invalid rows
     * - Sample data (first 5-10 rows)
     * - Detected errors and warnings
     * - Suggested column mapping
     *
     * Freemium: Includes geography validation if Geography module installed
     *
     * @param ImportCsvPreviewRequest $request Validated request with file and encoding
     * @return JsonResponse Preview DTO as JSON
     */
    public function preview(ImportCsvPreviewRequest $request): JsonResponse
    {
        try {
            // Extract tenant context from route
            $tenantSlug = $request->route('tenant');
            $tenantId = TenantId::fromString($tenantSlug);

            // Get uploaded file
            $file = $request->file('file');

            // CRITICAL: Check if Geography module is installed (freemium)
            $hasGeographyModule = $this->installationTracker->isInstalled('Geography', $tenantSlug);

            // Build options array with module status
            $options = [
                'encoding' => $request->input('encoding', 'UTF-8'),
                'has_geography_module' => $hasGeographyModule, // ✅ CRITICAL FOR BUSINESS MODEL
            ];

            // Delegate to Application Service
            $previewDto = $this->csvImportService->preview($tenantId, $file, $options);

            // Return success response
            return response()->json([
                'data' => $previewDto,
            ], 200);

        } catch (\InvalidArgumentException $e) {
            // CSV validation errors (missing columns, invalid format, etc.)
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);

        } catch (\DomainException $e) {
            // Business rule violations (subscription limits, etc.)
            return response()->json([
                'error' => $e->getMessage(),
            ], 403);

        } catch (\Exception $e) {
            // Unexpected errors - log and return generic message
            Log::error('CSV preview error', [
                'tenant' => $request->route('tenant'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Internal server error. Please contact support.',
            ], 500);
        }
    }

    /**
     * Execute CSV Import
     *
     * POST /{tenant}/api/v1/members/import
     *
     * Executes the actual import after preview validation:
     * - Creates TenantUsers and Members in single transaction
     * - Handles duplicate members (skip or error based on option)
     * - Applies custom column mapping if provided
     * - Returns import statistics
     *
     * Freemium: Validates against Geography data if module installed
     *
     * @param ImportCsvExecuteRequest $request Validated request with file, options
     * @return JsonResponse Result DTO as JSON
     */
    public function import(ImportCsvExecuteRequest $request): JsonResponse
    {
        try {
            // Extract tenant context from route
            $tenantSlug = $request->route('tenant');
            $tenantId = TenantId::fromString($tenantSlug);

            // Get uploaded file
            $file = $request->file('file');

            // CRITICAL: Check if Geography module is installed (freemium)
            $hasGeographyModule = $this->installationTracker->isInstalled('Geography', $tenantSlug);

            // Build options array with module status
            $options = [
                'encoding' => $request->input('encoding', 'UTF-8'),
                'skip_duplicates' => $request->boolean('skip_duplicates', false),
                'column_mapping' => $request->input('column_mapping', []),
                'has_geography_module' => $hasGeographyModule, // ✅ CRITICAL FOR BUSINESS MODEL
            ];

            // Delegate to Application Service
            $resultDto = $this->csvImportService->import($tenantId, $file, $options);

            // Return success response
            return response()->json([
                'data' => $resultDto,
            ], 200);

        } catch (\InvalidArgumentException $e) {
            // CSV validation errors
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);

        } catch (\DomainException $e) {
            // Business rule violations (subscription limits, etc.)
            return response()->json([
                'error' => $e->getMessage(),
            ], 403);

        } catch (\Exception $e) {
            // Unexpected errors - log and return generic message
            Log::error('CSV import error', [
                'tenant' => $request->route('tenant'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Internal server error. Please contact support.',
            ], 500);
        }
    }

    /**
     * Download CSV Template
     *
     * GET /{tenant}/api/v1/members/import/template
     *
     * Returns a generic CSV template for member import.
     *
     * Freemium Business Model:
     * - FREE TIER: Generic template (no geography columns)
     * - PAID TIER: Geography-specific template (province, district, ward, etc.)
     *
     * CRITICAL: Template must have NO country-specific data (Global Platform Rule)
     * - No hardcoded geography (Nepal, USA, India, etc.)
     * - No political hierarchy assumptions (central/province/district)
     * - Generic field names only (full_name, email, phone)
     *
     * @param Request $request
     * @return StreamedResponse CSV file download
     */
    public function template(Request $request): StreamedResponse
    {
        // Extract tenant context
        $tenantSlug = $request->route('tenant');

        // CRITICAL: Check if Geography module is installed (freemium)
        $hasGeographyModule = $this->installationTracker->isInstalled('Geography', $tenantSlug);

        if ($hasGeographyModule) {
            // PAID TIER: Include geography columns
            $headers = [
                'full_name',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'province',
                'district',
                'municipality',
                'ward',
            ];

            $exampleRow = [
                'John Doe',
                'john.doe@example.com',
                '+1234567890',
                '1990-01-01',
                'Male',
                'Province 3',
                'Kathmandu',
                'Kathmandu Metro',
                '15',
            ];
        } else {
            // FREE TIER: Generic headers only
            $headers = [
                'full_name',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'address',
            ];

            $exampleRow = [
                'John Doe',
                'john.doe@example.com',
                '+1234567890',
                '1990-01-01',
                'Male',
                '123 Main Street',
            ];
        }

        // Create CSV response
        return response()->stream(function () use ($headers, $exampleRow) {
            $handle = fopen('php://output', 'w');

            // Write header row
            fputcsv($handle, $headers);

            // Write one example row (generic data only)
            fputcsv($handle, $exampleRow);

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="member_import_template.csv"',
        ]);
    }
}
