<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Vote;
use App\Models\Result;
use App\Models\Organisation;
use App\Traits\BelongsToTenant;

class Candidacy extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use BelongsToTenant;

    public $keyType = 'string';
    public $incrementing = false;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    protected $fillable = [
        'organisation_id',
        'post_id',
        'user_id',
        'name',
        'description',
        'position_order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
    /**
     * Each candidacy belongs to one election (through post)
     * Access via: $candidacy->post->election
     */

    /**
     * Each Candidacy  belongs to only  one user
     * Get the user
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id')
               ->select(['id', 'user_id', 'name', 'region', 'email', 'first_name', 'last_name']);
    }


     /**
      * One Candidacy belongs to One Post
      * Get the post
       */
      public function post(){
           return $this->belongsTo(Post::class, 'post_id', 'id');
       }
    
    /**
     * A candidacy has many votes through results table
     */
    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'results', 'candidate_id', 'vote_id')
                    ->withTimestamps();
    }

    /**
     * Get results for this candidacy
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'candidate_id', 'id');
    }
    //  herer 
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
        
        // Priority 4: Use name field from candidacy table (backup)
        if (!empty($this->name)) {
            return $this->name;
        }
        
        // Fallback: Generate from candidacy_id
        if (!empty($this->candidacy_id)) {
            return 'Candidate ' . str_replace(['_', '-'], ' ', $this->candidacy_id);
        }
        
        return 'Unknown Candidate';
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
            'candidacy_name' => $this->candidate_name,  // This uses the accessor above
            'proposer_name' => $this->proposer_name,
            'supporter_name' => $this->supporter_name,
            'image_path_1' => $this->image_path_1,
            'user_info' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? 'Unknown',
                'user_id' => $this->user->user_id ?? 'N/A',
                'region' => $this->user->region ?? 'N/A',
                'email' => $this->user->email ?? 'N/A',
            ]
        ];
    }

    /**
     * Scope: Filter by organisation
     */
    public function scopeForOrganisation($query, $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Filter by election (through post)
     */
    public function scopeForElectionId($query, $electionId)
    {
        return $query->whereHas('post', function($q) use ($electionId) {
            $q->where('election_id', $electionId);
        });
    }

    /**
     * Scope to eager load user relationship
     */
    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    /**
     * Scope: Get approved candidacies
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Get pending candidacies
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Get candidacies for a specific election
     */
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /**
     * Scope: Get candidacies for a specific post
     */
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Scope: Get candidacies with specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if candidacy is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if candidacy is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if candidacy is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if candidacy is withdrawn
     */
    public function isWithdrawn(): bool
    {
        return $this->status === self::STATUS_WITHDRAWN;
    }

    /**
     * Approve this candidacy
     */
    public function approve(): bool
    {
        return $this->update(['status' => self::STATUS_APPROVED]);
    }

    /**
     * Reject this candidacy
     */
    public function reject(): bool
    {
        return $this->update(['status' => self::STATUS_REJECTED]);
    }

    /**
     * Withdraw this candidacy
     */
    public function withdraw(): bool
    {
        return $this->update(['status' => self::STATUS_WITHDRAWN]);
    }

    /**
     * Get organisation relationship helper
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}