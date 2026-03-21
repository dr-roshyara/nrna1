<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionOfficer extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'election_officers';

    protected $fillable = [
        'organisation_id',
        'user_id',
        'election_id',
        'appointed_by',
        'role',
        'status',
        'appointed_at',
        'accepted_at',
        'term_ends_at',
        'permissions',
        'metadata',
    ];

    protected $casts = [
        'appointed_at' => 'datetime',
        'accepted_at'  => 'datetime',
        'term_ends_at' => 'datetime',
        'permissions'  => 'array',
        'metadata'     => 'array',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class)->withoutGlobalScopes();
    }

    public function appointer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'appointed_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('term_ends_at')
                  ->orWhere('term_ends_at', '>', now());
            });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    public function scopeForElection($query, ?string $electionId)
    {
        return $query->where(function ($q) use ($electionId) {
            $q->where('election_id', $electionId)
              ->orWhereNull('election_id');
        });
    }

    // ── State helpers ─────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active'
            && (! $this->term_ends_at || $this->term_ends_at->isFuture());
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isChief(): bool
    {
        return $this->role === 'chief';
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    /**
     * Transition a pending appointment to active.
     * Authorization (ownership check) is the caller's responsibility.
     */
    public function markAccepted(): void
    {
        $this->update([
            'status'      => 'active',
            'accepted_at' => now(),
        ]);
    }
}
