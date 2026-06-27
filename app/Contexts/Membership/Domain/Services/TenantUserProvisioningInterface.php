<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

use App\Contexts\Membership\Application\DTOs\MobileRegistrationDto;
use App\Contexts\Membership\Application\DTOs\DesktopRegistrationDto;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;

/**
 * Tenant User Provisioning Interface
 *
 * DOMAIN SERVICE - Context Boundary
 *
 * This interface decouples the Membership context from the TenantAuth context.
 * It defines the contract for provisioning tenant user accounts without
 * creating direct dependencies on TenantAuth implementation details.
 *
 * Architecture Pattern: Anti-Corruption Layer
 * - Membership context depends on this interface (domain layer)
 * - TenantAuth adapter implements this interface (infrastructure layer)
 * - Prevents tight coupling between bounded contexts
 *
 * Business Rules:
 * - Mobile registrations: Create pending user (no password yet)
 * - Desktop registrations: User already exists (admin provides ID)
 */
interface TenantUserProvisioningInterface
{
    /**
     * Provision tenant user for mobile registration
     *
     * Creates a pending user account in the TenantAuth context.
     * User will complete registration via email verification.
     *
     * Business Flow:
     * 1. Create tenant_user record (status: pending_verification)
     * 2. Generate verification token
     * 3. Return user ID for member creation
     *
     * @param MobileRegistrationDto $dto Mobile registration data
     * @return TenantUserId The provisioned user's ID
     * @throws \InvalidArgumentException If email already exists
     * @throws \RuntimeException If provisioning fails
     */
    public function provisionForMobile(MobileRegistrationDto $dto): TenantUserId;

    /**
     * Provision tenant user for desktop admin registration
     *
     * For desktop registrations, the tenant user already exists
     * (created by admin beforehand). This method validates existence
     * and returns the user ID wrapped in value object.
     *
     * Business Flow:
     * 1. Validate user exists in TenantAuth context
     * 2. Return user ID for member creation
     *
     * @param DesktopRegistrationDto $dto Desktop registration data
     * @return TenantUserId The existing user's ID
     * @throws \InvalidArgumentException If user doesn't exist
     */
    public function provisionForDesktop(DesktopRegistrationDto $dto): TenantUserId;
}
