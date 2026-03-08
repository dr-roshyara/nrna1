<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

/**
 * VoterSlugStep Model
 *
 * Records when each step of the voting process is completed.
 * This is the source of truth for voter progress.
 *
 * Table: voter_slug_steps
 * Purpose: Audit trail + step routing
 */
class VoterSlugStep extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToTenant;

    protected $table = 'voter_slug_steps';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'voter_slug_id',
        'slug',
        'election_id',
        'step',
        'step_data',
        'completed_at',
    ];

    protected $casts = [
        'step_data' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationship: This step belongs to a voter slug
     */
    public function voterSlug()
    {
        return $this->belongsTo(VoterSlug::class, 'voter_slug_id');
    }

    /**
     * Relationship: This step belongs to an election
     */
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    /**
     * Scope: Get steps for a specific voter in an election
     */
    public function scopeForVoterInElection($query, $voterSlugId, $electionId)
    {
        return $query->where('voter_slug_id', $voterSlugId)
                     ->where('election_id', $electionId);
    }

    /**
     * Scope: Get completed steps in order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('step', 'asc');
    }
}
