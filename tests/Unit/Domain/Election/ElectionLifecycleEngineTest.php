<?php

namespace Tests\Unit\Domain\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Services\ElectionLifecycleEngine;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 1: SSOT Layer
 * Test: ElectionLifecycleEngine
 *
 * RED tests for the single source of truth computation.
 * Engine computes the canonical current state from all signals.
 */
class ElectionLifecycleEngineTest extends TestCase
{
    use RefreshDatabase;

    private ElectionLifecycleEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new ElectionLifecycleEngineImpl();
    }

    /**
     * Test: Engine returns snapshot for any election
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function engine_returns_snapshot_for_any_election(): void
    {
        $election = Election::factory()->create();

        $snapshot = $this->engine->compute($election);

        $this->assertNotNull($snapshot);
        $this->assertNotNull($snapshot->state);
        $this->assertIsBool($snapshot->canEdit);
        $this->assertIsBool($snapshot->canVote);
        $this->assertIsArray($snapshot->allowedActions);
    }

    /**
     * Test: Draft state when election just created
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function draft_state_when_election_just_created(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::Draft, $state);
    }

    /**
     * Test: Approved state when platform approved but setup not started
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function approved_state_when_platform_approved_but_setup_not_started(): void
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDay(),
            'setup_started_at' => null,  // setup hasn't begun yet
            'administration_completed' => false,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::Approved, $state);
    }

    /**
     * Test: SetupAdministration state when setup started but admin not complete
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function setup_administration_state_when_setup_started_but_admin_not_complete(): void
    {
        $election = Election::factory()->create([
            'setup_started_at' => now()->subDay(),
            'administration_completed' => false,
            'nomination_completed' => false,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::SetupAdministration, $state);
    }

    /**
     * Test: SetupAdministration when admin done but nomination window not yet open
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function setup_administration_when_nomination_window_not_yet_open(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'nomination_suggested_start' => now()->addDays(2),  // window opens in future
            'nomination_suggested_end' => now()->addDays(5),
            'voting_starts_at' => now()->addDays(10),
            'voting_ends_at' => now()->addDays(11),
            'results_published_at' => null,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::SetupAdministration, $state);
    }

    /**
     * Test: SetupNomination when admin complete and nomination window open
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function setup_nomination_when_admin_complete_and_window_open(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'nomination_suggested_start' => now()->subDay(),  // window opened yesterday
            'nomination_suggested_end' => now()->addDays(3),
            'voting_starts_at' => now()->addDays(10),
            'voting_ends_at' => now()->addDays(11),
            'results_published_at' => null,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::SetupNomination, $state);
    }

    /**
     * Test: SetupNomination when dates not set (fallback to nomination phase)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function setup_nomination_when_admin_complete_and_no_dates_set(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'nomination_suggested_start' => null,  // no dates set
            'nomination_suggested_end' => null,
            'voting_starts_at' => now()->addDays(10),
            'voting_ends_at' => now()->addDays(11),
            'results_published_at' => null,
        ]);

        $state = $this->engine->getState($election);

        // Fallback: if admin done and no window specified, assume nomination is active
        $this->assertEquals(ElectionLifecycleState::SetupNomination, $state);
    }

    /**
     * Test: Draft permissions allow editing and voter management
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function draft_state_provides_correct_permissions(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $snapshot = $this->engine->compute($election);

        $this->assertTrue($snapshot->canEdit);
        $this->assertFalse($snapshot->canVote);
        $this->assertTrue($snapshot->canManageVoters);
        $this->assertFalse($snapshot->canPublishResults);
    }

    /**
     * Test: Engine is injectable via container
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function engine_is_injectable(): void
    {
        $resolved = app(ElectionLifecycleEngine::class);

        $this->assertInstanceOf(ElectionLifecycleEngine::class, $resolved);
    }

    /**
     * Test: Snapshot is immutable (computing twice returns equivalent snapshots)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function snapshot_is_deterministic(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $snapshot1 = $this->engine->compute($election);
        $snapshot2 = $this->engine->compute($election);

        $this->assertEquals($snapshot1->state, $snapshot2->state);
        $this->assertEquals($snapshot1->canEdit, $snapshot2->canEdit);
        $this->assertEquals($snapshot1->canVote, $snapshot2->canVote);
    }

    /**
     * PHASE B: OPERATIONAL SUSPENSION OVERLAY
     * RED test: Engine derives Suspended when suspended_at is set
     *
     * Suspension is an operational overlay — it overrides all lifecycle derivation.
     * Even if all facts point to VotingActive, if suspended_at is set,
     * the engine must return Suspended.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function engine_derives_suspended_when_suspended_at_is_set(): void
    {
        $election = Election::factory()->create([
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),  // voting opened 2 hours ago
            'voting_ends_at' => now()->addHours(6),    // voting ends in 6 hours
            'results_published_at' => null,
            'suspended_at' => now()->subHours(1),  // but election was suspended 1 hour ago
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::Suspended, $state,
            'Engine must return Suspended when suspended_at is set, regardless of other facts');
    }

    /**
     * RED test: Suspension overrides all other lifecycle states
     *
     * This test verifies the core architectural principle:
     * Suspension is the highest-priority derivation logic.
     * It is checked FIRST, before all other state derivation.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function suspension_is_highest_priority_override(): void
    {
        // Create an election that would be in VotingActive state
        $election = Election::factory()->create([
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),  // voting started 2 hours ago
            'voting_ends_at' => now()->addHours(4),    // voting ends in 4 hours
            'results_published_at' => null,
            'suspended_at' => null,  // not suspended yet
        ]);

        // EM-VOT-002 (adopted): VotingActive requires an approved candidate —
        // this test's premise is an election that IS VotingActive pre-suspension.
        $post = \App\Models\Post::factory()->create([
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);
        \App\Models\Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $election->organisation_id,
            'user_id'         => \App\Models\User::factory()->create()->id,
            'status'          => 'approved',
        ]);

        // Without suspension, would be VotingActive
        $state = $this->engine->getState($election);
        $this->assertEquals(ElectionLifecycleState::VotingActive, $state,
            'Election should be VotingActive before suspension');

        // Now suspend it
        $election->update(['suspended_at' => now()]);

        // After suspension, engine must return Suspended regardless of other facts
        // Even though all conditions point to VotingActive, Suspended takes precedence
        $state = $this->engine->getState($election);
        $this->assertEquals(ElectionLifecycleState::Suspended, $state,
            'Suspension must override VotingActive state; engine checks suspended_at first');
    }
}
