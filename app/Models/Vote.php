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
     
    protected $hidden = ['user_id'];
      
    public function user(){ 
        return $this->belongsTo(User::Class)
        ->select(['id','name', 'region', 'user_id', 
        'nrna_id',  'has_voted']);
   } 
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
