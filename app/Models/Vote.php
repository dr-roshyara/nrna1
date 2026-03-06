<?php

namespace App\Models;

/**
 * Vote Model - Real Election Votes
 *
 * Extends BaseVote to inherit all shared voting logic.
 * This model represents votes cast in REAL elections.
 *
 * Table: votes
 * Inheritance: Vote extends BaseVote
 * Siblings: DemoVote (for demo elections)
 *
 * Demo votes are stored in a separate demo_votes table to ensure
 * complete physical and logical separation from real voting data.
 */
class Vote extends BaseVote
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'votes';

    /**
     * Get all results aggregated from this vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Check if this is a real vote (always true for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return true;
    }

    /**
     * Check if this is a demo vote (always false for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return false;
    }

    /**
     * Check for duplicate voting from the same device
     *
     * Detects if a vote with the same device fingerprint already exists for this election.
     * Used for fraud detection without compromising voter privacy.
     *
     * @param string $fingerprintHash The device fingerprint hash
     * @param string $electionId The election ID
     * @return bool True if duplicate device detected
     */
    public static function hasDuplicateDevice(string $fingerprintHash, string $electionId): bool
    {
        return static::where('device_fingerprint_hash', $fingerprintHash)
            ->where('election_id', $electionId)
            ->exists();
    }

    /**
     * Scope: Get votes from the same device in an election
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $fingerprintHash The device fingerprint hash
     * @param string $electionId The election ID
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSameDevice($query, string $fingerprintHash, string $electionId)
    {
        return $query->where('device_fingerprint_hash', $fingerprintHash)
            ->where('election_id', $electionId);
    }
}
