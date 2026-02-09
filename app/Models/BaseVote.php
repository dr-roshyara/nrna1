<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * BaseVote Abstract Class
 *
 * Contains all shared voting logic for both real and demo votes.
 * Subclasses (Vote, DemoVote) specify their respective tables.
 *
 * This follows the DRY principle - voting business logic defined once,
 * used by both real and demo voting implementations.
 *
 * Table assignment happens in concrete subclasses:
 * - Vote extends BaseVote → votes table
 * - DemoVote extends BaseVote → demo_votes table
 */
abstract class BaseVote extends Model
{
    use HasFactory;

    /**
     * All candidate columns (candidate_01 through candidate_60)
     * These are mass-assignable on all vote types.
     *
     * NOTE: No 'user_id' - votes are anonymous by design.
     *
     * @var array
     */
    protected $fillable = [
        'election_id',
        'voting_code',
        'no_vote_option',
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
     * All candidate fields are JSON in the database, cast to array for easier handling.
     *
     * @var array
     */
    protected $casts = [
        'no_vote_option' => 'boolean',
    ];

    /**
     * Get the user who cast this vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all posts associated with this vote (polymorphic)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'votable');
    }

    /**
     * Get all candidacies associated with this vote (polymorphic)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphedByMany
     */
    public function candidacies()
    {
        return $this->morphedByMany(Candidacy::class, 'votable');
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
     * Check if this vote was submitted by a specific user
     *
     * @param int $userId
     * @return bool
     */
    public function isSubmittedBy(int $userId): bool
    {
        return $this->user_id == $userId;
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
     * Check if voter selected the no-vote option
     *
     * @return bool
     */
    public function selectedNoVoteOption(): bool
    {
        return (bool) $this->no_vote_option;
    }

    /**
     * Scope: Get votes for a specific user
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
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
