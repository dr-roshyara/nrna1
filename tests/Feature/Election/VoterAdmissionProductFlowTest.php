<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C4 — product-level acceptance flows (R3-6), end to end through the
 * real endpoints the UI uses. The UI only displays; every step here is decided by
 * the C2/C3 server-side model.
 *
 *  A   settings ON → voter shown "verification_required" → refused at code entry →
 *      Chief verifies (Verify Identity) → voter reaches code entry.
 *  A′  same, via Verify All (bulk attestation).
 *  B   Suspend Identity (existing two-person process) → "reverification_required" →
 *      reinstatement alone not entitled → re-verification ⇒ entitled.
 */
class VoterAdmissionProductFlowTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->chief    = $this->officer($this->election, 'chief', 'same', 'Chief One');
        $this->voter    = $this->registeredVoter($this->election);
    }

    private function as(User $user)
    {
        return $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function standing(): VotingEntitlementReason
    {
        return app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id);
    }

    private function identityStateOnManagePage(): string
    {
        $state = null;
        $this->as($this->chief)->get($this->voterRoute('elections.voters.index', $this->election))
            ->assertInertia(function ($page) use (&$state) {
                $state = $page->toArray()['props']['identityVerification'][$this->voter->id] ?? null;
            });

        return (string) $state;
    }

    private function turnIdentityVerificationOnFromSettings(): void
    {
        $this->as($this->chief)->get(route('elections.settings.edit', $this->election->slug))
            ->assertInertia(fn ($page) => $page
                ->where('admission.identityVerificationRequired', false)
                ->where('admission.affectedVoters', 1));

        $this->as($this->chief)
            ->from(route('elections.settings.edit', $this->election->slug))
            ->patch(route('elections.admission-policy.update', $this->election->slug), ['identity_verification_required' => true])
            ->assertSessionHas('success');

        $this->as($this->chief)->get(route('elections.settings.edit', $this->election->slug))
            ->assertInertia(fn ($page) => $page->where('admission.identityVerificationRequired', true));
    }

    private function voterSlug(): VoterSlug
    {
        return VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    public function test_a_identity_verification_on_then_verify_identity_lets_the_voter_vote(): void
    {
        $this->turnIdentityVerificationOnFromSettings();
        $this->assertSame('verification_required', $this->identityStateOnManagePage());

        $this->election = $this->openVoting($this->election);
        $slug = $this->voterSlug();
        $this->as($this->voter)->getJson(route('slug.code.create', ['vslug' => $slug->slug]))->assertForbidden();

        $this->as($this->chief)->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.verify', $this->election), ['user_id' => $this->voter->id, 'attested' => true])
            ->assertSessionHasNoErrors();

        $this->as($this->voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))->assertOk();
    }

    public function test_a_prime_identity_verification_on_then_verify_all_lets_the_voter_vote(): void
    {
        $this->turnIdentityVerificationOnFromSettings();
        $this->as($this->chief)->get($this->voterRoute('elections.voters.index', $this->election))
            ->assertInertia(fn ($page) => $page->where('admission.bulkVerifiableCount', 1));

        $this->election = $this->openVoting($this->election);
        $slug = $this->voterSlug();
        $this->as($this->voter)->getJson(route('slug.code.create', ['vslug' => $slug->slug]))->assertForbidden();

        $this->as($this->chief)->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.verify-all', $this->election), ['attested' => '1', 'expected_count' => 1])
            ->assertSessionHasNoErrors();

        $this->as($this->voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))->assertOk();
    }

    public function test_b_suspend_identity_requires_reinstatement_and_re_verification(): void
    {
        $deputy = $this->officer($this->election, 'deputy', 'same', 'Deputy Two');
        $this->turnIdentityVerificationOnFromSettings();
        $this->as($this->chief)->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.verify', $this->election), ['user_id' => $this->voter->id, 'attested' => true]);
        $this->assertSame('verified', $this->identityStateOnManagePage());
        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());

        $membership = ElectionMembership::where('election_id', $this->election->id)->where('user_id', $this->voter->id)->firstOrFail();
        $suspensionRoute = fn (string $name) => $this->voterRoute($name, $this->election, ['membership' => $membership->id]);

        // Suspend Identity = the existing two-person process.
        $this->as($this->chief)->post($suspensionRoute('elections.voters.propose-suspension'));
        $this->as($deputy)->post($suspensionRoute('elections.voters.confirm-suspension'));
        $this->assertSame('reverification_required', $this->identityStateOnManagePage());
        $this->assertSame(VotingEntitlementReason::NotActive, $this->standing());

        // Reinstatement alone does not restore entitlement.
        $this->as($this->chief)->post($suspensionRoute('elections.voters.approve'));
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing());
        $this->assertSame('reverification_required', $this->identityStateOnManagePage());

        // Re-verification restores it.
        $this->as($deputy)->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.verify', $this->election), ['user_id' => $this->voter->id, 'attested' => true]);
        $this->assertSame('verified', $this->identityStateOnManagePage());
        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());
    }
}
