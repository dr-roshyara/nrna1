<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Application\DTOs\MobileRegistrationDto;
use App\Contexts\Membership\Domain\Services\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use Illuminate\Support\Str;

/**
 * TenantAuth Provisioning Adapter (Test/Stub Implementation)
 *
 * INFRASTRUCTURE LAYER - Implements TenantUserProvisioningInterface
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
final class TenantAuthProvisioningAdapter implements TenantUserProvisioningInterface
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
}
