<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use  Illuminate\Support\Facades\DB;
class Code extends Model
{
    use HasFactory;
    protected $guard= [];
    
    /**
     * 
     * 
     * Each code row belongs to exactly one user. So belongs to relationship  
     *
     * 
     **/
    public function user(){ 
         return $this->belongsTo(User::Class)
         ->select(['id','name', 'region', 'user_id', 
         'nrna_id', 'can_vote_now', 'has_voted']);
    }    
    

}
