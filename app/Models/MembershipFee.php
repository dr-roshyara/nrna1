<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipFee extends Model
{
    use HasUuids;

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
        'due_date',
        'paid_at',
        'status',
        'payment_method',
        'payment_reference',
        'idempotency_key',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'amount'             => 'decimal:2',
        'fee_amount_at_time' => 'decimal:2',
        'due_date'           => 'date',
        'paid_at'            => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Fees past their due date that have not yet been paid or waived.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'pending')
                     ->whereNotNull('due_date')
                     ->where('due_date', '<', now());
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
