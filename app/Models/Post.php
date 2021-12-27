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

        return $this->hasMany(Candidacy::class, 'post_id', 'post_id');
    }
}
