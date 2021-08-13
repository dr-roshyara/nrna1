<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeligateVote extends Model
{
    use HasFactory;
    public $member_keys =array(
        "member1_id",
     "member2_id",
     "member3_id",
     "member4_id",
     "member5_id",
     "member6_id",
     "member7_id",
     "member8_id",
     "member9_id",
     "member10_id",
     "member11_id",
     "member12_id",
     "member13_id",
     "member14_id",
     "member15_id",
     "member16_id",
     "member17_id",
     "member18_id",
     "member19_id",
     "member21_id",
     "member22_id",
     "member23_id",
     "member24_id",
     "member25_id",
     "member26_id",
     "member27_id",
     "member28_id",
     "member29_id",
     "member30_id",
     "member31_id",
     "member32_id",
     "member33_id",
     "member34_id",
     "member35_id");    
    
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
