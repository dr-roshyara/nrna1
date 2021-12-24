<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidacy extends Model
{
    use HasFactory;
     use HasFactory;
    protected $fillable =['name','user_id','user_name','candidacy_id','candidacy_name', 'proposer_name', 'proposer_id',
    'supporter_id','supporter_name', 'post_id','post_nepali_name','post_name', 'image_path_1', 
    'image_path_2', 'image_path_3'];
      /**
     * Each Candidacy  belongs to only  one user 
     * Get the user 
     */
     public function user(){
           return $this->belongsTo(User::class);
       }
     /**
      * One Candidacy belongs to One Post 
      * Get the post 
       */
      public function post(){ 
           return $this->belongsTo(Post::class);
       }
       /**
        * A candidacy has many votes. Also A Vote is for many posts so 
        * Its better to build a many to many relationship  
        */
        /**
        *  Get all of the tags for the post.
        */
        public function votes() 
        {
            return $this->morphToMany(Vote::class, 'votable');
        }
    //  herer 
}