<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

/**
 * DemoVoterSlug Model - Demo Voter Slug Tracking
 *
 * Physical table separation from real voter slugs for testing.
 * Same structure as VoterSlug but stored in demo_voter_slugs table.
 *
 * Table: demo_voter_slugs (separate from voter_slugs table)
 * Purpose: Testing voter workflows and step tracking without affecting real voter data
 *
 * Demo voter slugs with multi-tenancy support:
 * - MODE 1: organisation_id = NULL (public demo, visible to all users)
 * - MODE 2: organisation_id = X (scoped to specific organisation)
 * - Can be reset/cleared without affecting real voter workflows
 * - Associated with specific demo elections
 * - Includes both election_id and organisation_id for isolation
 * - Tracks current step in the voting process
 * - Stores step metadata (IP addresses, timestamps, etc.)
 */
class DemoVoterSlug extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'demo_voter_slugs';

    protected $fillable = [
        'organisation_id',
        'user_id',
        'election_id',
        'slug',
        'expires_at',
        'is_active',
        'current_step',
        'step_meta',
        'has_voted',
        'can_vote_now',
        'voting_time_min',
        'step_1_ip',
        'step_1_completed_at',
        'step_2_ip',
        'step_2_completed_at',
        'step_3_ip',
        'step_3_completed_at',
        'step_4_ip',
        'step_4_completed_at',
        'step_5_ip',
        'step_5_completed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'has_voted' => 'boolean',
        'can_vote_now' => 'boolean',
        'current_step' => 'integer',
        'voting_time_min' => 'integer',
        'step_meta' => 'array',
        'step_1_completed_at' => 'datetime',
        'step_2_completed_at' => 'datetime',
        'step_3_completed_at' => 'datetime',
        'step_4_completed_at' => 'datetime',
        'step_5_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function steps()
    {
        return $this->hasMany(DemoVoterSlugStep::class, 'demo_voter_slug_id');
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

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Check if this is a demo voter slug (always true)
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Delete all demo voter slugs older than N days
     */
    public static function cleanupOlderThan(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Get the route key for implicit route model binding
     * Uses the 'slug' column instead of the default 'id'
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
