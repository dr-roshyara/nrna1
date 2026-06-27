<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class ApplicationContextModel extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'membership_applications';

    protected $fillable = [
        'id',
        'organisation_id',
        'user_id',
        'membership_type_id',
        'status',
        'rejection_reason',
        'application_data',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'user_id' => 'string',
        'membership_type_id' => 'string',
        'status' => 'string',
        'application_data' => 'json',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
