<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Traits\BelongsToTenant;
use App\Models\Election;

class Member extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'organisation_user_id',
        'membership_type_id',
        'membership_number',
        'status',
        'fees_status',
        'joined_at',
        'membership_expires_at',
        'last_renewed_at',
        'ended_at',
        'end_reason',
        'created_by',
        'updated_by',
        'newsletter_unsubscribed_at',
        'newsletter_unsubscribe_token',
        'newsletter_bounced_at',
    ];

    protected $casts = [
        'joined_at'             => 'datetime',
        'membership_expires_at' => 'datetime',
        'last_renewed_at'       => 'datetime',
        'ended_at'              => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];

    /**
     * Upward relationships (identity hierarchy)
     */
    public function organisationUser()
    {
        return $this->belongsTo(OrganisationUser::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            OrganisationUser::class,
            'id',
            'id',
            'organisation_user_id',
            'user_id'
        );
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Downward relationships (hierarchy)
     */
    public function voters()
    {
        return $this->hasMany(Voter::class, 'member_id');
    }

    public function fees()
    {
        return $this->hasMany(MembershipFee::class);
    }

    public function payments()
    {
        return $this->hasMany(MembershipPayment::class);
    }

    public function renewals()
    {
        return $this->hasMany(MembershipRenewal::class);
    }

    // ── Voting rights ─────────────────────────────────────────────────────────

    /**
     * Computed voting rights based on membership type + fees + status.
     *
     * full       → Full Member type + (paid or exempt fees) + active
     * voice_only → Full Member type + partial fees + active
     *              OR Associate Member type + (paid or exempt fees) + active
     * none       → unpaid fees, expired, or suspended
     */
    public function getVotingRightsAttribute(): string
    {
        if (! in_array($this->status, ['active'])) {
            return 'none';
        }

        $typeGrantsVoting = (bool) ($this->membershipType?->grants_voting_rights ?? false);

        if ($typeGrantsVoting) {
            return match ($this->fees_status) {
                'paid', 'exempt' => 'full',
                'partial'        => 'voice_only',
                default          => 'none',  // unpaid
            };
        }

        // Associate type — capped at voice_only when fees are met
        return match ($this->fees_status) {
            'paid', 'exempt' => 'voice_only',
            default          => 'none',
        };
    }

    /**
     * Whether this member may cast a ballot in the given election.
     *
     * Requires:
     *  - voting_rights === 'full'
     *  - election belongs to the same organisation
     */
    public function canVoteInElection(Election $election): bool
    {
        if ($election->organisation_id !== $this->organisation_id) {
            return false;
        }

        return $this->voting_rights === 'full';
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /**
     * Can the member self-renew?
     * - Not a lifetime member
     * - Within 90 days after expiry (configurable)
     */
    public function canSelfRenew(): bool
    {
        if ($this->status !== 'active' || $this->membership_expires_at === null) {
            return false; // lifetime members cannot renew
        }

        $windowDays = config('membership.self_renewal_window_days', 90);

        return $this->membership_expires_at->isAfter(now()->subDays($windowDays));
    }

    /**
     * End this membership, cascade-waive pending fees and remove from active elections.
     */
    public function endMembership(?string $reason = null): void
    {
        DB::transaction(function () use ($reason) {
            $this->update([
                'status'     => 'ended',
                'ended_at'   => now(),
                'end_reason' => $reason,
            ]);

            // Waive all pending fees
            $this->fees()->where('status', 'pending')->update(['status' => 'waived']);

            // Remove from active elections via the user linked to this membership
            $userId = $this->organisationUser?->user_id;
            if ($userId) {
                ElectionMembership::where('user_id', $userId)
                    ->where('status', 'active')
                    ->update(['status' => 'removed']);
            }
        });
    }
}
