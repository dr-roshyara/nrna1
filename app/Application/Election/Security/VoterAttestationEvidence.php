<?php

namespace App\Application\Election\Security;

/**
 * PBDIGIT-286 P5 (decision D3-P): the trust-only read port for a voter's verification evidence in one election.
 *
 * Answers whether the voter has ACTIVE officer verification evidence for this election, whatever the method
 * (individual, bulk or legacy rows; D3 = A, D3a). Missing or revoked evidence answers false (D3-E). A store failure
 * must throw, never answer false or true, so the callers fail closed (D3-E).
 *
 * The admission path keeps its own identical predicate (EloquentVotingEntitlementQuery::isAdmitted); the
 * duplication is accepted technical debt (D3-P).
 */
interface VoterAttestationEvidence
{
    public function hasActiveAttestation(string $electionId, string $userId): bool;
}
