<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPayment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'member_id',
        'fee_id',
        'organisation_id',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'status',
        'recorded_by',
        'income_id',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the member this payment is for.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the membership fee this payment is for.
     */
    public function fee()
    {
        return $this->belongsTo(MembershipFee::class);
    }

    /**
     * Get the organisation this payment belongs to.
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the user who recorded this payment.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the income record created for this payment (if any).
     */
    public function income()
    {
        return $this->belongsTo(\App\Domain\Finance\Models\Income::class);
    }
}
