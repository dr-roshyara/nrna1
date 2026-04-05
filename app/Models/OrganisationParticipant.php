<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganisationParticipant extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'organisation_participants';

    protected $fillable = [
        'id',
        'organisation_id',
        'user_id',
        'participant_type',  // staff | guest | election_committee
        'role',
        'assigned_at',
        'expires_at',
        'permissions',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at'  => 'datetime',
        'permissions' => 'array',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeStaff(Builder $query): Builder
    {
        return $query->where('participant_type', 'staff');
    }

    public function scopeGuests(Builder $query): Builder
    {
        return $query->where('participant_type', 'guest');
    }

    public function scopeElectionCommittee(Builder $query): Builder
    {
        return $query->where('participant_type', 'election_committee');
    }

    /** Active = no expiry date, or expiry date in the future */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /**
     * Guest records with a past expires_at are considered expired.
     * Staff and election_committee records with no expires_at are never expired.
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return $this->expires_at->isPast();
    }
}
