<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeligateCandidacy extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id','nrna_id','name','post_id','image_path_1','description'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
     /**
      * One Candidacy belongs to One Post 
      * Get the post 
       */
      public function deligatepost(){ 
        return $this->belongsTo(DeligatePost::class);
    }
    /**
     * A candidacy has many votes. Also A Vote is for many posts so 
     * Its better to build a many to many relationship  
     */
     /**
     *  Get all of the tags for the post.
     */
     public function deligatevotes() 
     {
     return $this->morphToMany(Vote::class, 'deligatevotable');
     }
 //  herer 
}
