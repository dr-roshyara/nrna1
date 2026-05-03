<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Shared\Domain\Scopes\BelongsToTenant;

class ApplicationContextModel extends Model
{
    use SoftDeletes;

    protected $table = 'membership_applications';

    protected $fillable = [
        'id',
        'organisation_id',
        'member_id',
        'membership_type_id',
        'status',
        'rejection_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'member_id' => 'string',
        'membership_type_id' => 'string',
        'status' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new BelongsToTenant());
    }
}
