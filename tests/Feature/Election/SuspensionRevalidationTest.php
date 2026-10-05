<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C3.4 — Suspend Identity ≠ removal; suspension decision ≠ re-verification.
 *
 * ADR_20261004_1549 D5 / R3-3 / R3-4 / §2.2 inv. 2 and 9:
 *  - "Suspend Identity" is the EXISTING two-person process (propose → confirm);
 *  - a CONFIRMED suspension keeps the voter registered and ends the evidence's
 *    CURRENT admission effect (status revoked, reason suspension_confirmed) —
 *    the historical fact (who verified, when) is not deleted;
 *  - reinstatement alone does not re-verify; re-verification alone does not
 *    reinstate; both are required, in either order.
 */
class SuspensionRevalidationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;
    private User $voter;
    private ElectionMembership $membership;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);

        // Two distinct leaders: the two-person rule compares names.
        $this->chief  = $this->officer('chief', 'Chief One');
        $this->deputy = $this->officer('deputy', 'Deputy Two');

        $this->voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        $this->membership = ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);

        $this->verifyAs($this->chief);
        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing(), 'Precondition: verified and entitled');
    }

    private function officer(string $role, string $name): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id, 'name' => $name]);

        ElectionOfficer::create([
            'user_id'         => $user->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        return $user;
    }

    private function as(User $actor)
    {
        return $this->actingAs($actor)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function voterRoute(string $name): string
    {
        return route($name, ['organisation' => $this->org->slug, 'election' => $this->election->slug, 'membership' => $this->membership->id]);
    }

    private function verifyAs(User $actor): void
    {
        $this->as($actor)->post(
            route('elections.voters.verify', ['organisation' => $this->org->slug, 'election' => $this->election->slug]),
            ['user_id' => $this->voter->id, 'attested' => true, 'notes' => 'video call']
        )->assertSessionHasNoErrors();
    }

    private function suspend(): void
    {
        $this->as($this->chief)->post($this->voterRoute('elections.voters.propose-suspension'));
        $this->as($this->deputy)->post($this->voterRoute('elections.voters.confirm-suspension'));
        $this->assertSame('inactive', $this->membership->fresh()->status, 'Precondition: suspension confirmed');
    }

    private function reinstate(): void
    {
        $this->as($this->chief)->post($this->voterRoute('elections.voters.approve'));
        $this->assertSame('active', $this->membership->fresh()->status, 'Precondition: reinstated');
    }

    private function evidence(): VoterVerification
    {
        return VoterVerification::where('election_id', $this->election->id)->where('user_id', $this->voter->id)->firstOrFail();
    }

    private function standing(): VotingEntitlementReason
    {
        return app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id);
    }

    // ── Confirmed suspension ─────────────────────────────────────────────────

    public function test_a_confirmed_suspension_ends_the_current_admission_effect_but_keeps_the_voter_registered(): void
    {
        $verifiedBy = $this->evidence()->verified_by;

        $this->suspend();

        $evidence = $this->evidence();
        $this->assertSame('revoked', $evidence->status);
        $this->assertSame('suspension_confirmed', $evidence->revocation_reason);
        $this->assertSame($this->deputy->id, $evidence->revoked_by, 'the confirmer ends the effect');
        $this->assertSame($verifiedBy, $evidence->verified_by, 'the historical fact is kept');
        $this->assertNotNull(ElectionMembership::whereKey($this->membership->id)->first(), 'the voter stays registered');
        $this->assertSame(VotingEntitlementReason::NotActive, $this->standing());
    }

    public function test_a_proposal_alone_does_not_touch_the_evidence(): void
    {
        $this->as($this->chief)->post($this->voterRoute('elections.voters.propose-suspension'));

        $this->assertSame('active', $this->evidence()->status);
        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());
    }

    public function test_a_cancelled_proposal_keeps_the_evidence(): void
    {
        $this->as($this->chief)->post($this->voterRoute('elections.voters.propose-suspension'));
        $this->as($this->chief)->post($this->voterRoute('elections.voters.cancel-proposal'));

        $this->assertSame('active', $this->evidence()->status);
    }

    public function test_a_confirmed_suspension_ends_the_admission_effect_even_when_the_policy_is_off(): void
    {
        $this->election->update(['voter_admission_policy' => ['requires' => []]]);

        $this->suspend();

        $this->assertSame('revoked', $this->evidence()->status);
    }

    // ── Reinstatement ≠ re-verification ──────────────────────────────────────

    public function test_reinstatement_alone_does_not_restore_entitlement(): void
    {
        $this->suspend();

        $this->reinstate();

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing());
    }

    public function test_re_verification_alone_does_not_reinstate(): void
    {
        $this->suspend();

        $this->verifyAs($this->deputy);

        $this->assertSame('inactive', $this->membership->fresh()->status, 'verification never lifts a suspension');
        $this->assertSame(VotingEntitlementReason::NotActive, $this->standing());
    }

    public function test_reinstatement_then_re_verification_restores_entitlement(): void
    {
        $this->suspend();
        $this->reinstate();

        $this->verifyAs($this->deputy);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());
    }

    public function test_re_verification_then_reinstatement_restores_entitlement(): void
    {
        $this->suspend();
        $this->verifyAs($this->deputy);

        $this->reinstate();

        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());
    }
}
