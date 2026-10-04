<?php

namespace App\Contexts\Elections\Application\Ports;

/**
 * AdmissionImpactQuery — how many registered voters an admission-policy change
 * affects (ADR_20261004_1549 R3-2; v1 boundary: none ↔ OFFICER_IDENTITY_VERIFICATION).
 *
 * "Registered voters without identity evidence" are the voters whose effective
 * voting entitlement would change under the proposed policy once admission
 * enforcement is active (C3): they would need verification when it is turned ON,
 * and would no longer need it when it is turned OFF. In C2 no entitlement changes.
 */
interface AdmissionImpactQuery
{
    /**
     * Active, not-deleted voter memberships of the election (election-derived
     * organisation scope) that have no ACTIVE identity evidence for that election.
     */
    public function registeredVotersWithoutIdentityEvidence(string $electionId): int;
}
