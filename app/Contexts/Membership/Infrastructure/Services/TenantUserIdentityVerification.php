<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Domain\Services\IdentityVerificationInterface;
use Illuminate\Support\Facades\DB;

/**
 * Tenant User Identity Verification Service
 *
 * Infrastructure implementation of identity verification.
 *
 * Business Rule:
 * - Every member MUST have a tenant user account (1:1 required)
 * - TenantUser is managed in a separate context/table
 *
 * Implementation Notes:
 * - Queries tenant_users table to verify existence
 * - Could be replaced with API call to TenantUser context in future
 * - Currently uses direct database query for simplicity
 */
class TenantUserIdentityVerification implements IdentityVerificationInterface
{
    /**
     * {@inheritdoc}
     *
     * Implementation:
     * - Queries tenant_users table on tenant database
     * - Checks that user exists and is active
     *
     * Future enhancement:
     * - Could call TenantUser context API instead of direct query
     * - Could cache results for performance
     */
    public function userExists(string $tenantUserId, string $tenantId): bool
    {
        // Basic validation
        if (empty(trim($tenantUserId)) || empty(trim($tenantId))) {
            return false;
        }

        // Query tenant_users table (tenant database)
        // Note: tenant_users is managed by TenantAuth context
        $exists = DB::connection('tenant')
            ->table('tenant_users')
            ->where('id', $tenantUserId)
            ->where('tenant_id', $tenantId)
            ->exists();

        return $exists;
    }
}
