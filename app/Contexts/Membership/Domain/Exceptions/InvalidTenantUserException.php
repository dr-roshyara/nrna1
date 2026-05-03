<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Exceptions;

use DomainException;

/**
 * InvalidTenantUserException (Domain Layer Exception)
 *
 * Thrown when a TenantUser fails business rule validation during member registration.
 *
 * This exception represents a domain-level business rule violation, not a technical error.
 * It should be caught at the application layer and translated to appropriate HTTP responses.
 *
 * Business Rules Enforced:
 * - TenantUser must exist
 * - TenantUser must be active
 * - TenantUser must belong to the correct tenant (tenant isolation)
 * - TenantUser must not already be linked to another Member
 *
 * @package App\Contexts\Membership\Domain\Exceptions
 */
class InvalidTenantUserException extends DomainException
{
    /**
     * Exception: User not found in repository
     *
     * @param int $userId The ID that was not found
     * @return self
     */
    public static function notFound(int $userId): self
    {
        return new self(
            "User not found: TenantUser with ID {$userId} does not exist in the database."
        );
    }

    /**
     * Exception: User account is inactive
     *
     * Business Rule: Only active users can be linked to member profiles.
     * Inactive, suspended, or banned users cannot register as members.
     *
     * @param int $userId The ID of the inactive user
     * @param string $status The actual status of the user
     * @return self
     */
    public static function inactive(int $userId, string $status): self
    {
        return new self(
            "User account is inactive: TenantUser {$userId} has status '{$status}'. Only 'active' users can be linked to members."
        );
    }

    /**
     * Exception: User belongs to a different tenant
     *
     * **CRITICAL SECURITY CHECK**
     * This prevents cross-tenant data access. If this exception is thrown,
     * it may indicate:
     * - A bug in tenant context resolution
     * - An attempted security breach
     * - Incorrect tenant_id being passed to validator
     *
     * @param int $userId The user ID
     * @param int $expectedTenantId The tenant ID we expected
     * @param int $actualTenantId The actual tenant ID from user record
     * @return self
     */
    public static function wrongTenant(int $userId, int $expectedTenantId, int $actualTenantId): self
    {
        return new self(
            "User belongs to a different tenant: TenantUser {$userId} belongs to tenant {$actualTenantId}, "
            . "but member registration is for tenant {$expectedTenantId}. "
            . "This violates tenant isolation security."
        );
    }

    /**
     * Exception: User already linked to another member
     *
     * Business Rule: 1 user account = 1 member profile (1:1 relationship).
     * If a user already has a member profile, they cannot create another.
     *
     * This could indicate:
     * - User trying to create duplicate profile
     * - Bug in member registration form (should check for existing profile)
     *
     * @param int $userId The user ID
     * @param int $existingMemberId The ID of the existing member profile
     * @return self
     */
    public static function alreadyLinked(int $userId, int $existingMemberId): self
    {
        return new self(
            "User is already linked to a member profile: TenantUser {$userId} is already linked to Member {$existingMemberId}. "
            . "A user can only have one member profile."
        );
    }

    /**
     * Generic exception for unexpected validation failures
     *
     * Use this only when the specific factory methods don't apply.
     *
     * @param string $reason The reason for validation failure
     * @return self
     */
    public static function generic(string $reason): self
    {
        return new self($reason);
    }
}
