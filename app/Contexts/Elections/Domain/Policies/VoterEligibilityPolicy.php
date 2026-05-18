<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Domain\Election\Enum\ElectionMode;

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
     * @param ElectionMode $mode Election operational mode
     *
     * @return bool True if eligible, false otherwise
     */
    public function isEligible(
        string $userId,
        string $organisationId,
        ElectionMode $mode
    ): bool;
}
