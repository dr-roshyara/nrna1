<?php

namespace App\Models;

/**
 * DemoVote Model - Demo Election Votes
 *
 * Extends BaseVote to inherit all shared voting logic.
 * This model represents votes cast in DEMO elections for testing.
 *
 * Table: demo_votes (separate from votes table)
 * Inheritance: DemoVote extends BaseVote
 * Siblings: Vote (for real elections)
 *
 * Demo votes are stored in a separate demo_votes table to ensure:
 * - Complete physical separation from real voting data
 * - Easy cleanup and testing (can truncate demo_votes table)
 * - No risk of demo data contaminating real results
 *
 * Same voting business logic as Vote, but different table/lifecycle.
 */
class DemoVote extends BaseVote
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_votes';

    /**
     * Override parent casts for demo-specific fields
     *
     * Adds cast_at (inherited from parent, explicit here)
     * and no_vote_posts (inherited from parent, explicit here)
     *
     * @var array
     */
    protected $casts = [
        'candidate_selections' => 'array',
        'cast_at' => 'datetime',           // ✅ KEEP - inherited from parent but explicit
        'no_vote_posts' => 'array',        // ✅ KEEP - inherited from parent but explicit
        'device_metadata_anonymized' => 'array', // ✅ KEEP - fraud detection metadata
    ];

    /**
     * Override fillable to match demo_votes table schema.
     *
     * IMPORTANT: DemoVote uses candidate_selections (JSON) NOT individual candidate_xx columns.
     * The demo_votes table stores vote data differently than the real votes table:
     * - Real votes (parent BaseVote): Individual candidate_01..60 columns
     * - Demo votes: candidate_selections JSON + no_vote_option boolean
     *
     * For demo votes, we also use:
     * - receipt_hash: For voter self-verification (e.g., via email receipt)
     * - participation_proof: For IP-based admin verification (prove participation without revealing vote)
     * - encrypted_vote: Encrypted vote data for voter verification
     * - vote_hash: ✅ CRITICAL - Hash using code_id (NOT user_id) for anonymity
     * - no_vote_posts: ✅ KEEP - Posts where voter abstained
     * - device_fingerprint_hash: ✅ KEEP - Fraud detection (privacy-preserving)
     * - device_metadata_anonymized: ✅ KEEP - Anonymized device metadata
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id',
        'election_id',
        'receipt_hash',
        'participation_proof',
        'encrypted_vote',
        'vote_hash',                       // ✅ NEW - Critical for anonymity
        'candidate_selections',
        'no_vote_option',
        'no_vote_posts',                   // ✅ KEEP - Posts where voter abstained
        'voted_at',
        'voter_ip',
        'device_fingerprint_hash',         // ✅ KEEP - Fraud detection (privacy-preserving hash)
        'device_metadata_anonymized',      // ✅ KEEP - Anonymized device metadata
        'cast_at',                         // ✅ NEW - Timestamp of vote casting
    ];

    /**
     * Get all results aggregated from this demo vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(DemoResult::class);
    }

    /**
     * Verify this vote by receipt hash (voter self-verification)
     *
     * Allows a voter to verify their vote was counted by entering their receipt string.
     * The receipt string is hashed and compared against the stored receipt_hash.
     *
     * @param string $receipt The receipt string provided by voter
     * @return bool True if receipt matches
     */
    public function verifyByReceipt(string $receipt): bool
    {
        return hash('sha256', $receipt . config('app.salt')) === $this->receipt_hash;
    }

    /**
     * Prove participation by participation proof (IP-based admin verification)
     *
     * Allows election officials to verify a voter participated without seeing their vote.
     * Uses IP address + user ID + election ID to create a cryptographic proof.
     *
     * @param string $userId The user ID to verify
     * @param string $ip The IP address that cast the vote
     * @return bool True if participation can be proven
     */
    public function proveParticipation(string $userId, string $ip): bool
    {
        $proof = hash('sha256', $userId . $ip . $this->election_id . config('app.salt'));
        return $proof === $this->participation_proof;
    }

    /**
     * Check if this is a real vote (always false for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * Check if this is a demo vote (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Override boot hook to call parent and customize demo vote validation
     *
     * Demo votes use receipt_hash for voter verification.
     * Parent validates: election_id exists, receipt_hash is present, cast_at is set
     *
     * Demo-specific: Does NOT enforce organisation_id matching (demos are public)
     */
    protected static function booted()
    {
        // ✅ CRITICAL: Call parent::booted() to inherit validation logic
        parent::booted();

        static::creating(function ($vote) {
            // ✅ Demo vote validation passed (parent::booted() already validated election_id + receipt_hash)
            \Log::channel('voting_security')->info('Demo vote passed model validation', [
                'election_id' => $vote->election_id,
                'organisation_id' => $vote->organisation_id,
                'receipt_hash_prefix' => substr($vote->receipt_hash ?? '', 0, 10) . '...',
                'vote_hash_prefix' => substr($vote->vote_hash ?? '', 0, 10) . '...',
                'device_fingerprint' => substr($vote->device_fingerprint_hash ?? '', 0, 10) . '...',
                'timestamp' => now(),
                'ip' => request()->ip(),
            ]);
        });
    }

    /**
     * Scope: Get demo votes for current testing session
     * Demo votes are typically short-lived for testing purposes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentSession($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Delete all demo votes older than N days
     * Useful for scheduled cleanup of old test data.
     *
     * @param int $days
     * @return int
     */
    public static function cleanupOlderThan(int $days = 7): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
