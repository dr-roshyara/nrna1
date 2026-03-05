<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

/**
 * DemoPost Model - Demo Election Posts
 *
 * Physical table separation from real posts for testing.
 * Same structure as Post but stored in demo_posts table.
 *
 * Table: demo_posts (separate from posts table)
 * Purpose: Testing voting workflows without affecting real election data
 *
 * Demo posts with multi-tenancy support:
 * - MODE 1: organisation_id = NULL (public demo, visible to all users)
 * - MODE 2: organisation_id = X (scoped to specific organisation)
 * - Can be reset/cleared without affecting real elections
 * - Associated with specific demo elections
 * - Includes both election_id and organisation_id for isolation
 */
class DemoPost extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $table = 'demo_posts';

    protected $fillable = [
        'post_id',
        'name',
        'nepali_name',
        'election_id',
        'organisation_id',
        'state_name',
        'position_order',
        'required_number',
        'is_national_wide',
    ];

    /**
     * Get demo candidacies for this post
     */
    public function candidacies()
    {
         return $this->hasMany(DemoCandidacy::class, 'post_id', 'id');
    }

    /**
     * Get the election this post belongs to
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Scope to eager load candidacies
     */
    public function scopeWithCandidacies($query)
    {
        return $query->with('candidacies');
    }

    /**
     * Scope to filter by election
     */
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /**
     * Check if this is a demo post (always true)
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Delete all demo posts older than N days
     */
    public static function cleanupOlderThan(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
