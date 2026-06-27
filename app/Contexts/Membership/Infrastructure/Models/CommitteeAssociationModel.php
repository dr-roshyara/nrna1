<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

final class CommitteeAssociationModel extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'committee_associations';

    protected $fillable = [
        'id',
        'association_id',
        'lineage_id',
        'organisation_id',
        'member_id',
        'committee_id',
        'association_type',
        'status',
        'associated_at',
        'approved_by_actor_id',
        'approved_by_actor_type',
        'suspended_by_actor_id',
        'suspended_by_actor_type',
        'suspension_reason',
        'terminated_by_actor_id',
        'terminated_by_actor_type',
        'termination_reason',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'member_id' => 'string',
        'committee_id' => 'string',
        'associated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'string',
        'association_type' => 'string',
    ];

    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function newFactory()
    {
        return \Database\Factories\CommitteeAssociationModelFactory::new();
    }
}
