<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class VoterSlug extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'election_id',
        'user_id',
        'voter_id', // Three-tier hierarchy: VoterSlug belongs to Voter
        'slug',
        'expires_at',
        'is_active',
        'current_step',
        'step_meta',
        'has_voted',
        'can_vote_now',
        'voting_time_in_minutes',
        'status',
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
        'voting_time_in_minutes' => 'integer',
        'step_meta' => 'array',
        'step_1_completed_at' => 'datetime',
        'step_2_completed_at' => 'datetime',
        'step_3_completed_at' => 'datetime',
        'step_4_completed_at' => 'datetime',
        'step_5_completed_at' => 'datetime',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class)->withoutGlobalScopes();
    }

    public function election()
    {
        return $this->belongsTo(Election::class)->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }

    /**
     * Three-tier hierarchy: Get the voter this slug belongs to
     */
    public function voter()
    {
        return $this->belongsTo(Voter::class)->withoutGlobalScopes();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voter_slug_id', 'id');
    }

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()->where('organisation_id', $organisationId);
    }

    public function scopeForElection($query, $election)
    {
        $electionId = is_string($election) ? $election : $election->id;
        return $query->withoutGlobalScopes()->where('election_id', $electionId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVoted($query)
    {
        return $query->where('status', 'voted');
    }

    public function hasVoted(): bool
    {
        return $this->status === 'voted';
    }

    public function markAsVoted(): bool
    {
        return $this->update(['status' => 'voted']);
    }

    /**
     * ✅ NEW: Boot method - Auto-mark expired slugs as inactive
     *
     * BUSINESS GUARANTEE: When a slug is retrieved from the database,
     * if it has expired, it is immediately marked inactive to:
     * - Prevent stale sessions from blocking new voting
     * - Ensure fresh slugs are created when needed
     * - Maintain audit trail (status = 'expired')
     */
    protected static function booted()
    {
        static::retrieved(function ($slug) {
            // ✅ AUTO-CHECK when model is loaded from database
            if ($slug->expires_at && now()->greaterThan($slug->expires_at) && $slug->is_active) {
                // Use direct query update to persist immediately
                static::query()->where('id', $slug->id)->update([
                    'is_active' => false,
                    'can_vote_now' => false,
                    'status' => 'expired',
                    'updated_at' => now(),
                ]);

                // Update the in-memory instance to match
                $slug->is_active = false;
                $slug->can_vote_now = false;
                $slug->status = 'expired';

                \Illuminate\Support\Facades\Log::info('Auto-marked expired voter slug', [
                    'slug_id' => $slug->id,
                    'expires_at' => $slug->expires_at,
                    'marked_at' => now(),
                ]);
            }
        });

        static::creating(function ($slug) {
            // Ensure expires_at is set
            if (!$slug->expires_at) {
                $slug->expires_at = now()->addMinutes(
                    config('voting.slug_expiration_minutes', 30)
                );
            }

            // Ensure status is set
            if (!$slug->status) {
                $slug->status = 'active';
            }

            // Ensure is_active is set
            if (!isset($slug->is_active)) {
                $slug->is_active = true;
            }

            // Ensure can_vote_now is set
            if (!isset($slug->can_vote_now)) {
                $slug->can_vote_now = true;
            }
        });
    }
}
