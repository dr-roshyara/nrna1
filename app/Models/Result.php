<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'post_id',
        'candidacy_id'
    ];

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function candidacy()
    {
        return $this->belongsTo(Candidacy::class, 'candidacy_id', 'candidacy_id');
    }
}
