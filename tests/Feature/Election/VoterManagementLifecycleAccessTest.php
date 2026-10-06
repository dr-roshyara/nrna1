<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-251 — RED: Voter Management is reachable in every lifecycle state, for verification (PO 2026-10-06:
 * "this page should be accessible all the time to verify identity and IP address binding … even at the time
 * of voting").
 *
 * The PAGE gets its own lifecycle operation (view_voter_management, every state). Each WRITE keeps its own gate:
 *   import / assign / remove / … → import_voters (setup administration only — unchanged)
 *   verify identity              → verify_voter_identity (SetupAdministration → VotingActive)
 *   capture IP                   → capture_voter_ip      (SetupAdministration → VotingActive)
 * The page receives capabilities { manageVoters, verifyIdentity, captureIp } so it shows only what is allowed.
 * Contact data (email / telephone) is never editable here.
 *
 *   L1 the Chief opens Voter Management while voting is active
 *   L2 the page opens in Draft, SetupNomination, ReadyForVoting, ResultsPublished and Suspended
 *   L3 while voting: identity verification and IP capture succeed
 *   L4 while voting: assign and remove are still refused (unchanged)
 *   L5 capabilities per state
 *   L8–L11 (PO 2026-10-06, "suspension only"): two-person suspension (propose / confirm / cancel proposal) works
 *          until voting closes — own window suspend_voters; approve / remove / import / export stay setup-only
 * Synthetic data only.
 */
class VoterManagementLifecycleAccessTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
    }

    private function asChief(Election $election): User
    {
        $chief = $this->officer($election, 'chief');
        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id]);

        return $chief;
    }

    private function page(Election $election)
    {
        return $this->get($this->voterRoute('elections.voters.index', $election));
    }

    private function readyForVoting(): Election
    {
        return tap(ElectionScenarioFactory::setupAdministration($this->org))->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->addDay(),
            'voting_ends_at'           => now()->addDays(2),
        ])->fresh();
    }

    private function suspended(): Election
    {
        return tap(ElectionScenarioFactory::setupAdministration($this->org))->update(['suspended_at' => now()])->fresh();
    }

    public function test_l1_the_chief_opens_voter_management_while_voting_is_active(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $this->asChief($election);

        $this->page($election)->assertOk();
    }

    public function test_l2_the_page_opens_in_every_lifecycle_state(): void
    {
        $elections = [
            'Draft'            => Election::factory()->forOrganisation($this->org)->create(['type' => 'real']),
            'SetupNomination'  => ElectionScenarioFactory::setupNomination($this->org),
            'ReadyForVoting'   => $this->readyForVoting(),
            'ResultsPublished' => ElectionScenarioFactory::resultsPublished($this->org),
            'Suspended'        => $this->suspended(),
        ];

        foreach ($elections as $state => $election) {
            $this->asChief($election);
            $this->assertSame(200, $this->page($election)->status(), "{$state}: Voter Management is reachable");
        }
    }

    public function test_l3_while_voting_identity_verification_and_ip_capture_succeed(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true]);
        $this->asChief($election);
        $voter = $this->registeredVoter($election);
        $voter->forceFill(['current_ip' => '203.0.113.7'])->save();

        $this->from('/voters-manage')->post($this->voterRoute('elections.voters.verify', $election),
            ['user_id' => $voter->id, 'attested' => true])->assertSessionHasNoErrors();
        $this->from('/voters-manage')->post($this->voterRoute('elections.voters.capture-ip', $election),
            ['user_id' => $voter->id])->assertSessionHasNoErrors();

        $this->assertSame(1, DB::table('voter_verifications')->where('election_id', $election->id)->where('status', 'active')->count());
        $this->assertSame('203.0.113.7', DB::table('voter_ip_verifications')->where('election_id', $election->id)->value('ip_address'));
    }

    public function test_l4_while_voting_assign_and_remove_are_still_refused(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $this->asChief($election);
        $voter = $this->registeredVoter($election);
        $membership = ElectionMembership::where('election_id', $election->id)->where('user_id', $voter->id)->first();
        $newcomer = User::factory()->create(['organisation_id' => $this->org->id]);

        $this->assertSame(403, $this->post($this->voterRoute('elections.voters.store', $election), ['user_id' => $newcomer->id])->status(),
            'assign stays in the import_voters window');
        $this->assertSame(403, $this->delete($this->voterRoute('elections.voters.destroy', $election, ['membership' => $membership->id]))->status(),
            'remove stays in the import_voters window');
        $this->assertSame('active', $membership->fresh()->status);
    }

    public function test_l5_capabilities_follow_the_lifecycle_state(): void
    {
        $setup = ElectionScenarioFactory::setupAdministration($this->org);
        $this->asChief($setup);
        $this->page($setup)->assertInertia(fn ($p) => $p
            ->where('capabilities.manageVoters', true)
            ->where('capabilities.suspendVoters', true)
            ->where('capabilities.verifyIdentity', true)
            ->where('capabilities.captureIp', true));

        $voting = ElectionScenarioFactory::votingActive($this->org);
        $this->asChief($voting);
        $this->page($voting)->assertInertia(fn ($p) => $p
            ->where('capabilities.manageVoters', false)
            ->where('capabilities.suspendVoters', true)
            ->where('capabilities.verifyIdentity', true)
            ->where('capabilities.captureIp', true));

        $closed = ElectionScenarioFactory::resultsPublished($this->org);
        $this->asChief($closed);
        $this->page($closed)->assertInertia(fn ($p) => $p
            ->where('capabilities.manageVoters', false)
            ->where('capabilities.suspendVoters', false)
            ->where('capabilities.verifyIdentity', false)
            ->where('capabilities.captureIp', false));
    }

    // ── Two-person suspension until voting closes (PO 2026-10-06: "suspension only") ──────────

    private function membershipOf(Election $election, User $voter): ElectionMembership
    {
        return ElectionMembership::where('election_id', $election->id)->where('user_id', $voter->id)->first();
    }

    private function as(User $officer)
    {
        return $this->actingAs($officer)->withSession(['current_organisation_id' => $this->org->id])->from('/voters-manage');
    }

    public function test_l8_while_voting_a_two_person_suspension_is_proposed_and_confirmed(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        $deputy = $this->officer($election, 'deputy');
        $membership = $this->membershipOf($election, $this->registeredVoter($election));

        $this->as($chief)->post($this->voterRoute('elections.voters.propose-suspension', $election, ['membership' => $membership->id]))
            ->assertStatus(302);
        $this->assertSame('proposed', $membership->fresh()->suspension_status);

        $this->as($deputy)->post($this->voterRoute('elections.voters.confirm-suspension', $election, ['membership' => $membership->id]))
            ->assertStatus(302);
        $this->assertSame('inactive', $membership->fresh()->status, 'the voter is suspended during voting');
    }

    public function test_l9_while_voting_the_proposer_can_cancel_a_suspension_proposal(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        $membership = $this->membershipOf($election, $this->registeredVoter($election));

        $this->as($chief)->post($this->voterRoute('elections.voters.propose-suspension', $election, ['membership' => $membership->id]));
        $this->as($chief)->post($this->voterRoute('elections.voters.cancel-proposal', $election, ['membership' => $membership->id]))
            ->assertStatus(302);

        $this->assertNotSame('proposed', $membership->fresh()->suspension_status);
        $this->assertSame('active', $membership->fresh()->status);
    }

    public function test_l10_after_voting_a_suspension_cannot_be_proposed(): void
    {
        $election = ElectionScenarioFactory::resultsPublished($this->org);
        $chief = $this->officer($election, 'chief');
        $membership = $this->membershipOf($election, $this->registeredVoter($election));

        $this->assertSame(403, $this->as($chief)
            ->post($this->voterRoute('elections.voters.propose-suspension', $election, ['membership' => $membership->id]))->status());
        $this->assertNotSame('proposed', $membership->fresh()->suspension_status);
    }

    public function test_l11_while_voting_approve_stays_refused(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        $membership = $this->membershipOf($election, $this->registeredVoter($election, 'invited'));

        $this->assertSame(403, $this->as($chief)
            ->post($this->voterRoute('elections.voters.approve', $election, ['membership' => $membership->id]))->status(),
            'approve changes the electorate — setup administration only');
        $this->assertSame('invited', $membership->fresh()->status);
    }
}
