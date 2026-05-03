<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Shared\Domain\Scopes\BelongsToTenant;

class FeeContextModel extends Model
{
    use SoftDeletes;

    protected $table = 'membership_fees';

    protected $fillable = [
        'id',
        'organisation_id',
        'member_id',
        'amount',
        'currency',
        'status',
        'due_date',
        'paid_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'organisation_id' => 'string',
        'member_id' => 'string',
        'amount' => 'decimal:2',
        'status' => 'string',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(): void
    {
        static::addGlobalScope(new BelongsToTenant());
    }
}
