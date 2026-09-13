<?php

namespace App\Application\Election\Services;

use App\Models\Election;

/**
 * The single authoritative definition of the two nomination-completion
 * business predicates. Consumed by BOTH ConstitutionalTransitionGuard (the
 * human/manual `complete_nomination` path) and
 * Election::validateCompleteNomination() (both the human and system paths,
 * since that hook runs regardless of transition trigger).
 *
 * Deliberately kept to exactly these two predicates — not a generic
 * precondition framework.
 *
 * withoutGlobalScopes() is required, not decorative: the system/console
 * caller (ProcessElectionAutoTransitions) executes with no HTTP tenant
 * context, so Candidacy's BelongsToTenant global scope would otherwise
 * silently see zero rows for a real tenant election.
 */
final class NominationCompletionPredicates
{
    public static function hasApprovedCandidates(Election $election): bool
    {
        return $election->candidacies()->withoutGlobalScopes()
            ->where('status', 'approved')->exists();
    }

    public static function hasNoPendingCandidacies(Election $election): bool
    {
        return !$election->candidacies()->withoutGlobalScopes()
            ->where('status', 'pending')->exists();
    }
}
