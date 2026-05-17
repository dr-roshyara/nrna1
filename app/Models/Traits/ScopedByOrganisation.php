<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * ScopedByOrganisation
 *
 * Strict API-context tenant scoping with fail-closed security.
 *
 * CRITICAL SECURITY BOUNDARY:
 * - When TenantContext::get() is null, THROWS EXCEPTION (never returns unfiltered data)
 * - No session fallback (API-context only)
 * - No platform-org cache complexity
 *
 * Use this ONLY for API-context models.
 * For web models needing platform-org fallback, use BelongsToTenant instead.
 *
 * ⚠️  Null TenantContext = Exception, NOT "return everything"
 */
trait ScopedByOrganisation
{
    protected static function bootScopedByOrganisation(): void
    {
        static::addGlobalScope('organisation_scope', function (Builder $builder) {
            $tenantId = TenantContext::get();

            // SECURITY: Fail closed — null context must throw, never return unfiltered
            if ($tenantId === null) {
                throw new \RuntimeException(
                    'TenantContext is not set. ' . static::class . ' requires tenant isolation.'
                );
            }

            $builder->where(
                $builder->getModel()->getTable() . '.organisation_id',
                $tenantId
            );
        });

        static::creating(function (Model $model) {
            if (empty($model->organisation_id)) {
                $tenantId = TenantContext::get();

                // SECURITY: Fail closed on create
                if ($tenantId === null) {
                    throw new \RuntimeException(
                        'Cannot create ' . static::class . ' without TenantContext set.'
                    );
                }

                $model->organisation_id = $tenantId;
            }
        });
    }

    /**
     * Bypass tenant scope (admin operations only).
     *
     * @deprecated Use repository pattern for explicit scoping instead.
     *            This is a safety valve, not the primary query pattern.
     */
    public function scopeWithoutTenantScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('organisation_scope');
    }

    /**
     * Query specific organisation (admin operations only).
     *
     * Bypasses automatic scope to allow admin, reporting, or cross-tenant lookup queries.
     *
     * @param Builder $query
     * @param string $organisationId
     * @return Builder
     */
    public function scopeForTenant(Builder $query, string $organisationId): Builder
    {
        return $query->withoutGlobalScope('organisation_scope')
                     ->where($this->getTable() . '.organisation_id', $organisationId);
    }
}
