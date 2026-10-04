<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * VotingEntitlementContext — immutable input to the voting-entitlement decision.
 *
 * The registration facts of one (voter, election) pair, built by infrastructure
 * from the election's own data (never from ambient tenant context — PBDIGIT-65/69).
 * Pure data carrier, like EligibilityContext: no Eloquent, no business logic.
 *
 * isAdmitted (PBDIGIT-213 C3) is the outcome of the election's VoterAdmissionPolicy
 * against the voter's AdmissionEvidence. Its default `true` represents an election
 * with no admission requirement (policy OFF); infrastructure always supplies it.
 */
final readonly class VotingEntitlementContext
{
    public function __construct(
        public bool $isRegistered,
        public ?string $role = null,
        public ?string $status = null,
        public bool $organisationMatches = false,
        public bool $isAdmitted = true,
    ) {}
}
