<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Projections;

use Illuminate\Database\Eloquent\Model;

// Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
final class CommitteeGovernanceProjectionModel extends Model
{
    protected $table = 'committee_governance_projections';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'committee_id';

    protected $fillable = [
        'committee_id',
        'projection_version',
        'last_event_id',
        'last_event_occurred_at',
        'operational_state',
        'temporal_state',
        'legitimacy',
        'can_act',
        'is_fully_operational',
        'evaluated_at',
        'rebuilt_at',
        'projection_generation',
        'projection_schema_version',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'last_event_occurred_at' => 'datetime',
    ];
}
