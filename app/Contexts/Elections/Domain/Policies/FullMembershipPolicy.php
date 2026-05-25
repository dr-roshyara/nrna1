<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\VoterSourceStrategy;

/**
 * FullMembershipPolicy — Pure logic for full membership mode eligibility
 *
 * Responsibility: Decide if a user qualifies as a voter in full membership mode.
 *
 * Rules (full membership mode):
 * - User must have active member record
 * - Fees must be paid or exempt
 * - User must not be soft-deleted
 *
 * This is a PURE DOMAIN SERVICE — no framework, no database, no ORM.
 */
final class FullMembershipPolicy implements VoterEligibilityPolicy
{
    /**
     * Stub implementation for interface contract.
     * Infrastructure (EloquentVoterEligibilityQueryService) provides the real implementation.
     */
    public function isEligible(
        string $userId,
        string $organisationId,
        VoterSourceStrategy $mode
    ): bool {
        // Placeholder — infrastructure queries DB and calls decideForContext
        return true;
    }

    /**
     * Bulk filter — domain policies don't implement this.
     * Infrastructure (EloquentVoterEligibilityQueryService) provides the real implementation.
     * This stub exists only to satisfy interface contract.
     */
    public function qualifyingSubset(
        array $userIds,
        string $organisationId,
        VoterSourceStrategy $mode
    ): array {
        // Domain policies don't implement bulk filtering
        // Infrastructure handles this with optimized queries
        return [];
    }
    /**
     * Decide eligibility based on context
     *
     * Infrastructure builds context from DB queries,
     * then delegates to this method for pure decision logic.
     */
    public function decideForContext(EligibilityContext $context): bool
    {
        return $context->mode->isMembershipRegistry()
            && $context->isActive
            && ! $context->isDeleted
            && in_array($context->membershipStatus, ['active'], true)
            && in_array($context->feesStatus, ['paid', 'exempt'], true);
    }
}
