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
     
      
     protected $fillable = [
        'voting_code',
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
