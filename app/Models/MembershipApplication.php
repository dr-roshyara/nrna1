<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipApplication extends Model
{
    use HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'id',
        'organisation_id',
        'user_id',
        'membership_type_id',
        'applicant_email',
        'source',
        'status',
        'application_data',
        'expires_at',
        'lock_version',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'application_data' => 'array',
        'expires_at'       => 'datetime',
        'submitted_at'     => 'datetime',
        'reviewed_at'      => 'datetime',
        'lock_version'     => 'integer',
    ];

    // ── Public application helpers ────────────────────────────────────────────

    public function isPublicApplication(): bool
    {
        return $this->user_id === null && $this->source === 'public';
    }

    public function applicantName(): string
    {
        $data = $this->application_data ?? [];
        return trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    // ── Optimistic locking actions ────────────────────────────────────────────

    /**
     * Approve the application using optimistic locking.
     * Throws if another process already changed the record (race condition guard).
     *
     * @throws \App\Exceptions\ApplicationAlreadyProcessedException
     */
    public function approve(string $reviewedBy): void
    {
        $updated = static::where('id', $this->id)
            ->where('lock_version', $this->lock_version)
            ->where('status', 'submitted')
            ->update([
                'status'       => 'approved',
                'lock_version' => $this->lock_version + 1,
                'reviewed_by'  => $reviewedBy,
                'reviewed_at'  => now(),
            ]);

        if (! $updated) {
            throw new \App\Exceptions\ApplicationAlreadyProcessedException(
                'Application has already been processed or modified concurrently.'
            );
        }

        $this->refresh();
    }

    /**
     * Reject the application using optimistic locking.
     *
     * @throws \App\Exceptions\ApplicationAlreadyProcessedException
     */
    public function reject(string $reviewedBy, string $reason): void
    {
        $updated = static::where('id', $this->id)
            ->where('lock_version', $this->lock_version)
            ->where('status', 'submitted')
            ->update([
                'status'           => 'rejected',
                'lock_version'     => $this->lock_version + 1,
                'reviewed_by'      => $reviewedBy,
                'reviewed_at'      => now(),
                'rejection_reason' => $reason,
            ]);

        if (! $updated) {
            throw new \App\Exceptions\ApplicationAlreadyProcessedException(
                'Application has already been processed or modified concurrently.'
            );
        }

        $this->refresh();
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
