<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    use HasFactory;
    use BelongsToTenant;

    /**
     * All candidate columns (candidate_01 through candidate_60)
     * These are mass-assignable on all vote types.
     *
     * CRITICAL: No 'user_id' in database - votes are completely anonymous!
     * vote_hash provides cryptographic proof of vote without exposing identity.
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id',
        'election_id',
        'vote_hash',  // SHA256 cryptographic proof
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
        'no_vote_posts' => 'array',
        'metadata' => 'array',
        'cast_at' => 'datetime',
    ];

    /**
     * Hide sensitive fields from API responses
     *
     * @var array
     */
    protected $hidden = [
        'vote_hash', // Never expose the cryptographic proof in API
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

            // Ensure vote_hash is provided (cryptographic proof)
            if (is_null($vote->vote_hash)) {
                \Log::channel('voting_security')->warning('Vote rejected: NULL vote_hash', [
                    'reason' => 'Cryptographic proof is required',
                    'election_id' => $vote->election_id,
                    'timestamp' => now(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    'Votes require a cryptographic proof (vote_hash cannot be NULL)',
                    ['reason' => 'null_vote_hash']
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
                'vote_hash_prefix' => substr($vote->vote_hash, 0, 10) . '...',
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

        return hash_equals($this->vote_hash, $expectedHash);
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
            'vote_hash_prefix' => substr($this->vote_hash, 0, 8) . '...',
            'can_verify' => true,
        ];
    }

    /**
     * Get the election this vote belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
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
        return $query->where('election_id', $election->id);
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
}
