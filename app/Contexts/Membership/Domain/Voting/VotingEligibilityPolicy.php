<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Voting;

use App\Contexts\Membership\Domain\Membership\MembershipLineage;

/**
 * Voting Eligibility Policy
 *
 * CRITICAL: This is the ONLY place where voting eligibility is determined.
 * All eligibility rules are centralized here (single source of truth).
 *
 * This is a domain service (NOT an aggregate) that:
 * - Evaluates MembershipLineage facts (pure membership state)
 * - Returns eligibility decisions as result DTOs
 * - Allows Elections context to consume decisions via gateway
 *
 * SEMANTIC DISTINCTION (DDD principle):
 *
 *   Aggregate Facts (MembershipLineage)
 *   ├─ exists(): membership relationship has historical episodes
 *   ├─ isActive(): current operational status is ACTIVE
 *   ├─ isSuspended(): current operational status is SUSPENDED
 *   └─ isTerminated(): current operational status is TERMINATED
 *
 *   Policy Decision (VotingEligibilityPolicy)
 *   └─ evaluate(): constitutional eligibility rules applied to facts
 *      Returns: canVote + reasonCode (governance interpretation)
 *
 * The aggregate reports operational facts.
 * The policy interprets those facts into governance decisions.
 * Never use aggregate methods for authorization — use policy instead.
 */
final class VotingEligibilityPolicy
{
    /**
     * Evaluate voting eligibility for a member.
     *
     * CRITICAL: This is the authoritative eligibility engine.
     * All Elections voting decisions flow through this policy.
     *
     * @param MembershipLineage|null $lineage The member's membership lifecycle (null = no membership)
     * @return VotingEligibilityResult Eligibility decision with reason code
     */
    public function evaluate(?MembershipLineage $lineage): VotingEligibilityResult
    {
        // DECISION 1: No membership = no voting rights
        if ($lineage === null) {
            return VotingEligibilityResult::ineligible(ReasonCode::NO_MEMBERSHIP);
        }

        // DECISION 2: Terminated status = no voting rights (terminal, irreversible)
        if ($lineage->isTerminated()) {
            return VotingEligibilityResult::ineligible(ReasonCode::TERMINATED);
        }

        // DECISION 3: Suspended status = no voting rights (temporary)
        if ($lineage->isSuspended()) {
            return VotingEligibilityResult::ineligible(ReasonCode::SUSPENDED);
        }

        // DECISION 4: Not ACTIVE = no voting rights (fallback safeguard)
        if (!$lineage->isActive()) {
            return VotingEligibilityResult::ineligible(ReasonCode::NOT_ACTIVE);
        }

        // DECISION 5: ACTIVE status = voting rights granted
        return VotingEligibilityResult::eligible();
    }
}
