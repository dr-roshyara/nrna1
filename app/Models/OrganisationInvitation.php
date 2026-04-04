<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganisationInvitation extends Model
{
    protected $fillable = [
        'organisation_id',
        'email',
        'role',
        'token',
        'status',
        'invited_by',
        'accepted_by',
        'expires_at',
        'accepted_at',
        'resend_count',
        'message',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'accepted_at'  => 'datetime',
        'resend_count' => 'integer',
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending' && ! $this->isExpired();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('expires_at', '>', now());
    }
}
