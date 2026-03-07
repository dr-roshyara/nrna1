<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Voter extends Model
{
    use HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'member_id',
        'election_id',
        'status',
        'ineligibility_reason',
        'has_voted',
        'voted_at',
        'voter_number',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'has_voted' => 'boolean',
        'voted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Upward relationships (identity hierarchy)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Election context
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Downward relationships (election activities - voter is central hub)
     */
    public function codes()
    {
        return $this->hasMany(Code::class, 'voter_id');
    }

    public function voterSlug()
    {
        return $this->hasOne(VoterSlug::class, 'voter_id');
    }

    public function vote()
    {
        return $this->hasOne(Vote::class, 'voter_id');
    }

    /**
     * Business logic
     */
    public function canVote(): bool
    {
        return $this->status === 'eligible'
            && !$this->has_voted
            && $this->election->status === 'active';
    }

    public function markAsVoted(): void
    {
        $this->update([
            'status' => 'voted',
            'has_voted' => true,
            'voted_at' => now(),
        ]);
    }
}
