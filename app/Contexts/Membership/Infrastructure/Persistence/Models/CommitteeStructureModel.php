<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Persistence model — deliberately dumb.
 * Does NOT contain domain logic.
 * Repository handles mapping to/from domain aggregate.
 */
class CommitteeStructureModel extends Model
{
    protected $table = 'committee_structures';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'status',
        'version',
        'parent_structure_id',
    ];

    protected $casts = [
        'version' => 'integer',
        'organisation_id' => 'string',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(CommitteeStructureLevelModel::class, 'committee_structure_id');
    }
}
