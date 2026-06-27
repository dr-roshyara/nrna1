<?php

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Membership\Application\Services\MemberGeographyValidator;
use App\Contexts\Membership\Domain\Exceptions\InvalidGeographyException;
use App\Contexts\Membership\Domain\Exceptions\InvalidMemberGeographyException;
use App\Contexts\Membership\Domain\Models\Member;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

/**
 * Member Registration Service (Application Layer)
 *
 * Orchestrates member registration business logic.
 *
 * Responsibilities:
 * - Validate geography hierarchy using GeographyService
 * - Validate TenantUser link using TenantUserValidator (NEW - Day 1)
 * - Generate unique membership numbers
 * - Create member records
 * - Ensure business rules are enforced
 */
class MemberRegistrationService
{
    /**
     * Create service instance
     *
     * @param MemberGeographyValidator $geographyValidator Validates geography hierarchy using DDD patterns
     * @param TenantUserValidator $tenantUserValidator Validates TenantUser business rules (NEW - Day 1)
     */
    public function __construct(
        protected MemberGeographyValidator $geographyValidator,
        protected TenantUserValidator $tenantUserValidator
    ) {}

    /**
     * Register a new member
     *
     * @param array $data Member registration data
     * @return Member
     * @throws InvalidGeographyException
     * @throws \App\Contexts\Membership\Domain\Exceptions\InvalidTenantUserException
     */
    public function register(array $data): Member
    {
        // Validate required fields
        $this->validateRequiredFields($data);

        // Get tenant context
        $tenant = $this->getCurrentTenant();

        // Validate geography hierarchy and get GeoPath
        $geoPath = $this->validateGeography($data);

        // Validate TenantUser (if provided) - NEW: Day 1 TDD Implementation
        // This enforces: user exists, active, correct tenant, not already linked
        $validatedUser = $this->tenantUserValidator->validate(
            $data['tenant_user_id'] ?? null,
            $tenant->id
        );

        // Generate membership number
        $membershipNumber = $this->generateMembershipNumber($tenant);

        // Create member
        return DB::transaction(function () use ($data, $tenant, $membershipNumber, $validatedUser, $geoPath) {
            return Member::create([
                'tenant_id' => $tenant->id,
                'tenant_user_id' => $validatedUser ? $validatedUser->id : null, // Use validated ID
                'country_code' => $data['country_code'] ?? 'NP',
                'admin_unit_level1_id' => $data['admin_unit_level1_id'],
                'admin_unit_level2_id' => $data['admin_unit_level2_id'],
                'admin_unit_level3_id' => $data['admin_unit_level3_id'] ?? null,
                'admin_unit_level4_id' => $data['admin_unit_level4_id'] ?? null,
                'admin_unit_level5_id' => $data['admin_unit_level5_id'] ?? null,
                'admin_unit_level6_id' => $data['admin_unit_level6_id'] ?? null,
                'admin_unit_level7_id' => $data['admin_unit_level7_id'] ?? null,
                'admin_unit_level8_id' => $data['admin_unit_level8_id'] ?? null,
                'geo_path' => $geoPath,
                'full_name' => $data['full_name'],
                'membership_number' => $membershipNumber,
                'membership_type' => $data['membership_type'] ?? Member::TYPE_FULL,
                'status' => $data['status'] ?? Member::STATUS_ACTIVE,
            ]);
        });
    }

    /**
     * Validate required fields
     *
     * @throws InvalidGeographyException
     */
    protected function validateRequiredFields(array $data): void
    {
        // Province and District are REQUIRED (levels 1 & 2)
        if (empty($data['admin_unit_level1_id']) || empty($data['admin_unit_level2_id'])) {
            throw InvalidGeographyException::missingRequiredLevels();
        }
    }

    /**
     * Validate geography hierarchy
     *
     * Uses MemberGeographyValidator to validate that all provided unit IDs
     * form a valid hierarchical relationship using DDD patterns.
     *
     * @param array $data Member registration data
     * @return string Validated GeoPath as string
     * @throws InvalidGeographyException
     */
    protected function validateGeography(array $data): string
    {
        $countryCode = $data['country_code'] ?? 'NP';

        try {
            // Validate using DDD validator
            $geoPath = $this->geographyValidator->validateForRegistration(
                $countryCode,
                $this->extractGeographyIds($data)
            );

            return $geoPath->toString();

        } catch (InvalidMemberGeographyException $e) {
            // Translate to existing exception for backward compatibility
            throw new InvalidGeographyException($e->getMessage());
        }
    }

    /**
     * Extract geography IDs from registration data
     *
     * @param array $data Member registration data
     * @return array Array of 8 geography IDs (null for missing levels)
     */
    private function extractGeographyIds(array $data): array
    {
        return [
            $data['admin_unit_level1_id'] ?? null,
            $data['admin_unit_level2_id'] ?? null,
            $data['admin_unit_level3_id'] ?? null,
            $data['admin_unit_level4_id'] ?? null,
            $data['admin_unit_level5_id'] ?? null,
            $data['admin_unit_level6_id'] ?? null,
            $data['admin_unit_level7_id'] ?? null,
            $data['admin_unit_level8_id'] ?? null,
        ];
    }

    /**
     * Generate unique membership number
     *
     * Format: {TENANT_SLUG}-{YEAR}-{SEQUENCE}
     * Example: NCP-NEPAL-2025-000001
     */
    protected function generateMembershipNumber(Tenant $tenant): string
    {
        $slug = strtoupper($tenant->slug);
        $year = date('Y');

        // Get next sequence number for this tenant and year
        $sequence = $this->getNextSequence($tenant->id, $year);

        return "{$slug}-{$year}-{$sequence}";
    }

    /**
     * Get next sequence number for membership number
     */
    protected function getNextSequence(string $tenantId, string $year): string
    {
        // Find the highest sequence number for this tenant and year
        $lastMember = Member::forTenant($tenantId)
            ->where('membership_number', 'LIKE', "%-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastMember) {
            // First member for this tenant/year
            return str_pad('1', 6, '0', STR_PAD_LEFT);
        }

        // Extract sequence from last membership number
        // Format: {SLUG}-{YEAR}-{SEQUENCE}
        $parts = explode('-', $lastMember->membership_number);
        $lastSequence = (int) end($parts);

        // Increment and pad
        return str_pad((string) ($lastSequence + 1), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get current tenant
     *
     * Uses container resolution instead of Tenant::current() for better testability.
     * In production, the container will resolve the active tenant.
     * In tests, we can bind a mock tenant to the container.
     */
    protected function getCurrentTenant(): Tenant
    {
        return app(Tenant::class);
    }
}
