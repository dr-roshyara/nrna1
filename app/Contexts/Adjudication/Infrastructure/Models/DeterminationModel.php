<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Eloquent persistence model for the Determination aggregate (Infrastructure
 * only — never referenced by Domain/Application). Tenant-scoped.
 *
 * @property string $id
 * @property string $organisation_id
 * @property string $challenge_ref
 * @property string $state
 * @property string $evidence_envelope_ref
 * @property string $issued_by_authority
 * @property string $jurisdiction
 * @property string|null $contested_election_id
 * @property string|null $contested_target_type
 * @property string|null $contested_target_id
 */
final class DeterminationModel extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'determinations';

    protected $keyType = 'string';

    public $incrementing = false;

    // Persists AGGREGATE STATE only. Ruling content (outcome/legitimacy/reason)
    // is carried by the DeterminationIssued event (outbox), not the write row,
    // because the frozen aggregate does not retain it.
    protected $fillable = [
        'id',
        'organisation_id',
        'challenge_ref',
        'state',
        'evidence_envelope_ref',
        'issued_by_authority',
        'jurisdiction',
        // ADR-UL-01 / ADR-PL-01: contested-outcome reference (nullable — rows
        // written before payload schema v2 have none).
        'contested_election_id',
        'contested_target_type',
        'contested_target_id',
    ];
}
