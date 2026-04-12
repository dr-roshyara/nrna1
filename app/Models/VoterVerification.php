<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoterVerification extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'election_id',
        'user_id',
        'organisation_id',
        'verified_ip',
        'verified_device_fingerprint_hash',
        'verified_device_components',
        'verified_by',
        'verified_at',
        'notes',
        'status',
        'revoked_by',
        'revoked_at',
    ];

    protected $casts = [
        'verified_device_components' => 'array',
        'verified_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Get the election this verification belongs to
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the voter (user) this verification is for
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who verified this voter
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the user who revoked this verification
     */
    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Get the organisation this verification belongs to
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Scope: Get active verifications
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get verifications for a specific election
     */
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /**
     * Scope: Get verification for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
