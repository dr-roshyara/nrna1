<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent read/write model for the greenfield reaction-state ledger
 * (`election_applied_determinations`). Tenant isolation is provided by the app-wide
 * {@see BelongsToTenant} trait (global scope + auto-fill of `organisation_id` from
 * TenantContext) — the domain stays tenant-free (ADR-T16).
 *
 * @property string $organisation_id
 * @property string $election_id
 * @property string $determination_id
 */
final class ElectionAppliedDeterminationModel extends Model
{
    use BelongsToTenant;

    protected $table = 'election_applied_determinations';

    /** @var list<string> */
    protected $fillable = ['organisation_id', 'election_id', 'determination_id'];
}
