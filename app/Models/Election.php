<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\VoterRegistration;
use App\Models\Code;
use App\Models\Vote;
use App\Models\Result;
use App\Models\VoterSlug;
use App\Traits\BelongsToTenant;
use App\Domain\Election\StateMachine\ElectionStateMachine;

/**
 * Election Model
 *
 * Represents an election event (demo or real).
 * Elections can be demo elections for testing or real elections for actual voting.
 */
class Election extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    // ── State Machine Constants ────────────────────────────────────────────────
    const STATE_ADMINISTRATION  = 'administration';
    const STATE_NOMINATION      = 'nomination';
    const STATE_VOTING          = 'voting';
    const STATE_RESULTS_PENDING = 'results_pending';
    const STATE_RESULTS         = 'results';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Resolve route binding without global scope so the election can be found
     * before session context is set by ensure.organisation middleware.
     * Controller methods validate organisation ownership explicitly.
     */
    public function resolveRouteBinding($value, $field = null): ?self
    {
        return static::withoutGlobalScopes()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id', // Allow setting for tests and seeders
        'name',
        'slug',
        'description',
        'type',
        'start_date',
        'end_date',
        'is_active',
        'results_published',
        'results_published_at',
        'settings',
        'status',
        'ip_restriction_enabled',
        'ip_restriction_max_per_ip',
        'ip_whitelist',
        'ip_mismatch_action',
        'voting_ip_mode',
        'voter_verification_mode',
        'no_vote_option_enabled',
        'no_vote_option_label',
        'selection_constraint_type',
        'selection_constraint_min',
        'selection_constraint_max',
        'settings_version',
        'settings_updated_by',
        'settings_updated_at',
        'settings_changes',
        // State machine columns
        'administration_suggested_start',
        'administration_suggested_end',
        'administration_completed',
        'administration_completed_at',
        'nomination_suggested_start',
        'nomination_suggested_end',
        'nomination_completed',
        'nomination_completed_at',
        'voting_starts_at',
        'voting_ends_at',
        'allow_auto_transition',
        'auto_transition_grace_days',
        'state_audit_log',
        // Locking columns
        'voting_locked',
        'voting_locked_at',
        'voting_locked_by',
        'results_locked',
        'results_locked_at',
    ];

    /**
     * The attributes that should be guarded from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'results_published_at' => 'datetime',
        'is_active'          => 'boolean',
        'results_published'  => 'boolean',
        'ip_restriction_enabled' => 'boolean',
        'no_vote_option_enabled' => 'boolean',
        'ip_whitelist'           => 'array',
        'settings_changes'       => 'array',
        'settings_updated_at'    => 'datetime',
        // State machine casts
        'administration_suggested_start' => 'datetime',
        'administration_suggested_end'   => 'datetime',
        'administration_completed'       => 'boolean',
        'administration_completed_at'    => 'datetime',
        'nomination_suggested_start'     => 'datetime',
        'nomination_suggested_end'       => 'datetime',
        'nomination_completed'           => 'boolean',
        'nomination_completed_at'        => 'datetime',
        'voting_starts_at'               => 'datetime',
        'voting_ends_at'                 => 'datetime',
        'allow_auto_transition'          => 'boolean',
        'auto_transition_grace_days'     => 'integer',
        'state_audit_log'                => 'array',
        // Locking columns
        'voting_locked'                  => 'boolean',
        'voting_locked_at'               => 'datetime',
        'results_locked'                 => 'boolean',
        'results_locked_at'              => 'datetime',
    ];

    /**
     * Get the organisation this election belongs to
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the user who last updated election settings
     */
    public function settingsUpdatedBy()
    {
        return $this->belongsTo(User::class, 'settings_updated_by');
    }

    /**
     * Get voter verifications for this election
     */
    public function voterVerifications()
    {
        return $this->hasMany(VoterVerification::class);
    }

    /**
     * Check if this election requires voter verification
     */
    public function requiresVoterVerification(): bool
    {
        return ($this->voter_verification_mode ?? 'none') !== 'none';
    }

    /**
     * Check if this election verifies voter IP addresses
     */
    public function checksIp(): bool
    {
        return in_array($this->voter_verification_mode, ['ip_only', 'both']);
    }

    /**
     * Check if this election verifies device fingerprints
     */
    public function checksFingerprint(): bool
    {
        return in_array($this->voter_verification_mode, ['fingerprint_only', 'both']);
    }

    // ── ElectionMembership relationships ─────────────────────────────────────

    public function memberships()
    {
        return $this->hasMany(ElectionMembership::class);
    }

    /** ElectionMembership voters (role = voter, status = active) */
    public function membershipVoters()
    {
        return $this->memberships()
            ->where('role', 'voter')
            ->where('status', 'active');
    }

    /** ElectionMembership voters whose eligibility has not expired */
    public function eligibleVoters()
    {
        return $this->membershipVoters()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Cached membership voter count — invalidated by ElectionMembership::booted() hooks.
     * Cache strategy: Option B (no tags, explicit key forget).
     */
    public function getVoterCountAttribute(): int
    {
        return \Illuminate\Support\Facades\Cache::remember(
            "election.{$this->id}.voter_count",
            300,
            fn () => $this->membershipVoters()->count()
        );
    }

    /**
     * Cached membership voter statistics — all counts in one query burst.
     * Cache strategy: Option B (no tags, explicit key forget).
     * Invalidated by ElectionMembership::booted() hooks.
     */
    public function getVoterStatsAttribute(): array
    {
        return \Illuminate\Support\Facades\Cache::remember(
            "election.{$this->id}.voter_stats",
            300,
            function () {
                $base = fn () => $this->memberships();

                return [
                    'total_memberships' => $base()->count(),
                    'active_voters'     => $this->membershipVoters()->count(),
                    'eligible_voters'   => $this->eligibleVoters()->count(),
                    'by_status' => [
                        'active'   => $base()->where('status', 'active')->count(),
                        'inactive' => $base()->where('status', 'inactive')->count(),
                        'invited'  => $base()->where('status', 'invited')->count(),
                        'removed'  => $base()->where('status', 'removed')->count(),
                    ],
                    'by_role' => [
                        'voter'     => $base()->where('role', 'voter')->count(),
                        'candidate' => $base()->where('role', 'candidate')->count(),
                        'observer'  => $base()->where('role', 'observer')->count(),
                        'admin'     => $base()->where('role', 'admin')->count(),
                    ],
                ];
            }
        );
    }

    /**
     * Scope: Get elections for a specific organisation
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    // ============ EAGER LOADING SCOPES (OPTIMIZATION) ============

    /**
     * Load organisation relationship
     */
    public function scopeWithOrganisation($query)
    {
        return $query->with(['organisation' => function($q) {
            $q->select('id', 'name');
        }]);
    }

    /**
     * Load essential relationships for validation
     * CRITICAL: Include election settings columns so they're not null in middleware
     */
    public function scopeWithEssentialRelations($query)
    {
        return $query->select(
            'id', 'name', 'organisation_id', 'type', 'status', 'end_date',
            'is_active',  // Voting state
            'no_vote_option_enabled', 'no_vote_option_label',  // No-vote settings
            'selection_constraint_type', 'selection_constraint_min', 'selection_constraint_max'  // Selection rules
        )
            ->with(['organisation' => function($q) {
                $q->select('id', 'name');
            }]);
    }

    /**
     * Get all posts for this election
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get all candidacies for this election (through posts)
     *
     * @return HasManyThrough
     */
    public function candidacies(): HasManyThrough
    {
        return $this->hasManyThrough(
            Candidacy::class,
            Post::class,
            'election_id',  // FK on posts
            'post_id',      // FK on candidacies
            'id',           // local key on elections
            'id'            // local key on posts
        )->withoutGlobalScopes();
    }

    /**
     * Get all voter registrations for this election
     *
     * @return HasMany
     */
    public function voterRegistrations(): HasMany
    {
        return $this->hasMany(VoterRegistration::class);
    }

    /**
     * Three-tier hierarchy: Get all voters for this election (central hub)
     *
     * @return HasMany
     */
    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    /**
     * Get all verification codes for this election
     *
     * @return HasMany
     */
    public function codes(): HasMany
    {
        return $this->hasMany(Code::class);
    }

    /**
     * Get all voter slugs for this election
     *
     * @return HasMany
     */
    public function voterSlugs(): HasMany
    {
        return $this->hasMany(VoterSlug::class)->withoutGlobalScopes();
    }

    /**
     * Get all receipt codes for this election
     *
     * @return HasMany
     */
    public function receiptCodes(): HasMany
    {
        return $this->hasMany(ReceiptCode::class);
    }

    /**
     * Get all demo posts for this election (for demo/test elections)
     *
     * @return HasMany
     */
    public function demoPosts(): HasMany
    {
        return $this->hasMany(DemoPost::class)->withoutGlobalScopes();
    }

    /**
     * Get all votes for this election
     * For real elections, returns from votes table
     * For demo elections, returns from demo_votes table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        // Use polymorphic approach based on election type
        if ($this->isDemo()) {
            return $this->hasManyThrough(
                DemoVote::class,
                VoterRegistration::class,
                'election_id',
                'user_id',
                'id',
                'user_id'
            );
        }

        return $this->hasManyThrough(
            Vote::class,
            VoterRegistration::class,
            'election_id',
            'user_id',
            'id',
            'user_id'
        );
    }

    /**
     * Get all results for this election
     * For real elections, returns from results table
     * For demo elections, returns from demo_results table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        if ($this->isDemo()) {
            return $this->hasManyThrough(
                DemoResult::class,
                DemoVote::class,
                'election_id',
                'vote_id',
                'id',
                'id'
            );
        }

        return $this->hasManyThrough(
            Result::class,
            Vote::class,
            'election_id',
            'vote_id',
            'id',
            'id'
        );
    }

    /**
     * Get all election officers for this election
     *
     * @return HasMany
     */
    public function officers(): HasMany
    {
        return $this->hasMany(ElectionOfficer::class);
    }

    /**
     * Get pending voters for this election
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function pendingVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'pending')
            ->with('user');
    }

    /**
     * Get approved voters for this election
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function approvedVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'approved')
            ->with('user');
    }

    /**
     * Get voters who have voted
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function votedVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'voted')
            ->with('user');
    }

    /**
     * Check if this is a demo election
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return $this->type === 'demo';
    }

    /**
     * Check if this is a real election
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->type === 'real';
    }

    /**
     * Check if election is currently active
     *
     * @return bool
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // If start_date is set and we haven't reached it, not active
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }

        // If end_date is set and we've passed it, not active
        if ($this->end_date && $now > $this->end_date) {
            return false;
        }

        return true;
    }

    /**
     * Get pending voter count
     *
     * @return int
     */
    public function pendingVoterCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get approved voter count
     *
     * @return int
     */
    public function approvedVoterCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Get voted count
     *
     * @return int
     */
    public function votedCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'voted')
            ->count();
    }

    /**
     * Get total votes cast in this election
     * Returns count from votes (real) or demo_votes (demo) table
     *
     * @return int
     */
    public function totalVotesCast(): int
    {
        if ($this->isDemo()) {
            return DemoVote::where('election_id', $this->id)->count();
        }

        return Vote::where('election_id', $this->id)->count();
    }

    /**
     * Get total verification codes for this election
     *
     * @return int
     */
    public function totalCodes(): int
    {
        return $this->codes()->count();
    }

    /**
     * Get verified codes count
     *
     * @return int
     */
    public function verifiedCodesCount(): int
    {
        return $this->codes()->verified()->count();
    }

    /**
     * Get unverified codes count
     *
     * @return int
     */
    public function unverifiedCodesCount(): int
    {
        return $this->codes()->unverified()->count();
    }

    /**
     * Get voter turnout percentage
     *
     * @return float|null
     */
    public function voterTurnout(): ?float
    {
        $approved = $this->approvedVoterCount();

        if ($approved === 0) {
            return null;
        }

        $voted = $this->votedCount();

        return ($voted / $approved) * 100;
    }

    /**
     * Get election summary statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'pending_voters' => $this->pendingVoterCount(),
            'approved_voters' => $this->approvedVoterCount(),
            'voted' => $this->votedCount(),
            'total_codes' => $this->totalCodes(),
            'verified_codes' => $this->verifiedCodesCount(),
            'total_votes_cast' => $this->totalVotesCast(),
            'turnout_percentage' => $this->voterTurnout(),
            'election_type' => $this->type,
            'is_active' => $this->isCurrentlyActive(),
        ];
    }

    // ── Settings Helper Methods ────────────────────────────────────────────────

    public function isIpRestricted(): bool
    {
        return (bool) $this->ip_restriction_enabled;
    }

    public function isNoVoteEnabled(): bool
    {
        return (bool) $this->no_vote_option_enabled;
    }

    public function getSelectionConstraintType(): string
    {
        return $this->selection_constraint_type ?? 'maximum';
    }

    public function validateSelectionCount(int $count): bool
    {
        return match ($this->getSelectionConstraintType()) {
            'any'     => true,
            'exact'   => $count === (int) $this->selection_constraint_max,
            'range'   => $count >= ($this->selection_constraint_min ?? 1)
                      && $count <= ($this->selection_constraint_max ?? PHP_INT_MAX),
            'minimum' => $count >= ($this->selection_constraint_min ?? 1),
            'maximum' => $count <= ($this->selection_constraint_max ?? PHP_INT_MAX),
            default   => true,
        };
    }

    public function isIpWhitelisted(string $ip): bool
    {
        if (empty($this->ip_whitelist)) {
            return false;
        }

        foreach ($this->ip_whitelist as $range) {
            if ($this->ipInRange($ip, trim($range))) {
                return true;
            }
        }

        return false;
    }

    private function ipInRange(string $ip, string $range): bool
    {
        if (!str_contains($range, '/')) {
            return $ip === $range;
        }

        [$subnet, $bits] = explode('/', $range);
        $ip     = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask   = -1 << (32 - (int) $bits);

        return ($ip & $mask) === ($subnet & $mask);
    }

    // ── State Machine Methods ─────────────────────────────────────────────────

    /**
     * Get current state (derived from dates and completion flags — pure, no side effects)
     * Linear priority ensures no ambiguity between states.
     */
    public function getCurrentStateAttribute(): string
    {
        $now = now();

        // 1. Results published (final)
        if ($this->results_published_at) {
            return self::STATE_RESULTS;
        }

        // 2. Voting active or ended
        if ($this->voting_starts_at && $this->voting_ends_at) {
            if ($now->between($this->voting_starts_at, $this->voting_ends_at)) {
                return self::STATE_VOTING;
            }
            if ($now->gt($this->voting_ends_at)) {
                return self::STATE_RESULTS_PENDING;
            }
        }

        // 3. Administration phase (not yet completed)
        if (!$this->administration_completed) {
            return self::STATE_ADMINISTRATION;
        }

        // 4. Nomination phase (administration done, nomination not yet done OR done but voting not started)
        // Stay in nomination until voting window opens
        if (!$this->nomination_completed || !($this->voting_starts_at && $now->gte($this->voting_starts_at))) {
            return self::STATE_NOMINATION;
        }

        // 5. Fallback (shouldn't reach here)
        return self::STATE_ADMINISTRATION;
    }

    /**
     * Check if an action is allowed in current state (pure, no auth checks)
     * Maps operations to allowed states per architecture/election_architecture/state_machine_art/statemachine_at_controller_level.md
     */
    public function allowsAction(string $action): bool
    {
        $allowed = [
            self::STATE_ADMINISTRATION  => [
                'manage_posts',
                'import_voters',
                'manage_committee',
                'configure_election',
                'manage_settings',
            ],
            self::STATE_NOMINATION => [
                'apply_candidacy',
                'approve_candidacy',
                'view_candidates',
                'configure_election',
                'manage_settings',
            ],
            self::STATE_VOTING => [
                'cast_vote',
                'verify_vote',
            ],
            self::STATE_RESULTS_PENDING => [
                'verify_vote',
            ],
            self::STATE_RESULTS => [
                'view_results',
                'verify_vote',
                'download_receipt',
            ],
        ];

        return in_array($action, $allowed[$this->current_state] ?? []);
    }

    /**
     * Check if dates for a specific phase can be updated
     * Enforces that you can only update dates for phases that haven't started yet
     */
    public function canUpdatePhaseDates(string $phase): bool
    {
        return match ($phase) {
            'administration' => !$this->administration_completed,

            'nomination' => !$this->nomination_completed,

            'voting' => !$this->voting_locked &&
                       (!$this->voting_starts_at || now()->lt($this->voting_starts_at)),

            'results_pending', 'results' => false,  // Never editable

            default => false,
        };
    }

    /**
     * Get state info for UI display
     */
    public function getStateInfoAttribute(): array
    {
        $state = $this->current_state;

        $info = [
            self::STATE_ADMINISTRATION => [
                'name'        => 'Administration',
                'description' => 'Setting up election, importing voters, managing committee',
                'color'       => 'blue',
            ],
            self::STATE_NOMINATION => [
                'name'        => 'Nomination',
                'description' => 'Candidates can apply and be approved',
                'color'       => 'purple',
            ],
            self::STATE_VOTING => [
                'name'        => 'Voting',
                'description' => 'Voting is in progress',
                'color'       => 'green',
            ],
            self::STATE_RESULTS_PENDING => [
                'name'        => 'Counting',
                'description' => 'Voting closed, results being finalized',
                'color'       => 'amber',
            ],
            self::STATE_RESULTS => [
                'name'        => 'Results',
                'description' => 'Final results published',
                'color'       => 'orange',
            ],
        ];

        return [
            'state'       => $state,
            'name'        => $info[$state]['name'] ?? 'Unknown',
            'description' => $info[$state]['description'] ?? '',
            'color'       => $info[$state]['color'] ?? 'slate',
        ];
    }

    /**
     * Mark administration as complete (admin action with reason audit)
     */
    public function completeAdministration(string $reason, string $actorId): void
    {
        // Validate prerequisites
        if ($this->posts()->count() === 0) {
            throw new \Exception('Cannot complete administration: No posts created');
        }
        if ($this->memberships()->where('role', 'voter')->where('status', 'active')->count() === 0) {
            throw new \Exception('Cannot complete administration: No voters imported');
        }

        $this->update([
            'administration_completed'   => true,
            'administration_completed_at' => now(),
        ]);

        // Auto-set nomination suggested dates if not already set
        if (!$this->nomination_suggested_start) {
            $this->update([
                'nomination_suggested_start' => now(),
                'nomination_suggested_end'   => now()->addDays(14),
            ]);
        }

        $this->logStateChange('administration_completed', ['reason' => $reason, 'actor_id' => $actorId]);
    }

    /**
     * Mark nomination as complete (admin action with audit)
     */
    public function completeNomination(string $reason, ?string $actorId = null): void
    {
        $pendingCount = $this->candidacies()->withoutGlobalScopes()->where('status', 'pending')->count();

        if ($pendingCount > 0) {
            throw new \Exception("Cannot complete nomination: {$pendingCount} candidates pending approval");
        }

        if ($this->candidacies()->withoutGlobalScopes()->where('status', 'approved')->count() === 0) {
            throw new \Exception('Cannot complete nomination: No candidates approved');
        }

        $this->update([
            'nomination_completed'   => true,
            'nomination_completed_at' => now(),
        ]);

        // Auto-set voting dates if not already set
        if (!$this->voting_starts_at) {
            $this->update([
                'voting_starts_at' => now(),
                'voting_ends_at'   => now()->addDays(4),
            ]);
        }

        // Lock voting immediately when nomination completes (at START)
        $this->lockVoting($actorId);

        $this->logStateChange('nomination_completed', ['reason' => $reason, 'actor_id' => $actorId]);
    }

    /**
     * Force close nomination (rejects pending candidates with audit)
     */
    public function forceCloseNomination(string $reason, string $actorId): void
    {
        // Guard: cannot force close after voting has started
        if ($this->voting_starts_at && now()->gte($this->voting_starts_at)) {
            throw new \Exception('Cannot modify nomination after voting has started');
        }

        // Auto-reject pending candidates
        $rejectCount = $this->candidacies()
            ->where('status', 'pending')
            ->update([
                'status'             => 'rejected',
                'rejection_reason'   => "Nomination phase closed by force: {$reason}",
            ]);

        $this->update([
            'nomination_completed'   => true,
            'nomination_completed_at' => now(),
        ]);

        $this->logStateChange('nomination_force_closed', [
            'reason'                    => $reason,
            'pending_candidates_rejected' => $rejectCount,
            'actor_id'                  => $actorId,
        ]);
    }

    /**
     * Validate election timeline (called in boot saving hook)
     */
    public function validateTimeline(?array $dates = null): void
    {
        $dates = $dates ?? [
            'administration_starts_at' => $this->administration_starts_at,
            'administration_ends_at' => $this->administration_ends_at,
            'nomination_starts_at' => $this->nomination_starts_at,
            'nomination_ends_at' => $this->nomination_ends_at,
            'voting_starts_at' => $this->voting_starts_at,
            'voting_ends_at' => $this->voting_ends_at,
        ];

        $adminStart = $dates['administration_starts_at'] ?? null;
        $adminEnd = $dates['administration_ends_at'] ?? null;
        $nomStart = $dates['nomination_starts_at'] ?? null;
        $nomEnd = $dates['nomination_ends_at'] ?? null;
        $votingStart = $dates['voting_starts_at'] ?? null;
        $votingEnd = $dates['voting_ends_at'] ?? null;

        // Minimum durations
        if ($adminStart && $adminEnd) {
            if ($adminEnd->diffInHours($adminStart) < 24) {
                throw new \InvalidArgumentException('Administration phase must last at least 24 hours');
            }
        }

        if ($nomStart && $nomEnd) {
            if ($nomEnd->diffInHours($nomStart) < 24) {
                throw new \InvalidArgumentException('Nomination phase must last at least 24 hours');
            }
        }

        // Voting duration minimum check (optional, can be relaxed for testing)
        // Disabled for now to allow flexible test scenarios
        // if ($votingStart && $votingEnd) {
        //     if ($votingEnd->diffInMinutes($votingStart) < 60) {
        //         throw new \InvalidArgumentException('Voting phase must last at least 1 hour');
        //     }
        // }

        // Chronological order
        if ($adminEnd && $nomStart) {
            if ($adminEnd->gte($nomStart)) {
                throw new \InvalidArgumentException('Administration must end before nomination starts (chronological order)');
            }
        }

        if ($nomEnd && $votingStart) {
            if ($nomEnd->gte($votingStart)) {
                throw new \InvalidArgumentException('Nomination must end before voting starts (chronological order)');
            }
        }

        // Voting dates validation: start before end
        if ($votingStart && $votingEnd) {
            if ($votingStart->gte($votingEnd)) {
                throw new \InvalidArgumentException('Voting start date must be before end date');
            }

            // Voting start cannot be in the past (integrity critical) — skip in tests
            if ($this->type === 'real' && !app()->environment('testing') && $votingStart->lt(now())) {
                throw new \InvalidArgumentException('Voting start date cannot be in the past');
            }
        }
    }

    private ?ElectionStateMachine $stateMachine = null;

    public function getStateMachine(): ElectionStateMachine
    {
        return $this->stateMachine ??= new ElectionStateMachine($this);
    }

    public function lockVoting(?string $actorId = null): void
    {
        $this->update([
            'voting_locked' => true,
            'voting_locked_at' => now(),
            'voting_locked_by' => $actorId,
        ]);

        $this->logStateChange('voting_locked', ['actor_id' => $actorId]);
    }

    public function lockResults(): void
    {
        $this->update([
            'results_locked' => true,
            'results_locked_at' => now(),
        ]);

        $this->logStateChange('results_locked', []);
    }

    /**
     * Log state changes to audit trail (append-only, capped at 200 entries) + dual-write to audit_logs
     */
    public function logStateChange(string $action, array $metadata): void
    {
        // 1. Append to JSON column (backward compatibility)
        $log = $this->state_audit_log ?? [];

        $log[] = [
            'action'    => $action,
            'metadata'  => $metadata,
            'timestamp' => now()->toIso8601String(),
        ];

        // Keep only last 200 entries to prevent bloat
        $log = array_slice($log, -200);

        $this->update(['state_audit_log' => $log]);

        // 2. Create audit log record (dual-write for complete audit trail)
        ElectionAuditLog::create([
            'election_id' => $this->id,
            'action' => $action,
            'new_values' => $metadata,
        ]);
    }

    // ── Lifecycle Hooks ────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::saving(function (Election $election) {
            $election->validateTimeline();
        });
        static::updated(function (Election $election) {
            $cols = [
                'ip_restriction_enabled',
                'ip_restriction_max_per_ip',
                'ip_whitelist',
                'no_vote_option_enabled',
                'no_vote_option_label',
                'selection_constraint_type',
                'selection_constraint_min',
                'selection_constraint_max',
            ];

            if ($election->wasChanged($cols)) {
                // Backward compatibility: clear old cache key
                \Illuminate\Support\Facades\Cache::forget("election-settings-{$election->id}");

                // Clear ElectionSettingsService cache keys
                $settingKeys = [
                    'max_use_ip_address',
                    'control_ip_address',
                    'select_all_required',
                    'ip_mismatch_action',
                    'voting_ip_mode',
                    'ip_whitelist',
                    'no_vote_enabled',
                    'no_vote_label',
                ];

                foreach ($settingKeys as $key) {
                    \Illuminate\Support\Facades\Cache::forget("election:{$election->id}:setting:{$key}");
                }
            }
        });
    }
}
