<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent row for adjudication-process state (WP-2, EPIC-004K §11).
 *
 * Persistence only — the process's rules live in `AdjudicationProcessState`
 * (Application). Tenant scoping is an infrastructure concern, so the domain and
 * application layers stay tenant-free (ADR-T16).
 *
 * @property string $id
 * @property string $organisation_id
 * @property string $challenge_ref
 * @property string $status
 * @property list<string> $admitted_evidence
 * @property list<string>|null $considered_evidence
 * @property string|null $concluded_by_authority
 * @property string|null $outcome
 * @property string|null $legitimacy
 * @property string|null $reason
 * @property \DateTimeImmutable $opened_at
 * @property \DateTimeImmutable|null $concluded_at
 * @property string|null $contested_election_id
 * @property string|null $contested_type
 * @property string|null $contested_target_id
 * @property string|null $evidence_envelope_ref
 * @property string|null $jurisdiction
 * @property \DateTimeImmutable|null $issuance_requested_at
 */
final class AdjudicationProcessModel extends Model
{
    use BelongsToTenant;

    protected $table = 'adjudication_processes';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'organisation_id',
        'challenge_ref',
        'status',
        'admitted_evidence',
        'considered_evidence',
        'concluded_by_authority',
        'outcome',
        'legitimacy',
        'reason',
        'opened_at',
        'concluded_at',
        'contested_election_id',
        'contested_type',
        'contested_target_id',
        'evidence_envelope_ref',
        'jurisdiction',
        'issuance_requested_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'admitted_evidence' => 'array',
        'considered_evidence' => 'array',
        'opened_at' => 'immutable_datetime',
        'concluded_at' => 'immutable_datetime',
        'issuance_requested_at' => 'immutable_datetime',
    ];
}
