<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use  Illuminate\Support\Facades\DB;
class Code extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id',
        'code1',
        'code2', 
        'code3',
        'code4',
        'vote_show_code',
        'is_code1_usable',
        'is_code2_usable', 
        'is_code3_usable',
        'is_code4_usable',
        'code1_sent_at',
        'code2_sent_at',
        'code3_sent_at', 
        'code4_sent_at',
        'can_vote_now',
        'has_voted',
        'voting_time_in_minutes',
        'vote_last_seen',
        'code1_used_at',
        'code2_used_at', 
        'code3_used_at',
        'code4_used_at',
        'code_for_vote',
        'vote_submitted',
        'vote_submitted_at',
        'has_code1_sent',
        'has_code2_sent',
        'client_ip',
        'has_agreed_to_vote',
        'has_used_code1'.
        'has_used_code2' 
        // Add any other fields you need to mass assign
    ];

    protected $casts = [
        'has_code1_sent' => 'boolean',
        'is_code1_usable' => 'boolean', 
        'is_code2_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'code1_sent_at' => 'datetime',
        'code2_sent_at' => 'datetime',
        'code1_used_at' => 'datetime',
        'code2_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
        'voting_started_at' => 'datetime',
    ]; 
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
         'nrna_id',  'has_voted']);
    }    
    

}
