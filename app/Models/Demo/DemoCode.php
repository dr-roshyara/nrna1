<?php

namespace App\Models\Demo;

use App\Models\Code;

/**
 * DemoCode Model - Demo Election Codes
 *
 * Extends Code to inherit all shared voting code logic.
 * This model represents voting codes used in DEMO elections for testing.
 *
 * Table: demo_codes
 * Inheritance: DemoCode extends Code
 * Siblings: Code (for real elections)
 *
 * Demo codes are stored in a separate demo_codes table to ensure
 * complete physical and logical separation from real voting data.
 *
 * Demo codes can be reset and reused for testing purposes.
 */
class DemoCode extends Code
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_codes';

    /**
     * ✅ OVERRIDE parent fillable for demo-specific columns
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id',                              // MODE 1: NULL, MODE 2: org_id
        'user_id',
        'election_id',                                  // Reference to demo election
        'voting_code',                                  // ✅ NEW: Anonymity bridge to vote record
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
        'voting_started_at',
        'voting_time_in_minutes',                       // ✅ KEEP - critical for session expiration
        'client_ip',
        // ✅ Device fingerprinting for fraud detection (privacy-preserving)
        'device_fingerprint_hash',
        'device_metadata_anonymized',
    ];

    /**
     * ✅ OVERRIDE parent casts for demo-specific fields
     *
     * @var array
     */
    protected $casts = [
        'has_code1_sent' => 'boolean',
        'has_code2_sent' => 'boolean',
        'is_code_to_open_voting_form_usable' => 'boolean',
        'is_code_to_save_vote_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'code_to_open_voting_form_sent_at' => 'datetime',
        'code_to_save_vote_sent_at' => 'datetime',
        'code_to_open_voting_form_used_at' => 'datetime',
        'code_to_save_vote_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'voting_time_in_minutes' => 'integer',         // ✅ Cast to integer
        'device_metadata_anonymized' => 'array',
    ];

    /**
     * Check if this is a demo code (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Check if this is a real code (always false for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
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
     * Check if code has expired
     */
    public function isExpired(): bool
    {
        if (!$this->code_to_open_voting_form_sent_at) {
            return false;
        }

        // Use voting_time_in_minutes from record if set, otherwise use config
        $votingWindow = $this->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
        $elapsedMinutes = \Carbon\Carbon::parse($this->code_to_open_voting_form_sent_at)->diffInMinutes(now());

        return $elapsedMinutes > $votingWindow;
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
