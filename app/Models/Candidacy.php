<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidacy extends Model
{
    use HasFactory;
     use HasFactory;
    protected $fillable =[
        'user_id','user_name','candidacy_id','candidacy_name', 
        'proposer_name', 'proposer_id', 'supporter_id','supporter_name', 
        'post_id','post_nepali_name','post_name', 'image_path_1', 
        'image_path_2', 'image_path_3'
    ];
    /**
     * Each Candidacy  belongs to only  one user 
     * Get the user 
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id')
               ->select(['id', 'user_id', 'name', 'region', 'email', 'first_name', 'last_name']);
    }


     /**
      * One Candidacy belongs to One Post 
      * Get the post 
       */
      public function post(){ 
           return $this->belongsTo(Post::class, 'post_id','post_id');
       }
    
    /**
     * A candidacy has many votes. Also A Vote is for many posts so 
     * Its better to build a many to many relationship  
     */
    public function votes() 
    {
        return $this->morphToMany(Vote::class, 'votable');
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
            'candidacy_id' => $this->candidacy_id,
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
}