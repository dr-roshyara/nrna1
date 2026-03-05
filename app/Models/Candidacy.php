<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;
use App\Models\Result;
use App\Models\Organisation;
use App\Traits\BelongsToTenant;

class Candidacy extends Model
{
    use HasFactory;
    use BelongsToTenant;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    protected $fillable =[
        'election_id',
        'user_id', 'user_name', 'candidacy_id', 'candidacy_name',
        'proposer_name', 'proposer_id', 'supporter_id', 'supporter_name',
        'post_id', 'post_nepali_name', 'post_name', 'image_path_1',
        'image_path_2', 'image_path_3', 'position_order',
        'organisation_id', 'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];
    /**
     * Each candidacy belongs to one election
     * Get the election
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

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