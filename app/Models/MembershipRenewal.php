<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipRenewal extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'organisation_id',
        'member_id',
        'membership_type_id',
        'renewed_by',
        'old_expires_at',
        'new_expires_at',
        'fee_id',
        'notes',
    ];

    protected $casts = [
        'old_expires_at' => 'datetime',
        'new_expires_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(MembershipFee::class, 'fee_id');
    }
}
