<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

/**
 * BelongsToTenant Trait
 *
 * Provides automatic tenant scoping for Eloquent models.
 *
 * Features:
 * - Applies global scope to filter by organisation_id
 * - Auto-fills organisation_id on model creation
 * - Supports both null (default platform) and non-null organisation_id values
 *
 * Usage:
 *     class User extends Model {
 *         use BelongsToTenant;
 *     }
 *
 * Now all User queries will automatically filter by organisation_id:
 *     User::all();           // Only users from current organisation
 *     User::find($id);       // Returns null if not from current org
 *     User::create([...]);   // Auto-fills organisation_id
 */
trait BelongsToTenant
{
    /**
     * Boot the trait - register global scope and creating observer
     */
    protected static function bootBelongsToTenant()
    {
        // Add global scope to all queries
        static::addGlobalScope('tenant', function (Builder $query) {
            $orgId = session('current_organisation_id');

            // ✅ Convert legacy organisation_id=0 to platform org ID for consistency
            // When session org is 0 or null (platform/demo mode), use platform org's actual ID
            if ($orgId === 0 || $orgId === null) {
                $platformOrg = \App\Models\Organisation::where('slug', 'platform')->first();
                if ($platformOrg) {
                    $orgId = $platformOrg->id;
                }
                // If no platform org, use 0 as fallback (shouldn't happen in normal operation)
            }

            // Apply scope: filter by calculated organisation_id
            $query->where('organisation_id', $orgId);
        });

        // Auto-fill organisation_id when creating
        static::creating(function (Model $model) {
            // Only set if not already set
            if (is_null($model->organisation_id)) {
                $sessionOrgId = session('current_organisation_id');

                // If session is null or 0 (demo/platform mode), use platform organisation ID
                if ($sessionOrgId === null || $sessionOrgId === 0) {
                    $platformOrg = \App\Models\Organisation::where('slug', 'platform')->first();
                    if ($platformOrg) {
                        $model->organisation_id = $platformOrg->id;
                    } else {
                        // Fallback: should not happen if seeding is correct
                        $model->organisation_id = 1;
                    }
                } else {
                    // Use the session organisation
                    $model->organisation_id = $sessionOrgId;
                }
            }
        });
    }

    /**
     * Scope: Include all records (bypass global scope for admin operations)
     *
     * Usage: User::withoutGlobalScopes()->get()
     */
    public function scopeIgnoreTenant(Builder $query)
    {
        return $query->withoutGlobalScopes();
    }

    /**
     * Scope: Only records from a specific organisation
     *
     * Usage: User::forOrganisation(1)->get()
     */
    public function scopeForOrganisation(Builder $query, $organisationId)
    {
        return $query->withoutGlobalScopes()->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Only records from default platform (organisation_id = 0)
     *
     * Usage: User::forDefaultPlatform()->get()
     */
    public function scopeForDefaultPlatform(Builder $query)
    {
        return $query->withoutGlobalScopes()->where('organisation_id', 0);
    }

    /**
     * Check if this model belongs to the current organisation
     *
     * @return bool
     */
    public function belongsToCurrentOrganisation(): bool
    {
        return $this->organisation_id === session('current_organisation_id');
    }

    /**
     * Check if this model belongs to a specific organisation
     *
     * @param int|null $organisationId
     * @return bool
     */
    public function belongsToOrganisation($organisationId): bool
    {
        return $this->organisation_id === $organisationId;
    }
}
