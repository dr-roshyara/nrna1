<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VoterRegistration Model
 *
 * Tracks the voter registration status for each user in each election.
 * Separates voter intent from user identity, supporting both demo and real elections.
 */
class VoterRegistration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'election_id',
        'status',
        'election_type',
        'registered_at',
        'approved_at',
        'voted_at',
        'approved_by',
        'rejected_by',
        'rejection_reason',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'voted_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user associated with this voter registration
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the election associated with this voter registration
     *
     * @return BelongsTo
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Check if voter registration is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if voter is approved
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if voter has voted
     *
     * @return bool
     */
    public function hasVoted(): bool
    {
        return $this->status === 'voted';
    }

    /**
     * Check if voter is rejected
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if this is a demo election registration
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return $this->election_type === 'demo';
    }

    /**
     * Check if this is a real election registration
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->election_type === 'real';
    }

    /**
     * Approve this voter registration
     *
     * @param string $approvedBy Name of committee member approving
     * @param array $metadata Optional metadata to store
     * @return $this
     */
    public function approve(string $approvedBy, array $metadata = []): self
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'rejected_by' => null,
            'rejection_reason' => null,
            'metadata' => array_merge($this->metadata ?? [], $metadata),
        ]);

        return $this;
    }

    /**
     * Reject this voter registration
     *
     * @param string $rejectedBy Name of committee member rejecting
     * @param string $reason Reason for rejection
     * @param array $metadata Optional metadata to store
     * @return $this
     */
    public function reject(string $rejectedBy, string $reason = '', array $metadata = []): self
    {
        $this->update([
            'status' => 'rejected',
            'rejected_by' => $rejectedBy,
            'rejection_reason' => $reason,
            'approved_by' => null,
            'approved_at' => null,
            'metadata' => array_merge($this->metadata ?? [], $metadata),
        ]);

        return $this;
    }

    /**
     * Mark voter as voted
     *
     * @param array $metadata Optional metadata to store (e.g., IP, timestamp)
     * @return $this
     */
    public function markAsVoted(array $metadata = []): self
    {
        $this->update([
            'status' => 'voted',
            'voted_at' => now(),
            'metadata' => array_merge($this->metadata ?? [], $metadata),
        ]);

        return $this;
    }

    /**
     * Get human-readable status
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'voted' => 'Voted',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Get pending registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get voted registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVoted($query)
    {
        return $query->where('status', 'voted');
    }

    /**
     * Scope: Get demo election registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDemo($query)
    {
        return $query->where('election_type', 'demo');
    }

    /**
     * Scope: Get real election registrations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReal($query)
    {
        return $query->where('election_type', 'real');
    }
}
