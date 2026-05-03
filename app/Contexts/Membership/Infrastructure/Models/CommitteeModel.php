<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class CommitteeModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'committees';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'code',
        'type',
        'level',
        'operational_geo_reference',
        'operational_geo_cache',
        'geo_cache_version',
        'geo_cache_updated_at',
        'parent_committee_id',
        'formation_date',
        'term_end_date',
        'status',
        'max_members',
    ];

    protected $casts = [
        'operational_geo_cache' => 'json',
        'formation_date' => 'date',
        'term_end_date' => 'date',
        'geo_cache_updated_at' => 'datetime',
    ];
}
