<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementContext;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;

/**
 * VotingEntitlementPolicy — the FINAL voting-entitlement decision (PBDIGIT-213).
 *
 * Composes; owns none of its inputs (ADR_20261004_1549 §2.2 invariant 8):
 *   registered → voter role → organisation scope → active membership → admitted
 *   (PBDIGIT-213 C3) → election votable.
 * The first failing condition is the single reason returned.
 *
 * - standing(): the voter's own standing in the election (person-level conditions).
 * - decide():   standing, then whether the election can be voted in now.
 *
 * Not owned here: ballot anonymity, one-ballot locking, IP/device security,
 * the suspension workflow. Pure domain service — no framework, no database.
 */
final class VotingEntitlementPolicy
{
    public function standing(VotingEntitlementContext $context): VotingEntitlementReason
    {
        if (! $context->isRegistered) {
            return VotingEntitlementReason::NotRegistered;
        }

        if ($context->role !== 'voter') {
            return VotingEntitlementReason::NotVoterRole;
        }

        if (! $context->organisationMatches) {
            return VotingEntitlementReason::OrganisationMismatch;
        }

        if ($context->status !== 'active') {
            return VotingEntitlementReason::NotActive;
        }

        // PBDIGIT-213 C3: the election's admission requirements (policy + evidence).
        if (! $context->isAdmitted) {
            return VotingEntitlementReason::NotAdmitted;
        }

        return VotingEntitlementReason::Entitled;
    }

    public function decide(VotingEntitlementContext $context, bool $electionVotable): VotingEntitlementReason
    {
        $standing = $this->standing($context);

        if (! $standing->isEntitled()) {
            return $standing;
        }

        return $electionVotable ? VotingEntitlementReason::Entitled : VotingEntitlementReason::ElectionNotVotable;
    }
}
