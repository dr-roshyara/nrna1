<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Application\DTOs\MobileRegistrationDto;
use App\Contexts\Membership\Application\Interfaces\TenantUserProvisioningInterface as ApplicationProvisioningInterface;
use App\Contexts\Membership\Domain\Services\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\User;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * TenantAuth Provisioning Adapter (Test/Stub Implementation)
 *
 * INFRASTRUCTURE LAYER - Implements both Domain and Application interfaces
 *
 * TEST IMPLEMENTATION - For now, creates a stub tenant user.
 * Later, this will call the actual TenantAuth context via:
 * - API call to TenantAuth service
 * - Database call to TenantAuth tables
 * - Event-driven user creation
 *
 * Business Rule: Mobile registration MUST provision tenant user first.
 * This adapter ensures the Membership context doesn't know HOW users are created.
 */
final class TenantAuthProvisioningAdapter implements TenantUserProvisioningInterface, ApplicationProvisioningInterface
{
    /**
     * Provision a tenant user account for mobile registration
     *
     * TEST IMPLEMENTATION:
     * - Generates a fake ULID for tenant user ID
     * - Returns TenantUserId value object
     *
     * FUTURE IMPLEMENTATION:
     * - Call TenantAuth context API/Service
     * - Create tenant_user record in database
     * - Set initial status (pending_verification)
     * - Generate verification token
     * - Return actual TenantUserId
     *
     * @param MobileRegistrationDto $dto Mobile registration data
     * @return TenantUserId Created tenant user ID
     * @throws \RuntimeException If user creation fails
     */
    public function provisionForMobile(MobileRegistrationDto $dto): TenantUserId
    {
        // TEST IMPLEMENTATION: Generate fake ULID
        // In production, this would call TenantAuth context

        $fakeTenantUserId = (string) Str::ulid();

        // Log for debugging (remove in production)
        \Log::debug('TenantAuthProvisioningAdapter: Provisioning user for mobile', [
            'tenant_id' => $dto->tenantId,
            'email' => $dto->email,
            'generated_user_id' => $fakeTenantUserId,
        ]);

        return new TenantUserId($fakeTenantUserId);
    }

    /**
     * Provision a tenant user account for desktop registration
     *
     * TEST IMPLEMENTATION:
     * - Currently NOT used (desktop assumes user already exists)
     * - Returns TenantUserId from DTO
     *
     * FUTURE IMPLEMENTATION:
     * - Validate tenant_user_id exists in TenantAuth context
     * - Throw exception if user doesn't exist
     * - Return validated TenantUserId
     *
     * @param \App\Contexts\Membership\Application\DTOs\DesktopRegistrationDto $dto Desktop registration data
     * @return TenantUserId Existing tenant user ID
     * @throws \RuntimeException If user doesn't exist
     */
    public function provisionForDesktop(\App\Contexts\Membership\Application\DTOs\DesktopRegistrationDto $dto): TenantUserId
    {
        // TEST IMPLEMENTATION: Assume user exists, return from DTO
        // In production, this would validate user exists in TenantAuth

        \Log::debug('TenantAuthProvisioningAdapter: Desktop registration (user already exists)', [
            'tenant_id' => $dto->tenantId,
            'tenant_user_id' => $dto->tenantUserId,
        ]);

        return new TenantUserId($dto->tenantUserId);
    }

    // ========== APPLICATION INTERFACE METHODS ==========

    public function createForCsvImport(
        TenantId $tenantId,
        string $email,
        string $fullName,
        array $options = []
    ): TenantUserId {
        // Validate input
        $this->validateEmail($email);
        $this->validateFullName($fullName);

        // Normalize email (lowercase)
        $email = strtolower(trim($email));

        return DB::transaction(function () use ($tenantId, $email, $fullName) {
            // Idempotency: Check if user already exists, reuse if found
            $user = User::where('email', $email)->first();
            $action = 'created';

            if (!$user) {
                // Parse name for first_name/last_name columns
                [$firstName, $lastName] = $this->parseFullName($fullName);

                // Create new user
                $user = User::create([
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'name' => $fullName,
                    'password' => bcrypt(Str::random(32)),
                    'organisation_id' => $tenantId->value(),
                ]);
            } else {
                $action = 'reused';
            }

            // Check existing OrganisationUser, avoid duplicate
            $orgUser = OrganisationUser::where('user_id', $user->id)
                ->where('organisation_id', $tenantId->value())
                ->first();

            if (!$orgUser) {
                $orgUser = OrganisationUser::create([
                    'organisation_id' => $tenantId->value(),
                    'user_id' => $user->id,
                    'role' => 'member',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }

            // Log for audit trail
            \Log::info('User provisioned for CSV import', [
                'tenant_id' => $tenantId->value(),
                'email' => $email,
                'user_id' => $user->id,
                'org_user_id' => $orgUser->id,
                'action' => $action,
            ]);

            // Return the OrganisationUser ID (needed by Member aggregate)
            return new TenantUserId($orgUser->id);
        });
    }

    /**
     * Validate email format
     *
     * @throws \InvalidArgumentException If email is invalid
     */
    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: {$email}");
        }
    }

    /**
     * Validate full name
     *
     * @throws \InvalidArgumentException If name is invalid
     */
    private function validateFullName(string $fullName): void
    {
        if (empty(trim($fullName))) {
            throw new \InvalidArgumentException("Full name is required");
        }

        if (strlen($fullName) > 255) {
            throw new \InvalidArgumentException("Full name exceeds maximum length");
        }
    }

    /**
     * Parse full name into first and last name
     *
     * @return array [firstName, lastName]
     */
    private function parseFullName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';
        return [$firstName, $lastName];
    }

    public function isEmailAvailable(TenantId $tenantId, string $email): bool
    {
        // TEST IMPLEMENTATION: Always return true (email always available)
        // In production, check against TenantAuth context user table
        return true;
    }

    /**
     * Batch create users for CSV import (delegates to single creation to avoid duplication)
     */
    public function batchCreateForCsvImport(
        TenantId $tenantId,
        array $users,
        array $options = []
    ): array {
        $result = [];

        foreach ($users as $user) {
            $userId = $this->createForCsvImport(
                $tenantId,
                $user['email'],
                $user['fullName'] ?? $user['email']
            );
            $result[$user['email']] = $userId;
        }

        return $result;
    }
}
