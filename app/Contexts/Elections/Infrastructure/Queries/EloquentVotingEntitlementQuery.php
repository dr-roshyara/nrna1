<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\Policies\VotingEntitlementPolicy;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionEvidence;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementContext;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use Illuminate\Support\Facades\DB;

/**
 * EloquentVotingEntitlementQuery — uncached, authoritative implementation of
 * VotingEntitlementQuery (PBDIGIT-213 C1).
 *
 * Builds the VotingEntitlementContext from the election's own data and delegates
 * the decision to VotingEntitlementPolicy.
 *
 * Scope rule (PBDIGIT-65/69, ADR-002 clarification (i)): only the 'tenant' global
 * scope is removed — the election determines the required organisation, ambient
 * context never filters the answer. SoftDeletes stays in force on both the
 * election and the membership, so a deleted record fails closed.
 *
 * Used directly (uncached) by the in-transaction check at vote.store / vote.verify;
 * every other caller receives CachedVotingEntitlementQuery through the container.
 */
final class EloquentVotingEntitlementQuery implements VotingEntitlementQuery
{
    public function __construct(private readonly VotingEntitlementPolicy $policy) {}

    public function standingOf(string $userId, string $electionId): VotingEntitlementReason
    {
        return $this->policy->standing($this->contextFor($userId, $this->election($electionId)));
    }

    public function decide(string $userId, string $electionId): VotingEntitlementReason
    {
        $election = $this->election($electionId);
        $votable  = $election !== null && ElectionLifecycle::of($election)->canVote();

        return $this->policy->decide($this->contextFor($userId, $election), $votable);
    }

    private function election(string $electionId): ?Election
    {
        return Election::withoutGlobalScope('tenant')->find($electionId);
    }

    private function contextFor(string $userId, ?Election $election): VotingEntitlementContext
    {
        if ($election === null) {
            return new VotingEntitlementContext(isRegistered: false);
        }

        // One live membership per (user, election): partial unique index
        // uq_user_election_active (deleted_at IS NULL).
        $membership = ElectionMembership::withoutGlobalScope('tenant')
            ->where('election_id', $election->id)
            ->where('user_id', $userId)
            ->first(['role', 'status', 'organisation_id']);

        if ($membership === null) {
            return new VotingEntitlementContext(isRegistered: false);
        }

        return new VotingEntitlementContext(
            isRegistered: true,
            role: $membership->role,
            status: $membership->status,
            organisationMatches: $membership->organisation_id === $election->organisation_id,
            isAdmitted: $this->isAdmitted($userId, $election),
        );
    }

    /**
     * PBDIGIT-213 C3.1 — the election's VoterAdmissionPolicy against the voter's
     * CURRENT admission evidence for THIS election. Policy OFF ⇒ admitted without
     * reading any evidence. Only active evidence counts; revoked evidence does not.
     */
    private function isAdmitted(string $userId, Election $election): bool
    {
        $policy = $election->admissionPolicy();

        if ($policy->isEmpty()) {
            return true;
        }

        $hasActiveIdentityEvidence = DB::table('voter_verifications')
            ->where('election_id', $election->id)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists();

        $evidence = $hasActiveIdentityEvidence
            ? AdmissionEvidence::satisfying(AdmissionRequirement::OfficerIdentityVerification)
            : AdmissionEvidence::none();

        return $policy->isSatisfiedBy($evidence)->isAdmitted();
    }
}
