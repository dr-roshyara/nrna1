<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class VoterSlug extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'slug',
        'expires_at',
        'is_active',
        'current_step',
        'step_meta',
        'election_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'current_step' => 'integer',
        'step_meta' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function steps()
    {
        return $this->hasMany(VoterSlugStep::class, 'voter_slug_id');
    }

    // ============ EAGER LOADING SCOPES (OPTIMIZATION) ============

    /**
     * Load all relationships at once
     */
    public function scopeWithAllRelations($query)
    {
        return $query->with(['user', 'election', 'organisation']);
    }

    /**
     * Load only essential relationships for validation
     * Selects specific columns to reduce data transfer
     */
    public function scopeWithEssentialRelations($query)
    {
        return $query->with([
            'election' => function($q) {
                $q->select('id', 'organisation_id', 'type', 'status', 'end_date');
            },
            'organisation' => function($q) {
                $q->select('id', 'name');
            }
        ]);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Get the route key for implicit route model binding
     * Uses the 'slug' column instead of the default 'id'
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
