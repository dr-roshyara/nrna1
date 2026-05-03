<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Shared\Domain\Scopes\BelongsToTenant;

class MemberContextModel extends Model
{
    use SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'id',
        'organisation_id',
        'personal_info',
        'membership_type_id',
        'status',
        'fees_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'membership_type_id' => 'string',
        'personal_info' => 'json',
        'status' => 'string',
        'fees_status' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new BelongsToTenant());
    }

    public function getOrganisationIdAttribute($value)
    {
        return $value;
    }
}
