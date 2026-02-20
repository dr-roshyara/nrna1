<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

/**
 * DemoCandidacy Model - Demo Election Candidates
 *
 * Physical table separation from real candidacies for testing.
 * Same structure as Candidacy but stored in demo_candidacies table.
 *
 * Table: demo_candidacies (separate from candidacies table)
 * Purpose: Testing voting workflows without affecting real election data
 *
 * Demo candidacies with multi-tenancy support:
 * - MODE 1: organisation_id = NULL (public demo, visible to all users)
 * - MODE 2: organisation_id = X (scoped to specific organisation)
 * - Can be reset/cleared without affecting real elections
 * - Allow same user to be candidate in both demo and real elections
 * - Safe for testing candidacy workflows
 */
class DemoCandidacy extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $table = 'demo_candidacies';

    protected $fillable = [
        'user_id',
        'user_name',
        'candidacy_id',
        'candidacy_name',
        'proposer_name',
        'proposer_id',
        'supporter_id',
        'supporter_name',
        'post_id',
        'post_nepali_name',
        'post_name',
        'image_path_1',
        'image_path_2',
        'image_path_3',
        'election_id',
        'organisation_id',  // ✅ Added for MODE 2 demo voting
        'position_order'
    ];

    /**
     * Each DemoCandidacy belongs to one user
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
               ->select(['id', 'user_id', 'name', 'region', 'email', 'first_name', 'last_name']);
    }

    /**
     * One DemoCandidacy belongs to One Post
     * Get the post
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    /**
     * A demo candidacy has many demo votes. Polymorphic relationship
     */
    public function votes()
    {
        return $this->morphToMany(DemoVote::class, 'votable');
    }

    /**
     * Get the election this candidate belongs to
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the candidate's name from User table
     * This is the main method to get the candidate's actual name
     *
     * @return string
     */
    public function getCandidateNameAttribute()
    {
        // Priority 1: Get name from related User
        if ($this->user && !empty($this->user->name)) {
            return $this->user->name;
        }

        // Priority 2: Construct from first_name + last_name if available
        if ($this->user && (!empty($this->user->first_name) || !empty($this->user->last_name))) {
            $fullName = trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''));
            if (!empty($fullName)) {
                return $fullName;
            }
        }

        // Priority 3: Use user_name field from candidacy table (backup)
        if (!empty($this->user_name)) {
            return $this->user_name;
        }

        // Priority 4: Use candidacy_id
        if (!empty($this->candidacy_id)) {
            return 'Demo Candidate ' . str_replace(['_', '-'], ' ', $this->candidacy_id);
        }

        return 'Demo Candidate';
    }

    /**
     * Get complete candidate information for vote display
     *
     * @return array
     */
    public function getVoteDisplayInfo()
    {
        return [
            'candidacy_id' => $this->candidacy_id,
            'candidacy_name' => $this->candidate_name,
            'proposer_name' => $this->proposer_name,
            'supporter_name' => $this->supporter_name,
            'image_path_1' => $this->image_path_1,
            'user_info' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? 'Demo Candidate',
                'user_id' => $this->user->user_id ?? 'DEMO',
                'region' => $this->user->region ?? 'Demo Region',
                'email' => $this->user->email ?? 'demo@example.com',
            ]
        ];
    }

    /**
     * Scope to eager load user relationship
     */
    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    /**
     * Scope to filter by election
     */
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /**
     * Check if this is a demo candidate (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Delete all demo candidates older than N days
     * Useful for cleanup of old test data
     *
     * @param int $days
     * @return int
     */
    public static function cleanupOlderThan(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
