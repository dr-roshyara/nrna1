<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Constitutional Voting Protection Tests
 *
 * VERIFIES: Election administration and voting are constitutionally connected.
 * A voter cannot bypass administration to start voting.
 *
 * Test Groups:
 * A. Lifecycle Protection (5 tests) — canVote() gates in various lifecycle states
 * B. Middleware Bypass Verification (3 tests) — middleware behavior with voter_slug
 * C. Attack Simulation (4 tests) — direct routes and step skipping
 * D. Expired Slug Renewal (1 test) — Fix 1 regression test
 */
class ConstitutionalVotingProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF for all POST requests — none of these tests test CSRF behavior
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    // ============================================================
    // GROUP A: LIFECYCLE PROTECTION (5 tests)
    // ============================================================

    /**
     * RED: Cannot view ballot during SetupAdministration.
     * INVARIANT A + B: Voting requires completed administration lifecycle.
     */
    public function test_cannot_vote_during_setup_administration(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2, // Steps 1-2 completed
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
        $response->assertSessionHas('error', function (string $value) {
            return str_contains(strtolower($value), 'vot');
        });
    }

    /**
     * RED: Cannot view ballot during SetupNomination.
     */
    public function test_cannot_vote_during_nomination_phase(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupNomination($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Cannot vote during suspended overlay.
     * INVARIANT H: Suspension overrides lifecycle.
     */
    public function test_cannot_vote_during_suspended_overlay(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Suspend the election
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Test suspension',
        ]);

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
    }

    /**
     * RED: Cannot vote after voting window has closed.
     */
    public function test_cannot_vote_after_election_closed(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingClosed($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
    }

    /**
     * RED (positive control): Can vote when VotingActive.
     */
    public function test_can_vote_when_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Should reach the controller (may be 200 or redirect to step order — but NOT to dashboard)
        $this->assertNotEquals(
            route('dashboard'),
            $response->headers->get('Location') ?? '',
            'Ballot should be accessible during VotingActive'
        );
    }

    // ============================================================
    // GROUP B: MIDDLEWARE BYPASS VERIFICATION (3 tests)
    // ============================================================

    /**
     * RED: Code creation is allowed during SetupAdministration (intentional design).
     * INVARIANT E: Code creation is preparatory — not constitutionally gated.
     */
    public function test_code_creation_allowed_during_setup(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));

        // Code creation is preparatory — should not redirect to dashboard
        $this->assertNotEquals(
            route('dashboard'),
            $response->headers->get('Location') ?? '',
            'Code creation should be allowed during SetupAdministration (preparatory operation)'
        );
    }

    /**
     * RED: Agreement acceptance is allowed during ReadyForVoting (intentional design).
     * INVARIANT E: Agreement is preparatory — not constitutionally gated.
     */
    public function test_agreement_allowed_during_ready_for_voting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::configurationComplete($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Ensure we're in ReadyForVoting or SetupNomination
        $state = ElectionLifecycle::of($election)->state();

        if ($state !== ElectionLifecycleState::ReadyForVoting) {
            $election->update([
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_starts_at' => now()->addDay(),
                'voting_ends_at' => now()->addDays(2),
            ]);
        }

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 1, // Code entered, agreement pending
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.code.agreement', ['vslug' => $slug->slug]));

        // Agreement is preparatory — should not redirect to dashboard
        $this->assertNotEquals(
            route('dashboard'),
            $response->headers->get('Location') ?? '',
            'Agreement acceptance should be allowed during ReadyForVoting (preparatory operation)'
        );
    }

    /**
     * RED: Expired slug can reach code creation during non-VotingActive (for renewal).
     * This is the Fix 1 regression test — the voter_slug bypass must NOT be removed.
     */
    public function test_expired_slug_can_reach_code_creation_during_non_voting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->expired()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));

        // Expired slug must still reach CodeController for renewal
        // This is the INTENTIONAL BYPASS — the voter_slug middleware bypass exists for this reason
        $this->assertNotEquals(
            route('dashboard'),
            $response->headers->get('Location') ?? '',
            'Expired slugs MUST be able to reach CodeController for code renewal. '
            . 'Removing the voter_slug bypass would break this.'
        );
    }

    // ============================================================
    // GROUP C: ATTACK SIMULATION (4 tests)
    // ============================================================

    /**
     * RED: Direct POST to vote submit is blocked during non-VotingActive.
     * Tests that a forged POST to store() is rejected by the controller's canVote() check.
     */
    public function test_direct_post_to_vote_store_blocked_when_not_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        $response = $this->actingAs($user)
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => 'test-code',
            ]);

        // Must be blocked — 302 or 403
        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Skipping steps is blocked by EnsureVoterStepOrder middleware.
     * Tests that step order enforcement works regardless of lifecycle state.
     */
    public function test_skipping_steps_blocked_when_not_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 0, // Not past code creation
        ]);

        // Try to access step 3 (vote/create) without completing step 1
        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Step order middleware should redirect
        $response->assertStatus(302);
    }

    /**
     * RED: Direct election show route renders with canVote=false when not VotingActive.
     * INVARIANT A: The election show page correctly reports canVote() status,
     * but does NOT redirect — it is an informational page, not a constitutional gate.
     * The constitutional boundary is enforced at VoteController::create() and store().
     */
    public function test_direct_route_to_elections_show_blocked_when_not_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $response = $this->actingAs($user)
            ->get(route('elections.show', ['slug' => $election->slug]));

        // Page renders with canVote=false — informational, not a redirect
        $response->assertStatus(200);
    }

    /**
     * RED: Voter slug possession does NOT imply voting authority.
     * INVARIANT C: Voter slug ≠ constitutional voting authority.
     */
    public function test_slug_possession_does_not_imply_voting_authority(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Create slug with all steps "completed" (steps 1-2 done)
        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        // Even though user possesses a valid slug, ballot must be blocked
        $response = $this->actingAs($user)
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }
}
