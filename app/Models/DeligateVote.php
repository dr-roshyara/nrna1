<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeligateVote extends Model
{
    use HasFactory;

    /**
     * Each vote belongs to only  one user 
     */ 
    public function user(){
           return $this->belongsTo(User::class);
       }

    /**
     * Get all of the posts that are assigned this vote.
     */
    public function deligateposts()
    {
        return $this->morphedByMany(DeligatePost::class, 'deligatevotable');
    }
    /**
     * Get all of the candidates that are assigned this vote.
     */
    public function deligatecandidacies()
    {
        return $this->morphedByMany(DeligateCandidacy::class, 'deligatevotable');
    }
   
}
