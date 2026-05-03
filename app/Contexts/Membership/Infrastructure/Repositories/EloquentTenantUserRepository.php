<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\TenantAuth\Domain\Models\TenantUser;
use App\Contexts\Membership\Domain\Repositories\TenantUserRepositoryInterface;

/**
 * EloquentTenantUserRepository (Infrastructure Layer)
 *
 * Eloquent-based implementation of TenantUserRepositoryInterface.
 *
 * **Responsibilities:**
 * - Execute database queries using Eloquent ORM
 * - Enforce tenant isolation at data access layer
 * - Eager-load relationships needed by domain layer
 * - Use tenant-specific database connection
 *
 * **Security Considerations:**
 * - ALWAYS includes tenant_id in WHERE clause
 * - Prevents cross-tenant data access at database level
 * - Uses parameterized queries (Eloquent prevents SQL injection)
 *
 * @package App\Contexts\Membership\Infrastructure\Repositories
 */
class EloquentTenantUserRepository implements TenantUserRepositoryInterface
{
    /**
     * Find TenantUser by ID with tenant isolation
     *
     * **Security:** Filters by both id AND tenant_id to prevent cross-tenant access.
     * **Performance:** Eager-loads 'member' relationship to avoid N+1 queries.
     *
     * Query executed:
     * ```sql
     * SELECT * FROM tenant_users
     * WHERE id = ? AND tenant_id = ?
     * LIMIT 1
     * ```
     *
     * @param int $id The TenantUser ID
     * @param int $tenantId The tenant ID for isolation
     * @return TenantUser|null
     */
    public function findById(int $id, int $tenantId): ?TenantUser
    {
        return TenantUser::query()
            ->where('id', $id)
            ->where('tenant_id', $tenantId) // CRITICAL: Tenant isolation
            ->with('member') // Eager load for "already linked" check
            ->first();
    }
}
