<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\TenantAuth\Domain\Models\TenantUser;
use App\Contexts\Membership\Domain\Exceptions\InvalidTenantUserException;
use App\Contexts\Membership\Domain\Repositories\TenantUserRepositoryInterface;

/**
 * TenantUserValidator (Application Layer Service)
 *
 * Validates TenantUser business rules before linking to Member profile.
 *
 * **Business Rules Enforced:**
 * 1. If tenant_user_id is null, return null (members without accounts allowed)
 * 2. TenantUser must exist in database
 * 3. TenantUser must be active (status = 'active')
 * 4. TenantUser must belong to correct tenant (tenant isolation)
 * 5. TenantUser must NOT already be linked to another Member
 *
 * **Usage Example:**
 * ```php
 * $validator = new TenantUserValidator($repository);
 * $validatedUser = $validator->validate($userId, $tenantId);
 * // If no exception thrown, user is valid for member registration
 * ```
 *
 * @package App\Contexts\Membership\Application\Services
 */
class TenantUserValidator
{
    /**
     * Create validator instance
     *
     * @param TenantUserRepositoryInterface $repository Repository for accessing TenantUser data
     */
    public function __construct(
        private readonly TenantUserRepositoryInterface $repository
    ) {}

    /**
     * Validate TenantUser for member registration
     *
     * **Return Scenarios:**
     * - null: If $tenantUserId is null (member without account - valid)
     * - TenantUser: If all validations pass
     * - Throws: If any business rule violation
     *
     * **Exception Scenarios:**
     * - InvalidTenantUserException::notFound() - User ID doesn't exist
     * - InvalidTenantUserException::wrongTenant() - Cross-tenant access attempt
     * - InvalidTenantUserException::inactive() - User is not active
     * - InvalidTenantUserException::alreadyLinked() - User already has member profile
     *
     * @param int|null $tenantUserId The TenantUser ID to validate (null allowed)
     * @param int $tenantId The tenant context for validation
     * @return TenantUser|null The validated user, or null if no user ID provided
     * @throws InvalidTenantUserException If validation fails
     */
    public function validate(?int $tenantUserId, int $tenantId): ?TenantUser
    {
        // Business Rule 1: Allow members without user accounts
        if ($tenantUserId === null) {
            return null;
        }

        // Fetch user with tenant isolation
        $user = $this->repository->findById($tenantUserId, $tenantId);

        // Business Rule 2: User must exist
        if ($user === null) {
            throw InvalidTenantUserException::notFound($tenantUserId);
        }

        // Business Rule 3: User must belong to correct tenant
        // (Repository already filters by tenant_id, but double-check for security)
        if ($user->tenant_id !== $tenantId) {
            throw InvalidTenantUserException::wrongTenant(
                $tenantUserId,
                $tenantId,
                $user->tenant_id
            );
        }

        // Business Rule 4: User must be active
        if ($user->status !== 'active') {
            throw InvalidTenantUserException::inactive($tenantUserId, $user->status);
        }

        // Business Rule 5: User must NOT already have a member profile
        if ($user->member !== null) {
            throw InvalidTenantUserException::alreadyLinked($tenantUserId, $user->member->id);
        }

        // All validations passed
        return $user;
    }
}
