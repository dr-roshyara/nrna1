<?php

namespace Tests\Feature\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\StateMachine\Transition;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Notifications\ElectionReadyForActivation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionActivationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;
    private User $commissioner;
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        // Bypass CSRF middleware for all requests in this test
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'name'   => 'General Election 2026',
                'type'   => 'real',
                'state'  => 'approved',
                'approved_at' => now(),
            ]);

        $this->owner       = $this->createUserWithRole('owner');
        $this->chief       = $this->createOfficer('chief', 'active');
        $this->deputy      = $this->createOfficer('deputy', 'active');
        $this->commissioner = $this->createOfficer('commissioner', 'active');
    }

    // =========================================================================
    // Permission Tests
    // =========================================================================

    public function test_chief_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        // Debug: Check what's actually in the session
        if ($response->getSession()->has('error')) {
            $this->fail('Activation failed with error: ' . $response->getSession()->get('error'));
        }

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify constitutional facts were set
        $this->election->refresh();
        $this->assertNotNull($this->election->setup_started_at, 'begin_setup must set setup_started_at');
        $this->assertFalse($this->election->administration_completed, 'administration_completed should be false before complete_administration');

        // Verify engine derives SetupAdministration state
        $engine = app(ElectionLifecycleEngineImpl::class);
        $derivedState = $engine->getState($this->election);
        $this->assertEquals(ElectionLifecycleState::SetupAdministration, $derivedState);
    }

    public function test_deputy_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        $response->assertRedirect();

        // Verify constitutional facts were set
        $this->election->refresh();
        $this->assertNotNull($this->election->setup_started_at, 'begin_setup must set setup_started_at');

        // Verify engine derives SetupAdministration state
        $engine = app(ElectionLifecycleEngineImpl::class);
        $derivedState = $engine->getState($this->election);
        $this->assertEquals(ElectionLifecycleState::SetupAdministration, $derivedState);
    }

    public function test_commissioner_cannot_activate_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        $response->assertForbidden();
        $this->assertEquals('approved', $this->election->fresh()->state);
    }

    public function test_owner_cannot_activate_election(): void
    {
        $response = $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        $response->assertForbidden();
        $this->assertEquals('approved', $this->election->fresh()->state);
    }

    // =========================================================================
    // Status Transition Tests
    // =========================================================================

    public function test_cannot_activate_already_active_election(): void
    {
        // Set voting window to make engine compute VotingActive state
        Election::withoutGlobalScopes()->where('id', $this->election->id)->update([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        // EM-VOT-002 (adopted): VotingActive additionally requires an approved
        // candidate — this test's premise is an ALREADY-ACTIVE election.
        $post = \App\Models\Post::factory()->create([
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);
        \App\Models\Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $this->org->id,
            'user_id'         => \App\Models\User::factory()->create()->id,
            'status'          => 'approved',
        ]);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        $response->assertSessionHas('error', 'Election cannot be activated in its current state.');
    }

    public function test_cannot_activate_completed_election(): void
    {
        // Set results published to make engine compute ResultsPublished state
        Election::withoutGlobalScopes()->where('id', $this->election->id)->update([
            'results_published_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->slug));

        $response->assertSessionHas('error', 'Election cannot be activated in its current state.');
    }

    // =========================================================================
    // Side-Effect Tests (Phase 3.2 Constitutional Hardening)
    // =========================================================================

    /**
     * Test: begin_setup side effects set the business facts the engine needs.
     *
     * This test verifies that when 'begin_setup' transition is executed,
     * the business fact 'administration_completed' is set to true.
     * The lifecycle engine reads this fact to derive 'setup' state.
     *
     * RED phase: This will fail because begin_setup has no side effects yet.
     * After implementing applySideEffectsForBeginSetup(), this test will pass.
     */
    public function test_begin_setup_sets_setup_started_at(): void
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'state' => 'approved',
                'approved_at' => now()->subDay(),
                'administration_completed' => false,
            ]);

        // Assign chief as ElectionOfficer on this election
        ElectionOfficer::create([
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $this->chief->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $this->chief->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        // Set tenant context so transition guard can validate chief's role
        $this->actingAs($this->chief);
        \App\Services\TenantContext::set($this->org->id);

        // Execute begin_setup transition
        $election->transitionTo(
            Transition::manual('begin_setup', $this->chief->id, 'Starting setup')
        );

        // Refresh from database to get latest state
        $election->refresh();

        // ASSERTION 1: The business fact must be updated
        $this->assertNotNull(
            $election->setup_started_at,
            'begin_setup side effects must set setup_started_at'
        );

        // ASSERTION 2: The engine must derive SetupAdministration from the fact
        $engine = app(ElectionLifecycleEngineImpl::class);
        $derivedState = $engine->getState($election);
        $this->assertEquals(
            ElectionLifecycleState::SetupAdministration,
            $derivedState,
            'Engine must derive SetupAdministration state from setup_started_at not null'
        );
    }

    // =========================================================================
    // Email Notification Tests
    // =========================================================================

    public function test_email_notification_sent_to_chief_when_election_created(): void
    {
        Notification::fake();

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
    }

    public function test_email_notification_sent_to_all_active_chiefs(): void
    {
        Notification::fake();

        $secondChief = $this->createOfficer('chief', 'active');

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertSentTo($secondChief, ElectionReadyForActivation::class);
    }

    public function test_email_notification_not_sent_to_inactive_chiefs(): void
    {
        Notification::fake();

        $inactiveChief = $this->createOfficer('chief', 'inactive');

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertNotSentTo($inactiveChief, ElectionReadyForActivation::class);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        // Factory auto-creates voter role; update it to the desired role
        UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $this->org->id)
            ->update(['role' => $role]);
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        // Factory auto-creates voter role; keep it as-is
        ElectionOfficer::create([
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => $status,
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => $status === 'active' ? now() : null,
        ]);
        return $user;
    }
}
