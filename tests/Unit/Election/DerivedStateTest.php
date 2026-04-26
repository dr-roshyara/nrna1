<?php

namespace Tests\Unit\Election;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TDD: Test derived state logic with business conditions
 *
 * State derivation combines:
 * - Time windows (voting_starts_at, voting_ends_at, results_published_at)
 * - Business conditions (candidates exist, votes exist, etc.)
 * - Explicit completion flags (administration_completed, nomination_completed)
 */
class DerivedStateTest extends TestCase
{
    use RefreshDatabase;

    // ============================================================
    // ADMINISTRATION STATE
    // ============================================================

    /** @test */
    public function returns_administration_state_for_new_election()
    {
        $election = Election::factory()->demo()->create([
            'administration_completed' => false,
            'nomination_completed' => false,
            'posts_count' => 1,
            'voters_count' => 1,
            'election_committee_members_count' => 1,
        ]);

        $this->assertEquals('administration', $election->current_state);
    }

    /** @test */
    public function returns_draft_state_when_missing_setup_requirements()
    {
        $election = Election::factory()->demo()->create([
            'administration_completed' => false,
            'posts_count' => 0,
        ]);

        $this->assertEquals('draft', $election->current_state);
    }

    // ============================================================
    // NOMINATION STATE
    // ============================================================

    /** @test */
    public function returns_nomination_state_when_administration_completed()
    {
        $election = Election::factory()->demo()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'posts_count' => 3,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
        ]);

        $this->assertEquals('nomination', $election->current_state);
    }

    /** @test */
    public function returns_nomination_blocked_when_admin_completed_but_missing_candidates()
    {
        $election = Election::factory()->demo()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'candidates_count' => 0,
        ]);

        $this->assertEquals('nomination_blocked', $election->current_state);
    }

    // ============================================================
    // VOTING STATE
    // ============================================================

    /** @test */
    public function returns_voting_state_when_time_window_active_and_conditions_met()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
        ]);

        $this->assertEquals('voting', $election->current_state);
    }

    /** @test */
    public function returns_voting_blocked_when_time_active_but_no_candidates()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'candidates_count' => 0,
            'pending_candidacies_count' => 0,
        ]);

        $this->assertEquals('voting_blocked', $election->current_state);
    }

    /** @test */
    public function returns_voting_blocked_when_pending_candidacies_exist()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'candidates_count' => 5,
            'pending_candidacies_count' => 2,
        ]);

        $this->assertEquals('voting_blocked', $election->current_state);
    }

    /** @test */
    public function returns_voting_state_only_during_voting_window()
    {
        $election = Election::factory()->demo()->create([
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
            'voting_starts_at' => now()->addDay(),
            'voting_ends_at' => now()->addDays(5),
        ]);

        $this->assertNotEquals('voting', $election->current_state);
    }

    // ============================================================
    // COUNTING/RESULTS_PENDING STATE
    // ============================================================

    /** @test */
    public function returns_counting_state_after_voting_window_ends()
    {
        $election = Election::factory()->demo()->create([
            'voting_starts_at' => now()->subDays(5),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 50,
            'results_published_at' => null,
        ]);

        $this->assertEquals('counting', $election->current_state);
    }

    /** @test */
    public function returns_counting_blocked_when_voting_not_locked()
    {
        $election = Election::factory()->demo()->create([
            'voting_starts_at' => now()->subDays(5),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => false,
            'votes_count' => 50,
            'results_published_at' => null,
        ]);

        $this->assertEquals('counting_blocked', $election->current_state);
    }

    /** @test */
    public function returns_counting_blocked_when_no_votes_recorded()
    {
        $election = Election::factory()->demo()->create([
            'voting_starts_at' => now()->subDays(5),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 0,
            'results_published_at' => null,
        ]);

        $this->assertEquals('counting_blocked', $election->current_state);
    }

    // ============================================================
    // RESULTS STATE
    // ============================================================

    /** @test */
    public function returns_results_state_when_results_published()
    {
        $election = Election::factory()->demo()->create([
            'results_published_at' => now(),
        ]);

        $this->assertEquals('results', $election->current_state);
    }

    /** @test */
    public function returns_results_state_regardless_of_other_conditions()
    {
        $election = Election::factory()->demo()->create([
            'results_published_at' => now(),
            'voting_starts_at' => null,
            'voting_ends_at' => null,
            'administration_completed' => false,
        ]);

        $this->assertEquals('results', $election->current_state);
    }

    // ============================================================
    // STATE TRANSITION SEQUENCES
    // ============================================================

    /** @test */
    public function state_transitions_through_expected_sequence()
    {
        $election = Election::factory()->demo()->create([
            'posts_count' => 3,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
            'administration_completed' => false,
            'nomination_completed' => false,
        ]);

        // Start: administration
        $this->assertEquals('administration', $election->current_state);

        // Complete administration
        $election->update(['administration_completed' => true]);
        $this->assertEquals('nomination', $election->current_state);

        // Add candidates
        $election->update([
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
        ]);
        $this->assertEquals('nomination', $election->current_state);

        // Start voting window
        $election->update([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        $this->assertEquals('voting', $election->current_state);

        // End voting window
        $election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 50,
        ]);
        $this->assertEquals('counting', $election->current_state);

        // Publish results
        $election->update(['results_published_at' => now()]);
        $this->assertEquals('results', $election->current_state);
    }
}
