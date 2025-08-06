<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;

class Vote extends Model
{
    use HasFactory; 
    /**
     * Each vote belongs to only  one user 
     */ 
     
      
     protected $fillable = ['voting_code']; // Only what you need
    
    /**
     * Get all of the posts that are assigned this vote.
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'votable');
    }
    /**
     * Get all of the candidates that are assigned this vote.
     */
    public function candidacies()
    {
        return $this->morphedByMany(Candidacy::class, 'votable');
    }
   
   
}
