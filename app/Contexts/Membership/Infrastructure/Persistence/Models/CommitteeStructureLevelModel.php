<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Persistence model for CommitteeLevel value object.
 * Deliberately dumb — no domain logic.
 */
class CommitteeStructureLevelModel extends Model
{
    protected $table = 'committee_structure_levels';

    protected $fillable = [
        'committee_structure_id',
        'level_index',
        'name',
        'geo_policy',
        'geo_scope',
        'role_limits',
        'min_membership_years',
        'age_range_min',
        'age_range_max',
        'gender_requirement',
    ];

    protected $casts = [
        'level_index' => 'integer',
        'min_membership_years' => 'integer',
        'age_range_min' => 'integer',
        'age_range_max' => 'integer',
        'role_limits' => 'array',
    ];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(CommitteeStructureModel::class, 'committee_structure_id');
    }
}
