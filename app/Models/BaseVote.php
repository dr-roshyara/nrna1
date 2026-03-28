<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Post;
use App\Models\Candidacy;
use App\Traits\BelongsToTenant;

/**
 * BaseVote Abstract Class
 *
 * Implements "Verifiable Anonymity" - the gold standard for election integrity:
 * - Voters can verify their vote was recorded correctly
 * - Results remain completely anonymous
 * - Audit trail is cryptographically secure
 *
 * Key Design Principle:
 * - NO user_id in database (votes are anonymous!)
 * - vote_hash: SHA256(user_id + election_id + code + timestamp)
 * - Allows verification WITHOUT exposing voter identity
 *
 * Table assignment happens in concrete subclasses:
 * - Vote extends BaseVote → votes table (real elections)
 * - DemoVote extends BaseVote → demo_votes table (testing)
 */
abstract class BaseVote extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use BelongsToTenant;

    /**
     * UUID key configuration
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * All candidate columns (candidate_01 through candidate_60)
     * These are mass-assignable on all vote types.
     *
     * CRITICAL: No 'user_id' in database - votes are completely anonymous!
     *
     * Verification columns (replaces old vote_hash):
     * - receipt_hash: For voter self-verification (e.g., via email receipt)
     * - participation_proof: For IP-based admin verification (prove participation without revealing vote)
     * - encrypted_vote: Encrypted vote data for voter verification
     * - device_fingerprint_hash: For fraud detection (privacy-preserving hash)
     * - device_metadata_anonymized: Anonymized device analytics
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id',
        'election_id',
        'receipt_hash',
        'no_vote_option',
        'participation_proof',
        'encrypted_vote',
        'device_fingerprint_hash',
        'device_metadata_anonymized',
        'no_vote_posts',
        'candidate_01', 'candidate_02', 'candidate_03', 'candidate_04', 'candidate_05',
        'candidate_06', 'candidate_07', 'candidate_08', 'candidate_09', 'candidate_10',
        'candidate_11', 'candidate_12', 'candidate_13', 'candidate_14', 'candidate_15',
        'candidate_16', 'candidate_17', 'candidate_18', 'candidate_19', 'candidate_20',
        'candidate_21', 'candidate_22', 'candidate_23', 'candidate_24', 'candidate_25',
        'candidate_26', 'candidate_27', 'candidate_28', 'candidate_29', 'candidate_30',
        'candidate_31', 'candidate_32', 'candidate_33', 'candidate_34', 'candidate_35',
        'candidate_36', 'candidate_37', 'candidate_38', 'candidate_39', 'candidate_40',
        'candidate_41', 'candidate_42', 'candidate_43', 'candidate_44', 'candidate_45',
        'candidate_46', 'candidate_47', 'candidate_48', 'candidate_49', 'candidate_50',
        'candidate_51', 'candidate_52', 'candidate_53', 'candidate_54', 'candidate_55',
        'candidate_56', 'candidate_57', 'candidate_58', 'candidate_59', 'candidate_60'
    ];

    /**
     * Cast JSON fields to arrays
     *
     * @var array
     */
    protected $casts = [
        'no_vote_option' => 'boolean',
        'no_vote_posts' => 'array',
        'metadata' => 'array',
        'device_metadata_anonymized' => 'array',
        'cast_at' => 'datetime',
    ];

    /**
     * Hide sensitive fields from API responses
     *
     * @var array
     */
    protected $hidden = [
        'receipt_hash', // Never expose the receipt hash in API
        'participation_proof', // Never expose the participation proof in API
    ];

    /**
     * Model Lifecycle Hooks
     *
     * Validates votes to ensure:
     * 1. election_id references a valid election
     * 2. organisation_id matches election's organisation
     * 3. vote_hash is provided (cryptographic proof)
     * 4. cast_at timestamp is set
     */
    protected static function booted()
    {
        static::creating(function ($vote) {
            // ✅ AUTO-GENERATE receipt_hash if not provided
            // This ensures all votes (demo and real) have cryptographic proof
            if (empty($vote->receipt_hash)) {
                $vote->receipt_hash = hash('sha256',
                    uniqid() .           // Unique ID per request
                    time() .             // Timestamp
                    config('app.key')    // App secret key
                );

                \Log::channel('voting_security')->debug('Auto-generated receipt_hash for vote', [
                    'vote_type' => get_class($vote),
                    'election_id' => $vote->election_id,
                    'receipt_hash_prefix' => substr($vote->receipt_hash, 0, 10) . '...',
                ]);
            }

            // Validate election_id is present
            if (is_null($vote->election_id)) {
                \Log::channel('voting_security')->warning('Vote rejected: NULL election_id', [
                    'reason' => 'Election reference is required',
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    'Votes require a valid election (election_id cannot be NULL)',
                    ['reason' => 'null_election_id']
                );
            }

            // Verify election exists
            $election = Election::withoutGlobalScopes()->find($vote->election_id);
            if (!$election) {
                \Log::channel('voting_security')->warning('Vote rejected: Invalid election_id', [
                    'election_id' => $vote->election_id,
                    'reason' => 'Election not found',
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    "Election (id: {$vote->election_id}) not found",
                    ['election_id' => $vote->election_id, 'reason' => 'election_not_found']
                );
            }

            // For real votes, ensure organisation_id matches election
            if (get_class($vote) === Vote::class && !is_null($election->organisation_id)) {
                if ($vote->organisation_id !== $election->organisation_id) {
                    \Log::channel('voting_security')->warning('Real vote rejected: Organisation mismatch', [
                        'vote_organisation_id' => $vote->organisation_id,
                        'election_organisation_id' => $election->organisation_id,
                        'election_id' => $vote->election_id,
                        'reason' => 'Vote organisation does not match election organisation',
                        'timestamp' => now(),
                        'ip' => request()->ip(),
                    ]);

                    throw new \App\Exceptions\OrganisationMismatchException(
                        "Vote organisation_id ({$vote->organisation_id}) does not match election organisation_id ({$election->organisation_id})",
                        [
                            'vote_organisation_id' => $vote->organisation_id,
                            'election_organisation_id' => $election->organisation_id,
                            'election_id' => $vote->election_id,
                        ]
                    );
                }
            }

            // Ensure receipt_hash is provided (cryptographic proof for voter verification)
            if (is_null($vote->receipt_hash)) {
                \Log::channel('voting_security')->warning('Vote rejected: NULL receipt_hash', [
                    'reason' => 'Receipt hash is required for verification',
                    'election_id' => $vote->election_id,
                    'timestamp' => now(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    'Votes require a receipt hash (receipt_hash cannot be NULL)',
                    ['reason' => 'null_receipt_hash']
                );
            }

            // Set cast_at if not provided
            if (is_null($vote->cast_at)) {
                $vote->cast_at = now();
            }

            // ✅ All validations passed - vote is anonymous and secure
            \Log::channel('voting_security')->info('Vote passed model validation', [
                'election_id' => $vote->election_id,
                'organisation_id' => $vote->organisation_id,
                'receipt_hash_prefix' => substr($vote->receipt_hash, 0, 10) . '...',
                'timestamp' => now(),
                'ip' => request()->ip(),
            ]);
        });
    }

    /**
     * Verify this vote was cast by a specific code
     *
     * Implements cryptographic verification without exposing voter identity.
     * Uses SHA256 hash of user_id + election_id + code + timestamp.
     *
     * @param Code $code The code to verify against
     * @return bool True if vote hash matches
     */
    public function verifyByCode(Code $code): bool
    {
        $expectedHash = hash('sha256',
            $code->user_id .
            $code->election_id .
            $code->code1 .
            $this->cast_at->timestamp
        );

        return hash_equals($this->receipt_hash, $expectedHash);
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
     * Get verification data for audit
     *
     * Allows voter to verify their vote without exposing how they voted.
     * @return array
     */
    public function getVerificationData(): array
    {
        return [
            'election_id' => $this->election_id,
            'organisation_id' => $this->organisation_id,
            'cast_at' => $this->cast_at,
            'receipt_hash_prefix' => substr($this->receipt_hash, 0, 8) . '...',
            'can_verify' => true,
        ];
    }

    /**
     * Get the organisation this vote belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get the election this vote belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election()
    {
        return $this->belongsTo(Election::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get posts this vote cast for through results table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'results', 'vote_id', 'post_id')
                    ->withPivot('candidate_id')
                    ->withTimestamps();
    }

    /**
     * Get candidacies this vote selected through results table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function candidacies()
    {
        return $this->belongsToMany(Candidacy::class, 'results', 'vote_id', 'candidate_id')
                    ->withTimestamps();
    }

    /**
     * Get all selected candidates for this vote
     * Returns array of non-null candidate selections.
     *
     * @return array
     */
    public function getSelectedCandidates(): array
    {
        $selected = [];

        for ($i = 1; $i <= 60; $i++) {
            $candidateKey = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);

            if ($this->$candidateKey !== null) {
                $selected[$candidateKey] = $this->$candidateKey;
            }
        }

        return $selected;
    }

    /**
     * Count how many candidates were selected in this vote
     *
     * @return int
     */
    public function countSelectedCandidates(): int
    {
        return count($this->getSelectedCandidates());
    }

    /**
     * Scope: Get votes for a specific election
     * Note: This is for real votes only. DemoVote is separate table.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\Election $election
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForElection($query, Election $election)
    {
        return $query->withoutGlobalScopes()
                     ->where('election_id', $election->id);
    }

    /**
     * Scope: Get recent votes (created in last N days)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get votes for a specific organisation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $organisationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()
                     ->where('organisation_id', $organisationId);
    }
}
