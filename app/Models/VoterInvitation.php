<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoterInvitation extends Model
{
    use HasFactory, HasUuids;

    // Email status constants
    public const EMAIL_PENDING = 'pending';
    public const EMAIL_SENT = 'sent';
    public const EMAIL_FAILED = 'failed';

    protected $fillable = [
        'election_id',
        'user_id',
        'organisation_id',
        'token',
        'email_status',
        'email_error',
        'sent_at',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    public function scopePending($query)
    {
        return $query->whereNull('used_at')
            ->where('expires_at', '>', now());
    }

    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeEmailSent($query)
    {
        return $query->where('email_status', self::EMAIL_SENT);
    }

    public function scopeEmailFailed($query)
    {
        return $query->where('email_status', self::EMAIL_FAILED);
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function scopeForOrganisation($query, $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }
}
