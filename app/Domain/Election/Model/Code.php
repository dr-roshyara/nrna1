<?php
// app/Domain/Election/Models/Code.php  

namespace App\Domain\Election\Models;

use App\Models\ElectionAwareModel;

class Code extends ElectionAwareModel
{
    protected $fillable = [
        'session_name',
        'user_id',
        'code1',
        'code2', 
        'vote_show_code',
        'is_code1_usable',
        'code1_sent_at',
        'is_code2_usable',
        'code2_sent_at',
        'can_vote',
        'can_vote_now',
        'has_voted',
        'voting_time_in_minutes',
        'vote_last_seen',
        'vote_completed_at',
        'code1_used_at',
        'code2_used_at',
        'vote_submitted',
        'vote_submitted_at',
        'has_code1_sent',
        'has_code2_sent',
        'client_ip',
        'has_agreed_to_vote',
        'has_agreed_to_vote_at',
        'voting_started_at',
        'has_used_code1',
        'has_used_code2',
        'is_codemodel_valid'
    ];
    
    protected $casts = [
        'is_code1_usable' => 'boolean',
        'is_code2_usable' => 'boolean',
        'can_vote' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'has_code1_sent' => 'boolean',
        'has_code2_sent' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'has_used_code1' => 'boolean',
        'has_used_code2' => 'boolean',
        'is_codemodel_valid' => 'boolean',
        'vote_last_seen' => 'date',
        'code1_sent_at' => 'datetime',
        'code2_sent_at' => 'datetime',
        'vote_completed_at' => 'datetime',
        'code1_used_at' => 'datetime',
        'code2_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
        'voting_started_at' => 'datetime'
    ];
    
    public function electionUser()
    {
        return $this->belongsTo(ElectionUser::class, 'user_id');
    }
}
