<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class VoterSlug extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'slug',
        'expires_at',
        'is_active',
        'current_step',
        'step_meta',
        'election_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'current_step' => 'integer',
        'step_meta' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(VoterSlugStep::class, 'voter_slug_id');
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }
}
