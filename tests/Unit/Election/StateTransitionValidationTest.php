<?php

namespace Tests\Unit\Election;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TDD: Test state transition validation rules
 *
 * Ensures that elections can only transition between valid states
 * and that business conditions are checked before allowing transitions
 */
class StateTransitionValidationTest extends TestCase
{
    use RefreshDatabase;

    // ============================================================
    // VALID TRANSITIONS
    // ============================================================

    /** @test */
    public function allows_transition_from_administration_to_nomination()
    {
        // Test that when admin is complete with required setup, nomination is allowed
        $election = Election::factory()->demo()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'posts_count' => 3,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
        ]);

        // Election is now in nomination state, verify it can be in that state
        $this->assertEquals('nomination', $election->current_state);
        $this->assertTrue($election->canEnterNominationPhase());
    }

    /** @test */
    public function allows_transition_from_nomination_to_voting()
    {
        // Test that when nomination is complete with candidates, voting is allowed
        $election = Election::factory()->demo()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
        ]);

        // Election should be in nomination state waiting for voting window
        $this->assertTrue($election->canEnterVotingPhase());
    }

    /** @test */
    public function allows_transition_from_voting_to_counting()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->subMinute(),
            'voting_locked' => true,
            'votes_count' => 50,
        ]);

        // Election is now in counting state, verify conditions are met to enter it
        $this->assertEquals('counting', $election->current_state);
        $this->assertTrue($election->canEnterCountingPhase());
    }

    // ============================================================
    // INVALID TRANSITIONS
    // ============================================================

    /** @test */
    public function rejects_transition_to_voting_without_candidates()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'candidates_count' => 0,
        ]);

        $this->assertFalse($election->canTransitionTo('voting'));
    }

    /** @test */
    public function rejects_transition_to_voting_with_pending_candidacies()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 2,
        ]);

        $this->assertFalse($election->canTransitionTo('voting'));
    }

    /** @test */
    public function rejects_transition_to_counting_while_voting_active()
    {
        $election = Election::factory()->demo()->create([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $this->assertFalse($election->canTransitionTo('counting'));
    }

    /** @test */
    public function rejects_transition_to_counting_without_votes()
    {
        $election = Election::factory()->demo()->create([
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 0,
        ]);

        $this->assertFalse($election->canTransitionTo('counting'));
    }

    // ============================================================
    // BACKWARD TRANSITIONS (NOT ALLOWED)
    // ============================================================

    /** @test */
    public function rejects_transition_backward_from_voting_to_nomination()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $this->assertFalse($election->canTransitionTo('nomination'));
    }

    /** @test */
    public function rejects_transition_backward_from_results_to_voting()
    {
        $election = Election::factory()->demo()->create([
            'results_published_at' => now(),
        ]);

        $this->assertFalse($election->canTransitionTo('voting'));
    }

    // ============================================================
    // SKIPPED TRANSITIONS (NOT ALLOWED)
    // ============================================================

    /** @test */
    public function rejects_jumping_from_nomination_directly_to_counting()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
        ]);

        $this->assertFalse($election->canTransitionTo('counting'));
    }

    /** @test */
    public function rejects_jumping_from_administration_directly_to_voting()
    {
        $election = Election::factory()->demo()->create([
            'administration_completed' => false,
        ]);

        $this->assertFalse($election->canTransitionTo('voting'));
    }

    // ============================================================
    // TRANSITION REASON TRACKING
    // ============================================================

    /** @test */
    public function can_provide_reason_for_blocked_transition()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'candidates_count' => 0,
        ]);

        $blocked = $election->getTransitionBlockedReason('voting');

        $this->assertIsString($blocked);
        $this->assertStringContainsString('candidate', strtolower($blocked));
    }

    /** @test */
    public function can_list_all_blocking_reasons()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => false,
            'candidates_count' => 0,
            'pending_candidacies_count' => 3,
        ]);

        $reasons = $election->getTransitionBlockedReasons('voting');

        $this->assertIsArray($reasons);
        $this->assertGreaterThan(0, count($reasons));
    }

    // ============================================================
    // MINIMUM CANDIDATE CONFIGURATION
    // ============================================================

    /** @test */
    public function respects_minimum_candidates_config_for_transition()
    {
        config(['election.min_candidates_for_voting' => 3]);

        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'candidates_count' => 2,
            'pending_candidacies_count' => 0,
        ]);

        $this->assertFalse($election->canTransitionTo('voting'));
    }
}
