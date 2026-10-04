<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Domain\Policies\VotingEntitlementPolicy;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementContext;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C1 — VotingEntitlementPolicy: pure decision logic.
 *
 * ADR_20261004_1549 §2.2 invariant 8: the entitlement decision composes
 * registration, voter role, organisation scope, membership status and the
 * election lifecycle, and yields exactly ONE deterministic reason.
 * (Admission is added in C2; it is deliberately absent here.)
 *
 * Pure domain test: no database, no container, no factories.
 */
final class VotingEntitlementPolicyTest extends TestCase
{
    private VotingEntitlementPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new VotingEntitlementPolicy();
    }

    /** @return array<string, array{VotingEntitlementContext, VotingEntitlementReason}> */
    public static function standingCases(): array
    {
        return [
            'not registered' => [
                new VotingEntitlementContext(isRegistered: false),
                VotingEntitlementReason::NotRegistered,
            ],
            'registered, but not as voter' => [
                new VotingEntitlementContext(isRegistered: true, role: 'candidate', status: 'active', organisationMatches: true),
                VotingEntitlementReason::NotVoterRole,
            ],
            'membership organisation differs from election organisation' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'active', organisationMatches: false),
                VotingEntitlementReason::OrganisationMismatch,
            ],
            'inactive membership' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'inactive', organisationMatches: true),
                VotingEntitlementReason::NotActive,
            ],
            'invited membership' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'invited', organisationMatches: true),
                VotingEntitlementReason::NotActive,
            ],
            'removed membership' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'removed', organisationMatches: true),
                VotingEntitlementReason::NotActive,
            ],
            'organisation mismatch is reported before status' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'inactive', organisationMatches: false),
                VotingEntitlementReason::OrganisationMismatch,
            ],
            'active registered voter' => [
                new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'active', organisationMatches: true),
                VotingEntitlementReason::Entitled,
            ],
        ];
    }

    /** @dataProvider standingCases */
    public function test_standing_yields_exactly_one_reason(VotingEntitlementContext $context, VotingEntitlementReason $expected): void
    {
        $reason = $this->policy->standing($context);

        $this->assertSame($expected, $reason);
        $this->assertSame($expected === VotingEntitlementReason::Entitled, $reason->isEntitled());
    }

    public function test_entitled_voter_is_denied_when_the_election_is_not_votable(): void
    {
        $context = new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'active', organisationMatches: true);

        $this->assertSame(VotingEntitlementReason::ElectionNotVotable, $this->policy->decide($context, electionVotable: false));
    }

    public function test_entitled_voter_in_a_votable_election_is_entitled(): void
    {
        $context = new VotingEntitlementContext(isRegistered: true, role: 'voter', status: 'active', organisationMatches: true);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->policy->decide($context, electionVotable: true));
    }

    public function test_person_level_denial_is_reported_before_the_lifecycle(): void
    {
        $context = new VotingEntitlementContext(isRegistered: false);

        $this->assertSame(VotingEntitlementReason::NotRegistered, $this->policy->decide($context, electionVotable: false));
    }
}
