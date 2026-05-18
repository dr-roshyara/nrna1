<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;

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
final class FullMembershipPolicy
{
    /**
     * Decide eligibility based on context
     *
     * Infrastructure builds context from DB queries,
     * then delegates to this method for pure decision logic.
     */
    public function decideForContext(EligibilityContext $context): bool
    {
        return $context->mode->isFullMembership()
            && $context->isActive
            && ! $context->isDeleted
            && in_array($context->membershipStatus, ['active'], true)
            && in_array($context->feesStatus, ['paid', 'exempt'], true);
    }
}
