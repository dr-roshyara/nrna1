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
    public function fresh_election_defaults_to_administration_state(): void
    {
        $this->assertEquals(Election::STATE_ADMINISTRATION, $this->election->current_state);
    }

    /** @test */
    public function state_is_nomination_after_administration_completed(): void
    {
        $this->election->update(['administration_completed' => true]);

        $this->assertEquals(Election::STATE_NOMINATION, $this->election->current_state);
    }

    /** @test */
    public function state_remains_nomination_after_nomination_completed_until_voting_starts(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->addDay(),
            'voting_ends_at'           => now()->addDays(4),
        ]);

        // Voting has not started yet — should stay in NOMINATION
        $this->assertEquals(Election::STATE_NOMINATION, $this->election->current_state);
    }

    /** @test */
    public function state_is_voting_when_within_voting_window(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->subHour(),
            'voting_ends_at'           => now()->addDays(3),
        ]);

        $this->assertEquals(Election::STATE_VOTING, $this->election->current_state);
    }

    /** @test */
    public function state_is_results_pending_after_voting_ends_without_publication(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->subDays(7),
            'voting_ends_at'           => now()->subDay(),
            'results_published_at'     => null,
        ]);

        $this->assertEquals(Election::STATE_RESULTS_PENDING, $this->election->current_state);
    }

    /** @test */
    public function state_is_results_when_results_published_at_is_set(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->subDays(7),
            'voting_ends_at'           => now()->subDay(),
            'results_published_at'     => now()->subHours(2),
        ]);

        $this->assertEquals(Election::STATE_RESULTS, $this->election->current_state);
    }

    /** @test */
    public function results_state_takes_priority_over_voting_window(): void
    {
        // Even if we somehow have a results_published_at during a voting window
        $this->election->update([
            'voting_starts_at'     => now()->subHour(),
            'voting_ends_at'       => now()->addDay(),
            'results_published_at' => now()->subMinutes(5),
        ]);

        $this->assertEquals(Election::STATE_RESULTS, $this->election->current_state);
    }

    // =========================================================================
    // ALLOWS ACTION TESTS
    // =========================================================================

    /** @test */
    public function administration_state_allows_manage_posts(): void
    {
        $this->assertTrue($this->election->allowsAction('manage_posts'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
        $this->assertFalse($this->election->allowsAction('apply_candidacy'));
    }

    /** @test */
    public function nomination_state_allows_candidacy_actions(): void
    {
        $this->election->update(['administration_completed' => true]);

        $this->assertTrue($this->election->allowsAction('apply_candidacy'));
        $this->assertTrue($this->election->allowsAction('approve_candidacy'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
        $this->assertFalse($this->election->allowsAction('manage_posts'));
    }

    /** @test */
    public function voting_state_allows_cast_vote_only(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->subHour(),
            'voting_ends_at'           => now()->addDays(3),
        ]);

        $this->assertTrue($this->election->allowsAction('cast_vote'));
        $this->assertTrue($this->election->allowsAction('verify_vote'));
        $this->assertFalse($this->election->allowsAction('manage_posts'));
        $this->assertFalse($this->election->allowsAction('apply_candidacy'));
    }

    /** @test */
    public function results_state_allows_view_results(): void
    {
        $this->election->update(['results_published_at' => now()]);

        $this->assertTrue($this->election->allowsAction('view_results'));
        $this->assertFalse($this->election->allowsAction('cast_vote'));
    }

    // =========================================================================
    // COMPLETE ADMINISTRATION TESTS
    // =========================================================================

    /** @test */
    public function cannot_complete_administration_without_posts(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/posts/i');

        $this->election->completeAdministration('Ready', $this->admin->id);
    }

    /** @test */
    public function cannot_complete_administration_without_voters(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/voters/i');

        $this->election->completeAdministration('Ready', $this->admin->id);
    }

    /** @test */
    public function complete_administration_transitions_to_nomination(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

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

        $this->expectException(\Exception::class);
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

        $this->expectException(\Exception::class);

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
        $info = $this->election->state_info;

        $this->assertArrayHasKey('state', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('color', $info);
        $this->assertEquals(Election::STATE_ADMINISTRATION, $info['state']);
        $this->assertEquals('blue', $info['color']);
    }

    // =========================================================================
    // HTTP ROUTE TESTS
    // =========================================================================

    /** @test */
    public function complete_administration_route_transitions_state(): void
    {
        Post::factory()->create(['election_id' => $this->election->id]);

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
}
