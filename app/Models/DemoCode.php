<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

/**
 * DemoCode Model - Demo Election Voting Codes
 *
 * Separate codes table for demo elections, parallel to Code model for real elections.
 * Stores verification codes used in demo voting workflows.
 *
 * Table: demo_codes (separate from codes table)
 * Purpose: Testing verification workflows without affecting real election data
 *
 * Demo codes with multi-tenancy support:
 * - MODE 1: organisation_id = NULL (public demo, visible to all users)
 * - MODE 2: organisation_id = X (scoped to specific organisation)
 * - Can be reset/cleared without affecting real elections
 * - Used in demo voting verification flow
 * - Uses UUID primary keys for consistency with real codes
 */
class DemoCode extends Model
{
    use HasFactory, HasUuids;
    use BelongsToTenant;

    protected $table = 'demo_codes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',      // MODE 1: NULL, MODE 2: org_id
        'user_id',
        'election_id',          // Reference to demo election
        'code1',
        'code2',
        'is_code1_usable',
        'is_code2_usable',
        'code1_sent_at',
        'code2_sent_at',
        'can_vote_now',
        'has_voted',
        'code1_used_at',
        'code2_used_at',
        'vote_submitted',
        'vote_submitted_at',
        'has_code1_sent',
        'has_code2_sent',
        'has_agreed_to_vote',
        'has_used_code1',
        'has_used_code2',
        'has_agreed_to_vote_at',
        'voting_started_at',
        'is_codemodel_valid',
        // ✅ Device fingerprinting for fraud detection (privacy-preserving)
        'device_fingerprint_hash',
        'device_metadata_anonymized',
    ];

    protected $casts = [
        'has_code1_sent' => 'boolean',
        'is_code1_usable' => 'boolean',
        'is_code2_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'has_used_code1' => 'boolean',
        'has_used_code2' => 'boolean',
        'is_codemodel_valid' => 'boolean',
        'code1_sent_at' => 'datetime',
        'code2_sent_at' => 'datetime',
        'code1_used_at' => 'datetime',
        'code2_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'device_metadata_anonymized' => 'array',
    ];

    /**
     * Get the user this code belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the election this code is for
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Scope: Get verified codes (can_vote_now = 1)
     */
    public function scopeVerified($query)
    {
        return $query->where('can_vote_now', 1);
    }

    /**
     * Scope: Get unverified codes (can_vote_now = 0)
     */
    public function scopeUnverified($query)
    {
        return $query->where('can_vote_now', 0);
    }

    /**
     * Check if this code is verified
     */
    public function isVerified(): bool
    {
        return (bool) $this->can_vote_now;
    }

    /**
     * Check if code has expired
     */
    public function isExpired(): bool
    {
        if (!$this->code1_sent_at) {
            return false;
        }
        return \Carbon\Carbon::parse($this->code1_sent_at)->diffInMinutes(now()) > ($this->voting_time_in_minutes ?? config('voting.time_in_minutes', 30));
    }
}
