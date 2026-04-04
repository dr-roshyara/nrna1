<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipType extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'slug',
        'description',
        'fee_amount',
        'fee_currency',
        'duration_months',
        'requires_approval',
        'form_schema',
        'is_active',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'fee_amount'         => 'decimal:2',
        'requires_approval'  => 'boolean',
        'is_active'          => 'boolean',
        'form_schema'        => 'array',
        'duration_months'    => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /**
     * A null duration means this is a lifetime membership with no expiry date.
     */
    public function isLifetime(): bool
    {
        return $this->duration_months === null;
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(MembershipApplication::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(MembershipFee::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(MembershipRenewal::class);
    }
}
