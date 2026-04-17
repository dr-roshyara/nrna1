<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'incomes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'organisation_id',
        'country',
        'committee_name',
        'period_from',
        'period_to',
        'membership_fee',
        'nomination_fee',
        'sponser_fee',
        'deligate_fee',
        'donation',
        'levy',
        'event_fee',
        'event_contribution',
        'event_income',
        'interest_income',
        'business_income',
        'deligate_contribution',
        'other_incomes',
        'source_type',
        'source_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'period_from' => 'datetime',
        'period_to' => 'datetime',
        'membership_fee' => 'decimal:2',
        'nomination_fee' => 'decimal:2',
        'sponser_fee' => 'decimal:2',
        'deligate_fee' => 'decimal:2',
        'donation' => 'decimal:2',
        'levy' => 'decimal:2',
        'event_fee' => 'decimal:2',
        'event_contribution' => 'decimal:2',
        'event_income' => 'decimal:2',
        'interest_income' => 'decimal:2',
        'business_income' => 'decimal:2',
        'deligate_contribution' => 'decimal:2',
        'other_incomes' => 'decimal:2',
    ];

    /**
     * Get the organisation that this income belongs to.
     */
    public function organisation()
    {
        return $this->belongsTo(\App\Models\Organisation::class);
    }

    /**
     * Get the user who recorded this income.
     */
    public function recordedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the membership payment linked to this income (if any).
     */
    public function membershipPayment()
    {
        return $this->hasOne(\App\Models\MembershipPayment::class, 'income_id');
    }
}
