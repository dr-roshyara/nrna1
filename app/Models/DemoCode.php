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
        'code_to_open_voting_form',
        'code_to_save_vote',
        'is_code_to_open_voting_form_usable',
        'is_code_to_save_vote_usable',
        'code_to_open_voting_form_sent_at',
        'code_to_save_vote_sent_at',
        'can_vote_now',
        'has_voted',
        'code_to_open_voting_form_used_at',
        'code_to_save_vote_used_at',
        'vote_submitted',
        'vote_submitted_at',
        'has_code1_sent',
        'has_code2_sent',
        'has_agreed_to_vote',
        'has_agreed_to_vote_at',
        'has_used_code1',
        'has_used_code2',
        'voting_started_at',
        'voting_time_in_minutes',
        'client_ip',
        // ✅ Device fingerprinting for fraud detection (privacy-preserving)
        'device_fingerprint_hash',
        'device_metadata_anonymized',
    ];

    protected $casts = [
        'has_code1_sent' => 'boolean',
        'has_code2_sent' => 'boolean',
        'is_code_to_open_voting_form_usable' => 'boolean',
        'is_code_to_save_vote_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'has_used_code1' => 'boolean',
        'has_used_code2' => 'boolean',
        'code_to_open_voting_form_sent_at' => 'datetime',
        'code_to_save_vote_sent_at' => 'datetime',
        'code_to_open_voting_form_used_at' => 'datetime',
        'code_to_save_vote_used_at' => 'datetime',
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
        if (!$this->code_to_open_voting_form_sent_at) {
            return false;
        }
        return \Carbon\Carbon::parse($this->code_to_open_voting_form_sent_at)->diffInMinutes(now()) > ($this->voting_time_in_minutes ?? config('voting.time_in_minutes', 30));
    }

    /**
     * Helper: Check if code to open voting form exists
     */
    public function hasOpenVotingFormCode(): bool
    {
        return !empty($this->code_to_open_voting_form);
    }

    /**
     * Helper: Check if code to save vote exists
     */
    public function hasSaveVoteCode(): bool
    {
        return !empty($this->code_to_save_vote);
    }

    /**
     * Helper: Mark code to open voting form as used
     */
    public function markOpenVotingFormCodeAsUsed(): void
    {
        $this->update([
            'code_to_open_voting_form_used_at' => now(),
            'is_code_to_open_voting_form_usable' => false,
        ]);
    }

    /**
     * Helper: Mark code to save vote as used
     */
    public function markSaveVoteCodeAsUsed(): void
    {
        $this->update([
            'code_to_save_vote_used_at' => now(),
            'is_code_to_save_vote_usable' => false,
        ]);
    }
}
