<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidacyApplication extends Model
{
    use HasFactory, HasUuids;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id', 'organisation_id', 'election_id', 'post_id', 'candidacy_id',
        'supporter_name', 'proposer_name', 'manifesto', 'documents', 'photo',
        'status', 'rejection_reason', 'reviewed_at', 'reviewed_by',
    ];

    protected $casts = [
        'documents'   => 'array',
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function user()         { return $this->belongsTo(User::class); }
    public function organisation() { return $this->belongsTo(Organisation::class); }
    public function election()     { return $this->belongsTo(Election::class); }
    public function post()         { return $this->belongsTo(Post::class)->withoutGlobalScopes(); }
    public function reviewer()     { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function candidacy()    { return $this->belongsTo(Candidacy::class)->withoutGlobalScopes(); }
}
