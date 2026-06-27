<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

final class GovernanceDecisionSnapshotModel extends Model
{
    protected $table = 'governance_decisions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'decided_at',
        'capability_type',
        'winning_authority_id',
        'legitimacy',
        'constitutional_scope',
        'constitutional_reason_json',
        'arbitration_trace_json',
        'schema_version',
        'doctrine_version',
        'legitimacy_policy_version',
        'arbitration_policy_version',
        'replay_engine_version',
        'replay_compatibility_version',
        'metadata_generated_at',
        'integrity_hash',
    ];

    protected $casts = [
        'decided_at'          => 'datetime',
        'metadata_generated_at' => 'datetime',
    ];
}
