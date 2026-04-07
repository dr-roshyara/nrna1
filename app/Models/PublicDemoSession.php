<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PublicDemoSession - Tracks anonymous visitor state through the 5-step demo flow
 *
 * Each browser session gets one record per demo election.
 * No user_id — identity is the session_token (Laravel session ID).
 *
 * Table: public_demo_sessions
 */
class PublicDemoSession extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'public_demo_sessions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'session_token',
        'election_id',
        'display_code',
        'current_step',
        'code_verified',
        'agreed',
        'candidate_selections',
        'has_voted',
        'voted_at',
        'expires_at',
    ];

    protected $casts = [
        'code_verified' => 'boolean',
        'agreed' => 'boolean',
        'has_voted' => 'boolean',
        'candidate_selections' => 'array',
        'voted_at' => 'datetime',
        'expires_at' => 'datetime',
        'current_step' => 'integer',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getRouteKeyName(): string
    {
        return 'session_token';
    }
}
