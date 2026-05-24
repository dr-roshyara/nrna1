<?php

namespace Tests\Architecture\GovernanceRuntime;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Constitutional Boundary Classification Tests
 *
 * Verifies that operations are correctly classified by constitutional severity:
 * - Preparatory operations MAY occur before VotingActive
 * - Constitutional operations MUST NOT occur before VotingActive
 * - Sovereignty override operations are governance actions, not lifecycle
 *
 * INVARIANT: Code creation and agreement acceptance are PREPARATORY.
 * INVARIANT: Ballot rendering and vote persistence are CONSTITUTIONAL.
 * INVARIANT: Suspension and resume are SOVEREIGNTY OVERRIDE.
 */
class ConstitutionalBoundaryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * Helper: Create an active voter membership for a user in an election.
     * The EnsureElectionVoter middleware requires this for real elections.
     */
    private function createVoterMembership(User $user, Election $election, Organisation $org): ElectionMembership
    {
        return ElectionMembership::factory()->voter()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]);
    }

    // ============================================================
    // PREPARATORY OPERATIONS (allowed before VotingActive)
    // ============================================================

    /**
     * RED: Code creation is preparatory — allowed during SetupAdministration.
     * Users can prepare to vote before voting opens.
     */
    public function test_code_creation_is_preparatory_allowed_during_setup(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $this->createVoterMembership($user, $election, $org);

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 0,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertNotEquals(
            302,
            $response->getStatusCode(),
            'Code creation should not redirect — it is a preparatory operation'
        );
    }

    /**
     * RED: Agreement acceptance is preparatory — allowed during VotingActive.
     */
    public function test_agreement_is_preparatory_allowed_during_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        $this->createVoterMembership($user, $election, $org);

        $this->assertEquals(
            ElectionLifecycleState::VotingActive,
            ElectionLifecycle::of($election)->state()
        );

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 1,
        ]);

        VoterSlugStep::create([
            'voter_slug_id' => $slug->id,
            'election_id' => $election->id,
            'step' => 1,
            'completed_at' => now(),
        ]);

        // Seed a verified access code so CodeController::showAgreement()
        // does not redirect to /code/create (its step-one fallback bypass).
        \App\Models\Code::factory()->verified()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
        ]);

        $response = $this
            ->withoutMiddleware([
                \App\Http\Middleware\EnsureVoterStepOrder::class,
                'voter.step.order',
            ])
            ->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('slug.code.agreement', ['vslug' => $slug->slug]));

        $this->assertNotEquals(
            302,
            $response->getStatusCode(),
            'Agreement acceptance should not redirect — location: '
            . ($response->headers->get('Location') ?? 'unknown')
        );
    }

    // ============================================================
    // CONSTITUTIONAL OPERATIONS (blocked before VotingActive)
    // ============================================================

    /**
     * RED: Ballot rendering is constitutional — blocked during SetupAdministration.
     * INVARIANT A: A voter MUST NOT see a ballot unless canVote() returns true.
     */
    public function test_ballot_rendering_is_constitutional_blocked_during_setup(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        VoterSlugStep::create(['voter_slug_id' => $slug->id, 'election_id' => $election->id, 'step' => 1, 'completed_at' => now()]);
        VoterSlugStep::create(['voter_slug_id' => $slug->id, 'election_id' => $election->id, 'step' => 2, 'completed_at' => now()]);

        // Ballot should be blocked (Step 3 — constitutional)
        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Ballot rendering is constitutional — blocked during SetupNomination.
     */
    public function test_ballot_rendering_blocked_during_nomination(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::setupNomination($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        VoterSlugStep::create(['voter_slug_id' => $slug->id, 'election_id' => $election->id, 'step' => 1, 'completed_at' => now()]);
        VoterSlugStep::create(['voter_slug_id' => $slug->id, 'election_id' => $election->id, 'step' => 2, 'completed_at' => now()]);

        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Vote persistence is constitutionally critical — blocked during SetupAdministration.
     * INVARIANT F: Both ballot display AND vote persistence MUST independently verify canVote().
     */
    public function test_vote_persistence_is_critical_blocked_during_setup(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::setupAdministration($org);
        $user = User::factory()->forOrganisation($org)->create();

        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 2,
        ]);

        // Direct POST to vote store should be blocked
        $response = $this->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'candidates' => [],
            ]);

        $response->assertStatus(302);
    }

    // ============================================================
    // SOVEREIGNTY OVERRIDE OPERATIONS
    // ============================================================

    /**
     * RED: Suspension is a sovereignty override — available in any state.
     */
    public function test_suspension_is_sovereignty_override_available_in_any_state(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($org->id);

        $election = ElectionScenarioFactory::votingActive($org);
        $chief = User::factory()->forOrganisation($org)->create();

        ElectionOfficer::create([
            'user_id' => $chief->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Sovereignty override test — emergency suspension',
                'category' => 'emergency',
            ]);

        $response->assertSessionHas('success');
        $this->assertNotNull($election->fresh()->suspended_at);
    }

    // ============================================================
    // CANVOTE() CONSTITUTIONAL CORRECTNESS
    // ============================================================

    /**
     * RED: canVote() returns true ONLY for VotingActive state.
     * INVARIANT G: canVote() is the single constitutional authority.
     */
    public function test_can_vote_true_only_for_voting_active(): void
    {
        $factoryMethods = ['setupAdministration', 'setupNomination', 'votingClosed', 'resultsPublished'];

        foreach ($factoryMethods as $method) {
            $currentOrg = Organisation::factory()->create(['type' => 'tenant']);
            TenantContext::set($currentOrg->id);

            $election = ElectionScenarioFactory::$method($currentOrg);
            $this->assertFalse(
                ElectionLifecycle::of($election->fresh())->canVote(),
                "canVote() should be false for state {$method}"
            );
        }

        // VotingActive should return true
        $activeOrg = Organisation::factory()->create(['type' => 'tenant']);
        TenantContext::set($activeOrg->id);

        $votingElection = ElectionScenarioFactory::votingActive($activeOrg);
        $this->assertTrue(
            ElectionLifecycle::of($votingElection->fresh())->canVote(),
            'canVote() should be true for VotingActive state'
        );
    }
}
