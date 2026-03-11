<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;
use App\Models\Election;

/**
 * BaseResult Abstract Class
 *
 * Contains all shared result logic for both real and demo election results.
 * Subclasses (Result, DemoResult) specify their respective tables.
 *
 * This follows the DRY principle - result aggregation logic defined once,
 * used by both real and demo result implementations.
 *
 * Table assignment happens in concrete subclasses:
 * - Result extends BaseResult → results table
 * - DemoResult extends BaseResult → demo_results table
 */
abstract class BaseResult extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use BelongsToTenant;

    /**
     * UUID key configuration
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * All attributes that are mass-assignable
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id',
        'election_id',
        'vote_id',
        'post_id',
        'candidacy_id',  // UUID FK to candidacies table — NULL for no-vote rows
        'vote_count',    // For aggregation
        'position_order',
        'no_vote',       // true when voter chose to abstain for this post
    ];

    protected $casts = [
        'no_vote' => 'boolean',
    ];

    /**
     * Model Lifecycle Hooks
     *
     * Phase 2: Model-Level Validation (Soft Boundary)
     * Validates results to enforce organisation_id consistency with parent vote
     *
     * CRITICAL RULES FOR REAL RESULTS:
     * 1. organisation_id MUST NOT be NULL
     * 2. organisation_id MUST match parent vote's organisation_id
     * 3. vote_id MUST reference a valid vote
     */
    protected static function booted()
    {
        static::creating(function ($result) {
            // Skip validation for demo results (they have separate validation)
            // Check if this is a Result instance (real) vs DemoResult instance (demo)
            if (get_class($result) !== Result::class) {
                return;
            }

            // CRITICAL 1: organisation_id MUST NOT be null for real results
            if (is_null($result->organisation_id)) {
                \Log::channel('voting_security')->warning('Real result rejected: NULL organisation_id', [
                    'reason' => 'Organisation context is required for real results',
                    'vote_id' => $result->vote_id,
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    'Real results require a valid organisation context (organisation_id cannot be NULL)',
                    ['reason' => 'null_organisation_id', 'vote_id' => $result->vote_id]
                );
            }

            // CRITICAL 2: vote_id MUST reference a valid vote
            if (is_null($result->vote_id)) {
                \Log::channel('voting_security')->warning('Real result rejected: NULL vote_id', [
                    'reason' => 'Vote reference is required',
                    'organisation_id' => $result->organisation_id,
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    'Real results require a valid vote (vote_id cannot be NULL)',
                    ['reason' => 'null_vote_id', 'organisation_id' => $result->organisation_id]
                );
            }

            // CRITICAL 3: vote must belong to the SAME organisation
            $vote = Vote::withoutGlobalScopes()->find($result->vote_id);

            if (!$vote) {
                \Log::channel('voting_security')->warning('Real result rejected: Invalid vote_id', [
                    'vote_id' => $result->vote_id,
                    'organisation_id' => $result->organisation_id,
                    'reason' => 'Vote not found',
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\InvalidRealVoteException(
                    "Vote (id: {$result->vote_id}) not found",
                    ['vote_id' => $result->vote_id, 'reason' => 'vote_not_found']
                );
            }

            // Verify vote organisation_id matches
            if ($vote->organisation_id !== $result->organisation_id) {
                \Log::channel('voting_security')->warning('Real result rejected: Organisation mismatch', [
                    'result_organisation_id' => $result->organisation_id,
                    'vote_organisation_id' => $vote->organisation_id,
                    'vote_id' => $result->vote_id,
                    'reason' => 'Result organisation does not match vote organisation',
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                throw new \App\Exceptions\OrganisationMismatchException(
                    "Result organisation_id ({$result->organisation_id}) does not match vote organisation_id ({$vote->organisation_id})",
                    [
                        'result_organisation_id' => $result->organisation_id,
                        'vote_organisation_id' => $vote->organisation_id,
                        'vote_id' => $result->vote_id,
                    ]
                );
            }

            // ✅ All validations passed - log success
            \Log::channel('voting_security')->info('Real result passed model validation', [
                'vote_id' => $result->vote_id,
                'organisation_id' => $result->organisation_id,
                'post_id' => $result->post_id,
                'candidate_id' => $result->candidate_id,
                'timestamp' => now(),
                'ip' => request()->ip(),
            ]);
        });
    }

    /**
     * Get the organisation this result belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get the vote this result is from
     * Note: Uses morph mapping to determine if it's Vote or DemoVote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract public function vote();

    /**
     * Get the post this result is for
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get the candidacy this result is for
     * Note: candidacy_id can be NULL for no-vote/abstention results
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidacy()
    {
        return $this->belongsTo(Candidacy::class, 'candidacy_id', 'id')
                    ->withoutGlobalScopes();
    }

    /**
     * Alias for candidacy() - get the candidate this result is for
     * Note: Deprecated - use candidacy() instead
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate()
    {
        return $this->candidacy();
    }

    /**
     * Get the election this result belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election()
    {
        return $this->belongsTo(Election::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Scope: Get results for a specific organisation
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

    /**
     * Scope: Get results for a specific election
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
     * Scope: Get results for a specific post
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $postId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPost($query, string $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Scope: Get results for a specific candidacy
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $candidacyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCandidacy($query, string $candidacyId)
    {
        return $query->where('candidacy_id', $candidacyId);
    }

    /**
     * Scope: Get results for a specific vote (alias for compatibility)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $voteId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForVote($query, string $voteId)
    {
        return $query->where('vote_id', $voteId);
    }

    /**
     * Get vote count for a specific candidacy
     *
     * @param string $candidacyId
     * @return int
     */
    public static function countForCandidacy(string $candidacyId): int
    {
        return static::forCandidacy($candidacyId)->count();
    }

    /**
     * Get vote count for a specific post
     *
     * @param string $postId
     * @return int
     */
    public static function countForPost(string $postId): int
    {
        return static::forPost($postId)->count();
    }

    /**
     * Get top N candidacies for a post (by vote count)
     *
     * @param string $postId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function topCandidaciesForPost(string $postId, int $limit = 10)
    {
        return static::forPost($postId)
            ->selectRaw('candidacy_id, COUNT(*) as vote_count')
            ->groupBy('candidacy_id')
            ->orderByDesc('vote_count')
            ->limit($limit)
            ->get();
    }
}
