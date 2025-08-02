<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Candidacy;
class Post extends Model
{
    use HasFactory;

    public function candidates(){
        //return $this->hasMany(Comment::class, 'foreign_key');
        //return $this->hasMany(Comment::class, 'foreign_key', 'local_key');

        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')
                     ->select(['candidacy_id','user_id', 'post_id','image_path_1']);
    }

     /**
     * Get all candidacies for this post (each candidacy is a candidate for this post).
     */
    public function candidacies()
    {
        // post_id is the foreign key in candidacies, post_id is the primary key in posts
        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')->select([ 'id','candidacy_id','user_id', 'post_id','image_path_1']);
    }

}
