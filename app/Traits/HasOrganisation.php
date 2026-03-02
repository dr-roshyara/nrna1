<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * HasOrganisation Trait
 *
 * Provides standardized organisation relationship and scopes.
 * Ensures all models using this trait follow the British English spelling convention.
 *
 * Usage:
 *   class User extends Model {
 *       use HasOrganisation;
 *   }
 */
trait HasOrganisation
{
    /**
     * Boot the trait - ensure we're using the correct column name
     */
    protected static function bootHasOrganisation()
    {
        static::creating(function (Model $model) {
            // ⚠️ NOTE: Do NOT set organisation_id from session during creation.
            // This prevents new users from inheriting the organisation_id of the
            // last election accessed (e.g., if Election::first() has org_id=2).
            // Instead, let the User model boot() method handle the default value.
            // The User model explicitly sets organisation_id = platform org ID.

            // If someone tried to set organisation_id, move it to organisation_id
            if (isset($model->attributes['organisation_id'])) {
                $model->organisation_id = $model->attributes['organisation_id'];
                unset($model->attributes['organisation_id']);
            }
        });

        static::updating(function (Model $model) {
            // Clean up any rogue organisation_id attributes
            if (isset($model->attributes['organisation_id'])) {
                $model->organisation_id = $model->attributes['organisation_id'];
                unset($model->attributes['organisation_id']);
            }
        });
    }

    /**
     * Get the organisation that owns this model
     */
    public function organisation()
    {
        return $this->belongsTo(\App\Models\Organisation::class, 'organisation_id');
    }

    /**
     * Scope a query to only include records from a specific organisation
     */
    public function scopeForOrganisation($query, $orgId)
    {
        return $query->where('organisation_id', $orgId);
    }

    /**
     * Scope to include platform records (organisation_id = 0)
     */
    public function scopeIncludePlatform($query)
    {
        return $query->where('organisation_id', 0);
    }

    /**
     * Scope to get records for a specific organisation excluding platform
     */
    public function scopeForTenantOnly($query, $orgId)
    {
        return $query->where('organisation_id', $orgId)->where('organisation_id', '!=', 0);
    }
}
