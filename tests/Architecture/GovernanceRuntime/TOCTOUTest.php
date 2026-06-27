<?php

namespace Tests\Architecture\GovernanceRuntime;

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
 * Time-of-Check / Time-of-Use (TOCTOU) Tests
 *
 * Verifies that the dual-gate architecture correctly handles race conditions
 * between canVote() checks at ballot rendering (create) and vote persistence (store).
 *
 * INVARIANT F: Both create() and store() MUST independently verify canVote().
 * A failure at EITHER gate MUST deny the vote.
 *
 * CRITICAL SCENARIO: What happens BETWEEN create() and store()?
 * - Election could be suspended
 * - Voting window could expire
 * - Election could be archived
 */
class TOCTOUTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * RED: Ballot rendered during VotingActive → election SUSPENDED → submission MUST be rejected.
     *
     * This tests the TOCTOU gap between create() and store() with an overlay change.
     * INVARIANT H: Suspension override — canVote() must return false when suspended.
     */
    public function test_vote_submission_rejected_when_suspended_after_ballot_render(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Verify canVote() is true (ballot can be rendered)
        $this->assertTrue(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() must be true before suspension'
        );

        // SUSPEND the election (simulating governance action between create() and store())
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'TOCTOU test — emergency governance hold',
        ]);

        // Verify canVote() is now false
        $this->assertFalse(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() must be false after suspension — overlay short-circuits lifecycle'
        );

        // Attempt to submit vote via store() → MUST reject
        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 3,
        ]);

        $response = $this->actingAs($user)
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => 'test-code',
            ]);

        // store() MUST reject — 302 redirect with error
        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Ballot rendered during VotingActive → voting window EXPIRES → submission MUST be rejected.
     *
     * This tests TOCTOU with window expiry between create() and store().
     */
    public function test_vote_submission_rejected_when_window_expires_after_ballot_render(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Verify canVote() is true
        $this->assertTrue(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() must be true while voting window is open'
        );

        // Move voting_ends_at to the past (simulating window expiry between create/store)
        $election->update([
            'voting_ends_at' => now()->subMinute(),
        ]);

        // Verify canVote() is now false
        $lifecycle = ElectionLifecycle::of($election->fresh());
        $this->assertFalse(
            $lifecycle->canVote(),
            'canVote() must be false after voting window expires'
        );

        // Verify derived state is Counting (not VotingActive)
        $this->assertEquals(
            ElectionLifecycleState::Counting,
            $lifecycle->state(),
            'Election should be in Counting state after voting window expires'
        );

        // Attempt to submit vote via store() → MUST reject
        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 3,
        ]);

        $response = $this->actingAs($user)
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => 'test-code',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    /**
     * RED: Ballot rendered during VotingActive → results PUBLISHED → submission MUST be rejected.
     *
     * Tests TOCTOU with terminal lifecycle progression.
     */
    public function test_vote_submission_rejected_when_results_published_after_ballot_render(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        // Verify canVote() is true
        $this->assertTrue(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() must be true before results are published'
        );

        // PUBLISH results (terminal progression)
        $election->update([
            'results_published_at' => now(),
            'voting_ends_at' => now()->subMinute(), // Must end voting too
        ]);

        // Verify canVote() is now false
        $this->assertFalse(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() must be false after results are published'
        );

        // Attempt to submit vote → MUST reject
        $slug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 3,
        ]);

        $response = $this->actingAs($user)
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => 'test-code',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }
}
