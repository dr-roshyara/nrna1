<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Domain\Election\Enum\VoterSourceStrategy;

/**
 * VoterEligibilityPolicy — Domain Policy Port
 *
 * Responsibility:
 * Single eligibility decision point for voter participation.
 *
 * This is a PURE POLICY PORT (Hexagonal Architecture).
 * - Read-only (no state mutations)
 * - Deterministic (same inputs → same output)
 * - No side effects
 * - No domain rule implementations in interface
 *
 * The implementation (Phase B infrastructure) will contain:
 * - election-only mode logic
 * - full membership mode logic
 * - fee validation
 * - membership type checking
 *
 * But the INTERFACE remains clean and mode-agnostic.
 */
interface VoterEligibilityPolicy
{
    /**
     * Determine if a user is eligible to participate
     *
     * @param string $userId User to check
     * @param string $organisationId Explicit tenancy boundary
     * @param VoterSourceStrategy $mode Election operational mode
     *
     * @return bool True if eligible, false otherwise
     */
    public function isEligible(
        string $userId,
        string $organisationId,
        VoterSourceStrategy $mode
    ): bool;

    /**
     * Bulk eligibility filter — returns only qualifying user IDs.
     * Single DB query per mode. No N+1.
     *
     * @param array $userIds User IDs to filter
     * @param string $organisationId Explicit tenancy boundary
     * @param VoterSourceStrategy $mode Election operational mode
     *
     * @return array Subset of $userIds containing only eligible users
     */
    public function qualifyingSubset(
        array $userIds,
        string $organisationId,
        VoterSourceStrategy $mode
    ): array;
}
