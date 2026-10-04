<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C2 — C2/C3 BOUNDARY PIN (plan §7).
 *
 * C2 defines the requirement; C3 makes evidence satisfy it. This test proves that
 * C2 does NOT enforce: with identity verification ON, an unverified active voter's
 * standing is unchanged.
 *
 * ⚠ DECLARED SUPERSEDED BY C3.1: C3's RED replaces this expectation in the open
 * (policy ON + no evidence → NotAdmitted). It must not be edited silently.
 */
class AdmissionPolicyC2BoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_c2_policy_on_does_not_yet_change_voting_entitlement(): void
    {
        Election::resetPlatformOrgCache();
        $org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $org->id]);

        $election = ElectionScenarioFactory::setupAdministration($org);
        $election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);

        $voter = User::factory()->create(['organisation_id' => $org->id]);
        ElectionMembership::create([
            'user_id'         => $voter->id,
            'organisation_id' => $org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);

        $this->assertFalse($election->fresh()->admissionPolicy()->isEmpty(), 'Precondition: policy is ON');
        $this->assertSame(
            VotingEntitlementReason::Entitled,
            app(VotingEntitlementQuery::class)->standingOf($voter->id, $election->id)
        );
    }
}
