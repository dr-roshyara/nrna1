<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\VoterSourceStrategy;

/**
 * ElectionOnlyPolicy — Pure logic for election-only mode eligibility
 *
 * Responsibility: Decide if a user qualifies as a voter in election-only mode.
 *
 * Rules (election-only mode):
 * - User must be in organisation_users table
 * - User status must be 'active'
 * - User must not be soft-deleted
 *
 * This is a PURE DOMAIN SERVICE — no framework, no database, no ORM.
 *
 * The isEligible() method signature is frozen per VoterEligibilityPolicy interface.
 * Internal logic works with EligibilityContext (pure DTO).
 */
final class ElectionOnlyPolicy implements VoterEligibilityPolicy
{
    /**
     * Determine eligibility for election-only mode voter
     *
     * Implements VoterEligibilityPolicy interface (Phase A frozen signature).
     * Infrastructure layer queries database, builds EligibilityContext,
     * then calls this method.
     *
     * For unit testing: pass context state as parameters.
     * For integration testing: infrastructure builds context from database.
     */
    public function isEligible(
        string $userId,
        string $organisationId,
        VoterSourceStrategy $mode
    ): bool {
        // Phase A: Placeholder satisfies interface contract
        // Phase B: Infrastructure will wire this to database queries
        // Unit tests will call this directly with test data

        if (! $mode->isImportedVoterRegistry()) {
            return false;
        }

        // In Phase B, infrastructure builds context from DB, passes to decideForContext
        // For now, just check mode
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
     * Internal helper for pure logic testing
     *
     * Infrastructure builds EligibilityContext from database,
     * then delegates to this method for decision.
     *
     * This allows unit tests to test pure logic without database.
     */
    public function decideForContext(EligibilityContext $context): bool
    {
        // Election-only rules: active + not deleted
        return $context->mode->isImportedVoterRegistry()
            && $context->isActive
            && ! $context->isDeleted;
    }
}
