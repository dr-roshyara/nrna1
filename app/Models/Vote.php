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
     * Cast attributes to native types.
     * Merges with parent's casts to preserve JSON casting for arrays.
     *
     * @var array
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'no_vote_option' => 'boolean',
        'no_vote_posts' => 'array',
        'metadata' => 'array',
        'device_metadata_anonymized' => 'array',
        'cast_at' => 'datetime',
    ];

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
     * Create Result records for each selected candidate AND abstentions in this vote
     * Extracts data from candidate_01 through candidate_60 columns (JSON format)
     * and creates Result records for:
     * 1. Selected candidates (no_vote = false, candidacy_id set)
     * 2. Abstentions (no_vote = true, candidacy_id = null)
     *
     * @return void
     */
    public function createResultsFromCandidates(): void
    {
        // Delete existing results first (idempotent - safe if called multiple times)
        Result::where('vote_id', $this->id)->forceDelete();

        $candidateCount = 0;
        $abstentionCount = 0;

        // Iterate through all candidate columns (candidate_01 through candidate_60)
        for ($i = 1; $i <= 60; $i++) {
            $candidateKey = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $value = $this->$candidateKey;

            if ($value !== null && is_string($value) && str_starts_with($value, '{')) {
                $decoded = json_decode($value, true);
                $postId = $decoded['post_id'] ?? null;

                // ✅ Handle "No Vote" / Abstention
                if (isset($decoded['no_vote']) && $decoded['no_vote'] === true) {
                    Result::create([
                        'organisation_id' => $this->organisation_id,
                        'election_id' => $this->election_id,
                        'vote_id' => $this->id,
                        'post_id' => $postId,
                        'candidacy_id' => null,  // NULL for abstention
                        'no_vote' => true,
                        'position_order' => $i,
                    ]);
                    $abstentionCount++;
                    continue;  // Skip candidate processing
                }

                // ✅ Handle selected candidates
                if (isset($decoded['candidates']) && is_array($decoded['candidates'])) {
                    foreach ($decoded['candidates'] as $candidate) {
                        if (isset($candidate['candidacy_id'])) {
                            Result::create([
                                'organisation_id' => $this->organisation_id,
                                'election_id' => $this->election_id,
                                'vote_id' => $this->id,
                                'post_id' => $postId,
                                'candidacy_id' => $candidate['candidacy_id'],
                                'no_vote' => false,
                                'position_order' => $i,
                            ]);
                            $candidateCount++;
                        }
                    }
                }
            }
        }

        \Log::info('Results created for vote', [
            'vote_id' => $this->id,
            'candidate_count' => $candidateCount,
            'abstention_count' => $abstentionCount,
        ]);
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

    /**
     * Calculate SHA256 checksum of vote candidate data
     * Used to detect if vote data has been corrupted or modified
     *
     * @return string SHA256 hash (64 characters)
     */
    public function calculateChecksum(): string
    {
        $data = [];
        for ($i = 1; $i <= 60; $i++) {
            $col = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if ($this->$col) {
                $data[$col] = $this->$col;
            }
        }
        // JSON_SORT_KEYS = 4 (ensures deterministic output)
        return hash('sha256', json_encode($data, 4) . config('app.key'));
    }

    /**
     * Verify stored checksum matches calculated value
     * Returns true only if vote data has not been modified
     *
     * @return bool True if checksum is valid
     */
    public function verifyChecksum(): bool
    {
        return $this->data_checksum === $this->calculateChecksum();
    }

    /**
     * Get expected number of Result records for this vote
     * Counts actual candidate selections from JSON (excludes abstentions)
     *
     * @return int Expected result count
     */
    public function getExpectedResultCount(): int
    {
        $count = 0;
        for ($i = 1; $i <= 60; $i++) {
            $col = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $value = $this->$col;

            if ($value && is_string($value) && str_starts_with($value, '{')) {
                $decoded = json_decode($value, true);
                // Abstentions don't create candidate results
                if (isset($decoded['no_vote']) && $decoded['no_vote'] === true) {
                    $count++; // But they do create a result row with candidacy_id = null
                    continue;
                }
                if (isset($decoded['candidates']) && is_array($decoded['candidates'])) {
                    $count += count($decoded['candidates']);
                }
            }
        }
        return $count;
    }

    /**
     * Verify integrity of vote and its results
     * Checks that:
     * 1. Data checksum is valid (vote not modified)
     * 2. Result count matches expected count
     *
     * @return array Verification status with detailed information
     */
    public function verifyResultsIntegrity(): array
    {
        $storedCount = Result::withoutGlobalScopes()->where('vote_id', $this->id)->count();
        $expectedCount = $this->getExpectedResultCount();

        return [
            'is_valid' => $storedCount === $expectedCount && $this->verifyChecksum(),
            'stored_count' => $storedCount,
            'expected_count' => $expectedCount,
            'checksum_valid' => $this->verifyChecksum(),
        ];
    }

    /**
     * Synchronize results with vote data
     * Deletes all existing results and regenerates from JSON source of truth
     * Idempotent - safe to call multiple times
     *
     * @return void
     */
    public function syncResults(): void
    {
        // Delete existing results (idempotent operation)
        Result::where('vote_id', $this->id)->forceDelete();

        // Recreate from JSON source of truth
        $this->createResultsFromCandidates();

        // Update sync timestamp
        $this->update(['results_last_synced_at' => now()]);
    }
}
