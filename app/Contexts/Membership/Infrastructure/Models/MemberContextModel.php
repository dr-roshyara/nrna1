<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class MemberContextModel extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'members';

    protected $fillable = [
        'id',
        'organisation_id',
        'organisation_user_id',
        'membership_type_id',
        'status',
        'fees_status',
        'fee_state',
        'residence_geo_unit_id',
        'personal_info',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'organisation_user_id' => 'string',
        'membership_type_id' => 'string',
        'status' => 'string',
        'fees_status' => 'string',
        'fee_state' => 'string',
        'residence_geo_unit_id' => 'integer',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
