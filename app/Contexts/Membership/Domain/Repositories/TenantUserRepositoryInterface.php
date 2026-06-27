<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\TenantAuth\Domain\Models\TenantUser;

/**
 * TenantUserRepository Interface (Domain Layer)
 *
 * Repository contract for accessing TenantUser entities within the Membership Context.
 *
 * **Why Repository Pattern?**
 * - Tenant isolation security (prevents cross-tenant data access)
 * - Testability (essential for TDD - easy to mock)
 * - Domain-focused abstraction (business language)
 *
 * @package App\Contexts\Membership\Domain\Repositories
 */
interface TenantUserRepositoryInterface
{
    /**
     * Find TenantUser by ID within specific tenant
     *
     * **CRITICAL SECURITY:** Must verify tenant_id to prevent cross-tenant access.
     *
     * @param int $id The TenantUser ID to find
     * @param int $tenantId The tenant ID for isolation
     * @return TenantUser|null The user if found and belongs to tenant, null otherwise
     */
    public function findById(int $id, int $tenantId): ?TenantUser;
}
