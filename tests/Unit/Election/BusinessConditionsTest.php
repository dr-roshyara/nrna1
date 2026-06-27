<?php

namespace Tests\Unit\Election;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TDD: Test business condition validation for each phase
 *
 * RED phase: These tests establish WHAT must be validated
 * before state transitions can occur
 */
class BusinessConditionsTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->election = Election::factory()->demo()->create([
            'administration_completed' => false,
            'nomination_completed' => false,
            'posts_count' => 1,
            'voters_count' => 1,
            'election_committee_members_count' => 1,
            'voting_starts_at' => now()->addDay(),
            'voting_ends_at' => now()->addDays(5),
        ]);
    }

    // ============================================================
    // ADMINISTRATION PHASE CONDITIONS
    // ============================================================

    /** @test */
    public function can_enter_administration_phase_when_election_created()
    {
        $this->assertTrue($this->election->canEnterAdministrationPhase());
    }

    /** @test */
    public function cannot_enter_administration_phase_without_posts()
    {
        $this->election->update(['posts_count' => 0]);
        $this->assertFalse($this->election->canEnterAdministrationPhase());
    }

    /** @test */
    public function cannot_enter_administration_phase_without_voters()
    {
        $this->election->update(['voters_count' => 0]);
        $this->assertFalse($this->election->canEnterAdministrationPhase());
    }

    /** @test */
    public function cannot_enter_administration_phase_without_committee_members()
    {
        $this->election->update(['election_committee_members_count' => 0]);
        $this->assertFalse($this->election->canEnterAdministrationPhase());
    }

    /** @test */
    public function can_enter_administration_when_all_conditions_met()
    {
        $this->election->update([
            'posts_count' => 3,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
        ]);
        $this->assertTrue($this->election->canEnterAdministrationPhase());
    }

    // ============================================================
    // NOMINATION PHASE CONDITIONS
    // ============================================================

    /** @test */
    public function cannot_enter_nomination_without_administration_completed()
    {
        $this->election->update(['administration_completed' => false]);
        $this->assertFalse($this->election->canEnterNominationPhase());
    }

    /** @test */
    public function can_enter_nomination_when_administration_completed_and_conditions_met()
    {
        $this->election->update([
            'administration_completed' => true,
            'posts_count' => 3,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
        ]);
        $this->assertTrue($this->election->canEnterNominationPhase());
    }

    /** @test */
    public function cannot_enter_nomination_without_posts_even_if_admin_completed()
    {
        $this->election->update([
            'administration_completed' => true,
            'posts_count' => 0,
            'voters_count' => 100,
            'election_committee_members_count' => 5,
        ]);
        $this->assertFalse($this->election->canEnterNominationPhase());
    }

    /** @test */
    public function cannot_enter_nomination_without_voters_even_if_admin_completed()
    {
        $this->election->update([
            'administration_completed' => true,
            'posts_count' => 3,
            'voters_count' => 0,
            'election_committee_members_count' => 5,
        ]);
        $this->assertFalse($this->election->canEnterNominationPhase());
    }

    // ============================================================
    // VOTING PHASE CONDITIONS
    // ============================================================

    /** @test */
    public function cannot_enter_voting_without_nomination_completed()
    {
        $this->election->update([
            'nomination_completed' => false,
            'candidates_count' => 5,
        ]);
        $this->assertFalse($this->election->canEnterVotingPhase());
    }

    /** @test */
    public function cannot_enter_voting_without_candidates()
    {
        $this->election->update([
            'nomination_completed' => true,
            'candidates_count' => 0,
        ]);
        $this->assertFalse($this->election->canEnterVotingPhase());
    }

    /** @test */
    public function cannot_enter_voting_with_pending_candidacies()
    {
        $this->election->update([
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 2,
        ]);
        $this->assertFalse($this->election->canEnterVotingPhase());
    }

    /** @test */
    public function can_enter_voting_when_all_conditions_met()
    {
        $this->election->update([
            'nomination_completed' => true,
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
        ]);
        $this->assertTrue($this->election->canEnterVotingPhase());
    }

    // ============================================================
    // COUNTING PHASE CONDITIONS
    // ============================================================

    /** @test */
    public function cannot_enter_counting_while_voting_active()
    {
        $this->election->update([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'voting_locked' => false,
        ]);
        $this->assertFalse($this->election->canEnterCountingPhase());
    }

    /** @test */
    public function cannot_enter_counting_without_voting_locked()
    {
        $this->election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => false,
        ]);
        $this->assertFalse($this->election->canEnterCountingPhase());
    }

    /** @test */
    public function cannot_enter_counting_without_votes()
    {
        $this->election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 0,
        ]);
        $this->assertFalse($this->election->canEnterCountingPhase());
    }

    /** @test */
    public function can_enter_counting_when_voting_ended_and_locked()
    {
        $this->election->update([
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHour(),
            'voting_locked' => true,
            'votes_count' => 50,
        ]);
        $this->assertTrue($this->election->canEnterCountingPhase());
    }

    // ============================================================
    // RESULTS PHASE CONDITIONS
    // ============================================================

    /** @test */
    public function can_enter_results_when_results_published()
    {
        $this->election->update([
            'results_published_at' => now(),
        ]);
        $this->assertTrue($this->election->canEnterResultsPhase());
    }

    /** @test */
    public function cannot_enter_results_until_published()
    {
        $this->election->update([
            'results_published_at' => null,
            'counting_completed' => false,
        ]);
        $this->assertFalse($this->election->canEnterResultsPhase());
    }

    // ============================================================
    // PHASE BLOCKED REASON MESSAGES
    // ============================================================

    /** @test */
    public function returns_blocked_reason_when_cannot_enter_voting()
    {
        $this->election->update([
            'nomination_completed' => true,
            'candidates_count' => 0,
            'pending_candidacies_count' => 0,
        ]);

        $reason = $this->election->getVotingPhaseBlockedReason();

        $this->assertEquals('no_candidates', $reason);
    }

    /** @test */
    public function returns_multiple_blocked_reasons_when_applicable()
    {
        $this->election->update([
            'nomination_completed' => false,
            'candidates_count' => 0,
            'pending_candidacies_count' => 3,
        ]);

        $reasons = $this->election->getVotingPhaseBlockedReasons();

        $this->assertContains('nomination_incomplete', $reasons);
        $this->assertContains('no_candidates', $reasons);
        $this->assertContains('pending_applications', $reasons);
    }

    // ============================================================
    // CONFIGURABLE LIMITS
    // ============================================================

    /** @test */
    public function respects_minimum_candidates_requirement_from_config()
    {
        config(['election.min_candidates_for_voting' => 3]);

        $this->election->update([
            'nomination_completed' => true,
            'candidates_count' => 2,
            'pending_candidacies_count' => 0,
        ]);

        $this->assertFalse($this->election->canEnterVotingPhase());

        $this->election = $this->election->fresh();
        $this->election->update(['candidates_count' => 3]);
        $this->election = $this->election->fresh();

        $this->assertTrue($this->election->canEnterVotingPhase());
    }
}
