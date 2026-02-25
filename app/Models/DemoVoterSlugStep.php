<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

/**
 * DemoVoterSlugStep Model - Demo Voter Step Tracking
 *
 * Records when each step of the demo voting process is completed.
 * This is the source of truth for demo voter progress.
 *
 * Table: demo_voter_slug_steps
 * Purpose: Audit trail + step routing for demo voters
 */
class DemoVoterSlugStep extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $table = 'demo_voter_slug_steps';

    protected $fillable = [
        'organisation_id',
        'demo_voter_slug_id',
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
     * Relationship: This step belongs to a demo voter slug
     */
    public function demoVoterSlug()
    {
        return $this->belongsTo(DemoVoterSlug::class, 'demo_voter_slug_id');
    }

    /**
     * Relationship: This step belongs to an election
     */
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    /**
     * Scope: Get steps for a specific demo voter in an election
     */
    public function scopeForVoterInElection($query, $demoVoterSlugId, $electionId)
    {
        return $query->where('demo_voter_slug_id', $demoVoterSlugId)
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
