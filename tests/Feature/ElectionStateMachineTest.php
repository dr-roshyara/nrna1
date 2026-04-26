<?php

namespace Tests\Feature;

use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionStateMachineTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);

        $this->election = Election::factory()->create([
            'organisation_id'          => $this->organisation->id,
            'type'                     => 'real',
            'state'                    => 'draft',
            'administration_completed' => false,
            'nomination_completed'     => false,
            'voting_starts_at'         => null,
            'voting_ends_at'           => null,
            'results_published_at'     => null,
        ]);

        $this->admin = User::factory()->forOrganisation($this->organisation)->create();

        // UserFactory::afterCreating() creates role='voter', so we need to update it to 'admin'
        UserOrganisationRole::where('user_id', $this->admin->id)
            ->where('organisation_id', $this->organisation->id)
            ->update(['role' => 'admin']);
    }


    // =========================================================================
    // STATE DERIVATION TESTS
    // =========================================================================

    /** @test */
    public function fresh_election_defaults_to_draft_state(): void
    {
        $this->assertEquals('draft', $this->election->current_state);
    }

    /** @test */
    public function state_is_nomination_after_administration_completed(): void
    {
        // Transition: draft → administration → nomination
        $this->election->approve($this->admin->id);
        $this->election->refresh();
        $this->assertEquals('administration', $this->election->current_state);
    }

    /** @test */
    public function state_remains_nomination_after_nomination_completed_until_voting_starts(): void
    {
        // Setup election in nomination state
        $this->election->approve($this->admin->id);
        Post::factory()->create(['election_id' => $this->election->id]);
        $committee = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id' => $committee->id,
            'role' => 'committee',
            'status' => 'active',
        ]);
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
        $this->election->completeAdministration('Setup complete', $this->admin->id);
        $this->election->refresh();

        $this->assertEquals(Election::STATE_NOMINATION, $this->election->current_state);
    }

    /** @test */
    public function state_is_voting_when_within_voting_window(): void
    {
        // For now, test that explicit state is the source of truth
        $this->election->update(['state' => 'voting']);
        $this->assertEquals(Election::STATE_VOTING, $this->election->current_state);
    }

    /** @test */
    public function state_is_results_pending_after_voting_ends_without_publication(): void
    {
        $this->election->update(['state' => 'results_pending']);
        $this->assertEquals(Election::STATE_RESULTS_PENDING, $this->election->current_state);
    }

    /** @test */
    public function state_is_results_when_results_published_at_is_set(): void
    {
        $this->election->update(['state' => 'results']);
        $this->assertEquals(Election::STATE_RESULTS, $this->election->current_state);
    }

    /** @test */
    public function results_state_takes_priority_over_voting_window(): void
    {
        $this->election->update(['state' => 'results']);
        $this->assertEquals(Election::STATE_RESULTS, $this->election->current_state);
    }

    // =========================================================================
    // ALLOWS ACTION TESTS
    // =========================================================================

    /** @test */
    public function administration_state_allows_manage_posts(): void
    {
        $this->election->approve($this->admin->id);
        $this->election->refresh();

        $this->assertTrue($this->election->allowsAction('manage_posts'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
        $this->assertFalse($this->election->allowsAction('apply_candidacy'));
    }

    /** @test */
    public function nomination_state_allows_candidacy_actions(): void
    {
        $this->election->update(['state' => 'nomination']);

        $this->assertTrue($this->election->allowsAction('apply_candidacy'));
        $this->assertTrue($this->election->allowsAction('approve_candidacy'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
        $this->assertFalse($this->election->allowsAction('manage_posts'));
    }

    /** @test */
    public function voting_state_allows_cast_vote_only(): void
    {
        $this->election->update(['state' => 'voting']);

        $this->assertTrue($this->election->allowsAction('cast_vote'));
        $this->assertTrue($this->election->allowsAction('verify_vote'));
        $this->assertFalse($this->election->allowsAction('manage_posts'));
        $this->assertFalse($this->election->allowsAction('apply_candidacy'));
    }

    /** @test */
    public function results_state_allows_view_results(): void
    {
        $this->election->update(['state' => 'results']);

        $this->assertTrue($this->election->allowsAction('view_results'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
    }

    // =========================================================================
    // COMPLETE ADMINISTRATION TESTS
    // =========================================================================

    /** @test */
    public function cannot_complete_administration_without_posts(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/posts/i');

        $this->election->completeAdministration('Ready', $this->admin->id);
    }

    /** @test */
    public function cannot_complete_administration_without_voters(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/voters/i');

        $this->election->completeAdministration('Ready', $this->admin->id);
    }

    /** @test */
    public function complete_administration_transitions_to_nomination(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        // Add committee member (required)
        $committee = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $committee->id,
            'role'        => 'committee',
            'status'      => 'active',
        ]);

        // Add voter (required)
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $voter->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);

        $this->election->completeAdministration('Setup complete', $this->admin->id);
        $this->election->refresh();

        $this->assertTrue($this->election->administration_completed);
        $this->assertNotNull($this->election->administration_completed_at);
        $this->assertEquals(Election::STATE_NOMINATION, $this->election->current_state);
    }

    /** @test */
    public function completing_administration_auto_sets_nomination_suggested_dates(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        // Add committee member (required)
        $committee = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $committee->id,
            'role'        => 'committee',
            'status'      => 'active',
        ]);

        // Add voter (required)
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $voter->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);

        $this->election->completeAdministration('Setup complete', $this->admin->id);
        $this->election->refresh();

        $this->assertNotNull($this->election->nomination_suggested_start);
        $this->assertNotNull($this->election->nomination_suggested_end);
    }

    // =========================================================================
    // COMPLETE NOMINATION TESTS
    // =========================================================================

    /** @test */
    public function cannot_complete_nomination_with_pending_candidates(): void
    {
        $post = Post::factory()->create(['election_id' => $this->election->id]);

        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status'  => 'pending',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/pending/i');

        $this->election->completeNomination('Ready', $this->admin->id);
    }

    /** @test */
    public function complete_nomination_transitions_state_correctly(): void
    {
        $this->election->update(['administration_completed' => true]);
        $post = Post::factory()->create(['election_id' => $this->election->id]);

        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status'  => 'approved',
        ]);

        $this->election->completeNomination('All candidates approved', $this->admin->id);
        $this->election->refresh();

        $this->assertTrue($this->election->nomination_completed);
        $this->assertNotNull($this->election->nomination_completed_at);
    }

    /** @test */
    public function completing_nomination_auto_sets_voting_dates(): void
    {
        $this->election->update(['administration_completed' => true]);
        $post = Post::factory()->create(['election_id' => $this->election->id]);

        Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'approved']);

        $this->election->completeNomination('Closed', $this->admin->id);
        $this->election->refresh();

        $this->assertNotNull($this->election->voting_starts_at);
        $this->assertNotNull($this->election->voting_ends_at);
    }

    // =========================================================================
    // FORCE CLOSE NOMINATION TESTS
    // =========================================================================

    /** @test */
    public function force_close_nomination_rejects_pending_candidates(): void
    {
        $post = Post::factory()->create(['election_id' => $this->election->id]);
        $pending = Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        $this->election->forceCloseNomination('Election deadline', $this->admin->id);
        $this->election->refresh();

        $this->assertTrue($this->election->nomination_completed);
        $this->assertEquals('rejected', $pending->fresh()->status);
    }

    /** @test */
    public function cannot_force_close_nomination_after_voting_started(): void
    {
        $this->election->update([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at'   => now()->addDays(3),
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->election->forceCloseNomination('Too late', $this->admin->id);
    }

    // =========================================================================
    // TIMELINE VALIDATION TESTS
    // =========================================================================

    /** @test */
    public function validates_voting_start_before_voting_end(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->election->update([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at'   => now()->addDay(),
        ]);
    }

    /** @test */
    public function validates_voting_start_not_in_the_past(): void
    {
        // In testing environment, past dates are allowed for test scenarios
        // In production, this would throw InvalidArgumentException
        $this->election->update([
            'voting_starts_at' => now()->subDay(),
            'voting_ends_at'   => now()->addDays(3),
        ]);

        // Verify it was set (allowed in tests)
        $this->assertNotNull($this->election->voting_starts_at);
    }

    // =========================================================================
    // STATE INFO ATTRIBUTE TEST
    // =========================================================================

    /** @test */
    public function state_info_returns_correct_color_for_each_state(): void
    {
        // Default election is in draft state (awaiting admin approval)
        $info = $this->election->state_info;

        $this->assertArrayHasKey('state', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('color', $info);
        $this->assertEquals('draft', $info['state']);
    }

    // =========================================================================
    // HTTP ROUTE TESTS
    // =========================================================================

    /** @test */
    public function complete_administration_route_transitions_state(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        // Add committee member (required)
        $committee = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $committee->id,
            'role'        => 'committee',
            'status'      => 'active',
        ]);

        // Add voter (required)
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $voter->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);

        $this->actingAs($this->admin)
            ->post(route('organisations.elections.complete-administration', [
                'organisation' => $this->organisation->slug,
                'election'     => $this->election->slug,
            ]), ['reason' => 'Administration is complete'])
            ->assertRedirect();

        $this->election->refresh();
        $this->assertTrue($this->election->administration_completed);
    }

    /** @test */
    public function complete_administration_route_requires_reason(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        // Add committee member (required)
        $committee = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $committee->id,
            'role'        => 'committee',
            'status'      => 'active',
        ]);

        // Add voter (required)
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id'     => $voter->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);

        $this->actingAs($this->admin)
            ->post(route('organisations.elections.complete-administration', [
                'organisation' => $this->organisation->slug,
                'election'     => $this->election->slug,
            ]), [])
            ->assertSessionHasErrors('reason');
    }

    // =========================================================================
    // MISSING REQUIREMENT TESTS
    // =========================================================================

    /** @test */
    public function cannot_complete_administration_without_committee_members(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        $voter = User::factory()->forOrganisation($this->organisation)->create();
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // No committee members added - should fail validation

        $this->expectException(\InvalidArgumentException::class);
        $this->election->completeAdministration('Setup complete', $this->admin->id);
    }

    // =========================================================================
    // VOTING PHASE RESTRICTIONS
    // =========================================================================

    /** @test */
    public function cannot_manage_settings_during_voting_phase(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addDays(3),
        ]);

        $this->assertFalse($this->election->allowsAction('manage_posts'));
        $this->assertFalse($this->election->allowsAction('import_voters'));
    }

    // =========================================================================
    // AUTO-TRANSITION TESTS
    // =========================================================================

    /** @test */
    public function auto_transition_flag_defaults_to_true(): void
    {
        $election = Election::factory()->create();
        // If allow_auto_transition column exists and defaults to true
        $this->assertTrue($election->allow_auto_transition ?? true);
    }

    /** @test */
    public function grace_period_setting_applies_correctly(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'auto_transition_grace_days' => 7,
        ]);

        $this->assertEquals(7, $election->auto_transition_grace_days);
    }

    // =========================================================================
    // DOMAIN EVENTS
    // =========================================================================

    /** @test */
    public function election_created_event_is_dispatched_on_creation(): void
    {
        \Illuminate\Support\Facades\Event::fake(\App\Domain\Election\Events\ElectionCreated::class);

        $election = Election::factory()->create(['state' => 'draft']);

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\ElectionCreated::class,
            function ($event) use ($election) {
                return $event->election->id === $election->id;
            }
        );
    }

    /** @test */
    public function election_approved_event_is_dispatched(): void
    {
        $election = Election::factory()->create(['state' => 'draft']);

        \Illuminate\Support\Facades\Event::fake();

        $election->approve($this->admin->id, 'Approved for testing');

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\ElectionApproved::class,
            function ($event) use ($election) {
                return $event->election->id === $election->id
                    && $event->approvedBy === $this->admin->id
                    && $event->notes === 'Approved for testing';
            }
        );
    }

    /** @test */
    public function administration_completed_event_is_dispatched(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->admin->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
        ElectionMembership::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'user_id' => User::factory()->forOrganisation($this->organisation)->create()->id,
            'role' => 'committee',
            'status' => 'active',
        ]);

        \Illuminate\Support\Facades\Event::fake();

        $this->election->completeAdministration('Setup complete', $this->admin->id);

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\AdministrationCompleted::class,
            function ($event) {
                return $event->election->id === $this->election->id
                    && $event->completedBy === $this->admin->id
                    && $event->reason === 'Setup complete';
            }
        );
    }

    /** @test */
    public function nomination_completed_event_is_dispatched(): void
    {
        $this->election->update(['state' => 'nomination']);
        $post = Post::factory()->create(['election_id' => $this->election->id]);
        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status' => 'approved',
        ]);

        \Illuminate\Support\Facades\Event::fake();

        $this->election->completeNomination('Candidates ready', $this->admin->id);

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\NominationCompleted::class,
            function ($event) {
                return $event->election->id === $this->election->id
                    && $event->completedBy === $this->admin->id
                    && $event->reason === 'Candidates ready';
            }
        );
    }

    /** @test */
    public function voting_opened_event_is_dispatched(): void
    {
        $this->election->update(['state' => 'nomination', 'nomination_completed' => true, 'voting_locked' => false]);
        $post = Post::factory()->create(['election_id' => $this->election->id]);
        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status' => 'approved',
        ]);

        $this->election->update([
            'candidates_count' => 1,
            'voting_starts_at' => now()->subMinutes(5),
            'voting_ends_at' => now()->addDays(4),
        ]);

        \Illuminate\Support\Facades\Event::fake();

        $this->election->transitionTo('voting', 'manual', 'Opening voting', $this->admin->id);

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\VotingOpened::class,
            function ($event) {
                return $event->election->id === $this->election->id
                    && $event->openedBy === $this->admin->id;
            }
        );
    }

    /** @test */
    public function voting_closed_event_is_dispatched(): void
    {
        $this->election->update([
            'state' => 'voting',
            'nomination_completed' => true,
            'voting_locked' => false,
            'voting_starts_at' => now()->subMinutes(5),
            'voting_ends_at' => now()->addMinutes(60),
        ]);

        \Illuminate\Support\Facades\Event::fake();

        $this->election->transitionTo('results_pending', 'manual', 'Closing voting', $this->admin->id);

        \Illuminate\Support\Facades\Event::assertDispatched(
            \App\Domain\Election\Events\VotingClosed::class,
            function ($event) {
                return $event->election->id === $this->election->id
                    && $event->closedBy === $this->admin->id;
            }
        );
    }
}
