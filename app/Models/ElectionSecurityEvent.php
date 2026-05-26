<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionSecurityEvent extends Model
{
    public $timestamps = false;

    protected $table = 'election_security_events';

    protected $attributes = [
        'retention_days' => 730,
    ];

    protected $fillable = [
        'event_type',
        'election_id',
        'voter_slug_id',
        'network_evidence',
        'device_evidence',
        'trust_level_before',
        'trust_level_after',
        'policy_evaluated',
        'overlay_applied',
        'policy_evaluation_sequence',
        'overlay_influence_chain',
        'trust_state_transition',
        'final_constitutional_outcome',
        'retention_days',
        'recorded_at',
    ];

    protected $casts = [
        'network_evidence' => 'array',
        'device_evidence' => 'array',
        'policy_evaluation_sequence' => 'array',
        'overlay_influence_chain' => 'array',
        'recorded_at' => 'immutable_datetime',
        'retention_days' => 'integer',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    // Append-only enforcement: prevent updates on existing records
    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new \LogicException('ElectionSecurityEvent is append-only. Cannot update existing records.');
        }

        return parent::save($options);
    }

    // Prevent deletion
    public function delete()
    {
        throw new \LogicException('ElectionSecurityEvent is append-only. Cannot delete records.');
    }

    public function forceDelete()
    {
        throw new \LogicException('ElectionSecurityEvent is append-only. Cannot force delete records.');
    }
}
