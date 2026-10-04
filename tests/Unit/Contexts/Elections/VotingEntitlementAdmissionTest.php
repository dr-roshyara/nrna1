<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Domain\Policies\VotingEntitlementPolicy;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementContext;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C3.1 — admission joins the final entitlement decision.
 *
 * ADR_20261004_1549 §2.1: VotingEntitlement = registered ∧ voter ∧ organisation ∧
 * active ∧ ADMITTED ∧ votable. Admission is evaluated after the membership's own
 * standing (a suspended voter is NotActive, whatever their evidence) and before
 * the lifecycle. Pure domain test.
 */
final class VotingEntitlementAdmissionTest extends TestCase
{
    private VotingEntitlementPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new VotingEntitlementPolicy();
    }

    private function activeVoter(bool $isAdmitted, string $status = 'active'): VotingEntitlementContext
    {
        return new VotingEntitlementContext(
            isRegistered: true,
            role: 'voter',
            status: $status,
            organisationMatches: true,
            isAdmitted: $isAdmitted,
        );
    }

    public function test_an_active_voter_who_is_not_admitted_is_denied(): void
    {
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->policy->standing($this->activeVoter(false)));
    }

    public function test_an_active_admitted_voter_has_standing(): void
    {
        $this->assertSame(VotingEntitlementReason::Entitled, $this->policy->standing($this->activeVoter(true)));
    }

    public function test_membership_status_is_reported_before_admission(): void
    {
        $this->assertSame(
            VotingEntitlementReason::NotActive,
            $this->policy->standing($this->activeVoter(isAdmitted: false, status: 'inactive'))
        );
    }

    public function test_admission_is_reported_before_the_lifecycle(): void
    {
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->policy->decide($this->activeVoter(false), electionVotable: false));
    }

    public function test_an_admitted_voter_in_a_closed_election_is_not_votable(): void
    {
        $this->assertSame(VotingEntitlementReason::ElectionNotVotable, $this->policy->decide($this->activeVoter(true), electionVotable: false));
    }

    public function test_admission_defaults_to_satisfied_when_no_requirement_applies(): void
    {
        // A context built without an admission input represents "no requirement"
        // (policy OFF) — the C1 behaviour is preserved.
        $context = new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'active', organisationMatches: true);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->policy->standing($context));
    }
}
