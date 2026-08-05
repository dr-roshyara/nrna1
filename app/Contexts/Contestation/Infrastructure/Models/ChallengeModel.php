<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the Challenge aggregate (`challenges`). The table carries the FULL
 * aggregate shape; raise-time columns are nullable and are populated by a later backlog
 * item (this reaction slice writes only state/determination). Tenant isolation via the
 * app-wide {@see BelongsToTenant} trait — the domain stays tenant-free (ADR-T16).
 *
 * @property string $id
 * @property string $organisation_id
 * @property string $state
 * @property string|null $determination_id
 */
final class ChallengeModel extends Model
{
    use BelongsToTenant;

    protected $table = 'challenges';

    protected $keyType = 'string';

    public $incrementing = false;

    /** @var list<string> */
    protected $fillable = [
        'id',
        'organisation_id',
        'state',
        'determination_id',
        'raiser_standing_ref',
        'contested_election_id',
        'contested_target_type',
        'contested_target_id',
        'submitted_content',
    ];
}
