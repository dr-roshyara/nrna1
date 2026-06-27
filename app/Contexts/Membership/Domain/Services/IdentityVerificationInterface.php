<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

/**
 * Identity Verification Service Interface
 *
 * Domain service for verifying digital identity existence.
 *
 * Business Rule (Digital Identity First):
 * Every member MUST have a tenant user account (1:1 required relationship).
 * This service ensures the user exists before member registration.
 *
 * Implementation notes:
 * - This is a DOMAIN service interface (in Domain/Services/)
 * - Implementation is in Infrastructure layer
 * - Used by application handlers to enforce business rules
 */
interface IdentityVerificationInterface
{
    /**
     * Verify that a user exists in the tenant system
     *
     * Business rule: Digital identity is mandatory before member registration.
     * A member cannot exist without a corresponding tenant user account.
     *
     * @param string $tenantUserId The tenant user system ID (ULID)
     * @param string $tenantId The tenant identifier
     * @return bool True if user exists and is active
     */
    public function userExists(string $tenantUserId, string $tenantId): bool;
}
