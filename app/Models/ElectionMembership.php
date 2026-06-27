<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * ElectionMembership — pivot between User, Organisation, and Election.
 *
 * Tracks who is eligible to vote in which election, with what role.
 *
 * Cache strategy: Option B (no tags — file-driver compatible).
 *   Cache::remember("election.{id}.voter_count", ttl, fn)
 *   Cache::forget("election.{id}.voter_count")  on every save/delete
 *
 * TODO: Create ElectionPolicy for policy-based authorization (separate task).
 *       Current route protection via EnsureCommitteeMember middleware.
 *
 * @see architecture/election/voter/20260317_2208_Voter_model.md
 */
class ElectionMembership extends Model
{
    use HasFactory, HasUuids, BelongsToTenant, SoftDeletes;

    protected $table = 'election_memberships';

    protected $fillable = [
        'user_id',
        'organisation_id',
        'election_id',
        'role',
        'status',
        'assigned_by',
        'assigned_at',
        'expires_at',
        'last_activity_at',
        'metadata',
        'has_voted',
        'voted_at',
        'suspension_status',
        'suspension_proposed_by',
        'suspension_proposed_at',
    ];

    protected $casts = [
        'assigned_at'      => 'datetime',
        'expires_at'       => 'datetime',
        'last_activity_at' => 'datetime',
        'metadata'         => 'array',
        'has_voted'               => 'boolean',
        'voted_at'                => 'datetime',
        'suspension_status'       => 'string',
        'suspension_proposed_at'  => 'datetime',
    ];

    protected $attributes = [
        'role'             => 'voter',
        'status'           => 'active',
        'metadata'         => '{}',
        'has_voted'        => false,
        'suspension_status' => 'none',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class)->withoutGlobalScopes();
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVoters($query)
    {
        return $query->where('role', 'voter');
    }

    public function scopeCandidates($query)
    {
        return $query->where('role', 'candidate');
    }

    public function scopeForElection($query, string $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    public function scopeEligible($query)
    {
        return $query->where('election_memberships.status', 'active')
            ->where(function ($q) {
                $q->whereNull('election_memberships.expires_at')
                  ->orWhere('election_memberships.expires_at', '>', now());
            })
            // Also enforce the member's own status (Issue 3 fix — member-level eligibility)
            ->whereExists(function ($sub) {
                $sub->select(\DB::raw(1))
                    ->from('organisation_users')
                    ->join('members', 'members.organisation_user_id', '=', 'organisation_users.id')
                    ->whereColumn('organisation_users.user_id', 'election_memberships.user_id')
                    ->whereColumn('organisation_users.organisation_id', 'election_memberships.organisation_id')
                    ->where('members.status', 'active')
                    ->where(function ($mq) {
                        $mq->whereNull('members.membership_expires_at')
                           ->orWhere('members.membership_expires_at', '>', now());
                    });
            });
    }

    public function scopeNotVoted($query)
    {
        return $query->where('has_voted', false);
    }


    // =========================================================================
    // Business logic
    // =========================================================================

    public function isEligible(): bool
    {
        return $this->status === 'active'
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function markAsVoted(): void
    {
        $this->update([
            'has_voted'        => true,
            'voted_at'         => now(),
            'status'           => 'inactive',
            'last_activity_at' => now(),
        ]);
    }

    public function remove(?string $reason = null, ?User $removedBy = null): void
    {
        $this->update([
            'status'   => 'removed',
            'metadata' => array_merge($this->metadata ?? [], [
                'removed_at'          => now()->toIso8601String(),
                'removed_reason'      => $reason,
                'removed_by'          => $removedBy?->id,
                'removed_by_email'    => $removedBy?->email,
            ]),
        ]);

        // Critical audit log when election is active — potential impact on live voting
        if ($this->election && $this->election->status === 'active') {
            Log::channel('voting_security')->critical('Voter removed from ACTIVE election', [
                'user_id'     => $this->user_id,
                'election_id' => $this->election_id,
                'reason'      => $reason,
                'removed_by'  => $removedBy?->email,
                'timestamp'   => now()->toIso8601String(),
            ]);
        }
    }

    // =========================================================================
    // Two-person suspension workflow
    // =========================================================================

    public function proposeSuspension(User $proposer): void
    {
        $this->update([
            'suspension_status'      => 'proposed',
            'suspension_proposed_by' => $proposer->name,
            'suspension_proposed_at' => now(),
        ]);
    }

    public function confirmSuspension(User $confirmer): void
    {
        $this->update([
            'status'            => 'inactive',
            'suspension_status' => 'confirmed',
        ]);
    }

    public function cancelSuspensionProposal(): void
    {
        $this->update([
            'suspension_status'      => 'none',
            'suspension_proposed_by' => null,
            'suspension_proposed_at' => null,
        ]);
    }

    public function isSuspensionProposed(): bool
    {
        return $this->suspension_status === 'proposed';
    }

    public function canConfirmSuspension(User $user): bool
    {
        return $this->suspension_status === 'proposed'
            && $this->suspension_proposed_by !== $user->name;
    }

    // =========================================================================
    // Cache invalidation (Option B — no tags)
    // =========================================================================

    protected static function booted(): void
    {
        $invalidate = function (self $membership) {
            Cache::forget("election.{$membership->election_id}.voter_count");
            Cache::forget("election.{$membership->election_id}.voter_stats");
            // Also clear per-user voter eligibility cache
            Cache::forget("user.{$membership->user_id}.voter.{$membership->election_id}");
        };

        $syncVoterCount = function (self $membership) {
            // Update the denormalized voters_count column when memberships change
            // Only count active voters (role='voter' and status='active')
            $count = DB::table('election_memberships')
                ->where('election_id', $membership->election_id)
                ->where('role', 'voter')
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->count();

            DB::table('elections')
                ->where('id', $membership->election_id)
                ->update(['voters_count' => $count]);
        };

        static::saved($invalidate);
        static::saved($syncVoterCount);
        static::deleted($invalidate);
        static::deleted($syncVoterCount);
    }
}
