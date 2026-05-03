<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class FeeContextModel extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $table = 'membership_fees';

    protected $fillable = [
        'id',
        'organisation_id',
        'member_id',
        'membership_type_id',
        'amount',
        'currency',
        'fee_amount_at_time',
        'currency_at_time',
        'period_label',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_reference',
        'idempotency_key',
        'recorded_by',
        'notes',
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
}
