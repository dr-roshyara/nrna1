<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
    use HasUuids;
    use BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'demo_candidacies';

    protected $fillable = [
        'post_id',
        'organisation_id',
        'user_id',
        'name',
        'description',
        'position_order',
    ];

    protected $casts = [
        // post_id is UUID (string), not integer
    ];

    /**
     * Each DemoCandidacy belongs to one user
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class)
               ->select(['id', 'name', 'region', 'email']);
    }

    /**
     * One DemoCandidacy belongs to One Post
     * Get the post
     */
    public function post()
    {
        return $this->belongsTo(DemoPost::class, 'post_id', 'id');
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

        // Priority 2: Use name field from candidacy table (backup)
        if (!empty($this->name)) {
            return $this->name;
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
            'candidacy_id' => $this->id,
            'candidacy_name' => $this->candidate_name,
            'proposer_name' => $this->proposer_name,
            'supporter_name' => $this->supporter_name,
            'image_path_1' => $this->image_path_1,
            'user_info' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? 'Demo Candidate',
                'user_id' => $this->user->id ?? 'DEMO',
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
