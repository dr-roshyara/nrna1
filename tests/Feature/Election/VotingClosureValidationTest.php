<?php

namespace Tests\Feature\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TDD: Voting Closure Validation Bug Fix
 *
 * REQUIREMENT:
 * - closeVoting() must work even if voting started in the past
 * - validateTimelineForEdit() must reject past voting start dates
 * - validateTimeline() (permissive) must NOT reject past dates
 *
 * CRITICAL: Assert BOTH lifecycle derivation AND persisted state.
 * Silent divergence between engine state and persisted state can mask bugs.
 */
class VotingClosureValidationTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private \App\Models\User $user;
    private \App\Models\Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        // Create authenticated user with election officer role
        $this->user = \App\Models\User::factory()->create();
        $this->actingAs($this->user);

        // Create organisation
        $this->org = \App\Models\Organisation::factory()->create(['type' => 'tenant']);

        // Create election with CONSTITUTIONAL FACTS that make engine derive VotingActive
        $now = now();
        $this->election = Election::factory()->real()->create([
            'organisation_id' => $this->org->id,
            'approved_at' => $now->copy()->subDays(11),  // Election was approved
            'setup_started_at' => $now->copy()->subDays(10),
            'administration_completed' => true,
            'administration_completed_at' => $now->copy()->subDays(9),
            'nomination_completed' => true,
            'nomination_completed_at' => $now->copy()->subDays(8),
            // Voting window is OPEN NOW (started in past, ends in future)
            'voting_starts_at' => $now->copy()->subDays(2),  // Started 2 days ago
            'voting_ends_at' => $now->copy()->addDay(),      // Ends tomorrow
            'posts_count' => 1,
            'voters_count' => 10,
            'candidates_count' => 5,
        ]);

        // Sync persisted state to match engine derivation
        $engine = app(ElectionLifecycleEngineImpl::class);
        $derivedState = $engine->getState($this->election);
        $this->election->update(['state' => $derivedState->value]);

        // Add user as chief election officer
        \App\Models\ElectionOfficer::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->user->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        // Set tenant context
        \App\Services\TenantContext::set($this->org->id);
    }

    // ============================================================
    // Core Transition Tests — MUST assert BOTH engine AND persisted state
    // ============================================================

    /** @test */
    public function close_voting_works_even_if_voting_started_in_past(): void
    {
        // Verify starting state via engine (not raw column)
        $initialDerived = app(ElectionLifecycleEngineImpl::class)->getState($this->election);
        $this->assertEquals(ElectionLifecycleState::VotingActive, $initialDerived);

        // Voting did start in the past
        $this->assertTrue(now()->gt($this->election->voting_starts_at));

        // Execute close_voting transition
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'close_voting',
                actorId: $this->user->id,
                reason: 'Testing voting closure with past start date'
            )
        );

        $this->assertNotNull($transition);

        // CRITICAL: Assert BOTH persisted column AND engine agreement
        $this->election->refresh();

        // Persisted state must be 'counting' per Constitution
        $this->assertEquals('counting', $this->election->state, 'Persisted state must be counting');

        // Engine must also derive 'counting' (no silent divergence)
        $derivedAfter = app(ElectionLifecycleEngineImpl::class)->getState($this->election);
        $this->assertEquals(ElectionLifecycleState::Counting, $derivedAfter, 'Engine must agree on counting state');
    }

    /** @test */
    public function enforce_voting_lock_works_even_if_voting_started_in_past(): void
    {
        // Infrastructure-level voting lock should work without timeline validation errors
        $this->election->enforceVotingLock($this->user->id);

        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);

        // Engine state should still be VotingActive (locking doesn't change lifecycle state)
        $derived = app(ElectionLifecycleEngineImpl::class)->getState($this->election);
        $this->assertEquals(ElectionLifecycleState::VotingActive, $derived);
    }

    // ============================================================
    // Timeline Validation Tests
    // ============================================================

    /** @test */
    public function validateTimelineForEdit_rejects_past_voting_start_on_update(): void
    {
        // When updating an election with past voting start, validateTimelineForEdit should fail
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        // Use the existing election which already has past voting_starts_at
        // Call validateTimelineForEdit() directly - it should throw
        $this->election->validateTimelineForEdit();
    }

    /** @test */
    public function validateTimelineForEdit_rejects_past_voting_start_directly(): void
    {
        // Create a fresh election in approved state (before voting dates are set)
        $fresh = Election::factory()->real()->create([
            'organisation_id' => $this->org->id,
            'state' => 'approved',
            'approved_at' => now()->subDay(),
            'voting_starts_at' => null,
            'voting_ends_at' => null,
        ]);

        // Now try to set past dates
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        $fresh->voting_starts_at = now()->subDays(5);
        $fresh->voting_ends_at = now()->addDays(5);
        $fresh->validateTimelineForEdit();
    }

    // ============================================================
    // Permissive Validation Tests (used during state transitions)
    // ============================================================

    /** @test */
    public function validateTimeline_permissive_accepts_past_voting_start(): void
    {
        // The permissive validateTimeline() should NOT throw for past dates
        // (used during state transitions)

        $this->election->voting_starts_at = now()->subDays(5);

        // Should NOT throw exception
        try {
            $this->election->validateTimeline();
            $this->assertTrue(true, 'validateTimeline() accepts past dates');
        } catch (\InvalidArgumentException $e) {
            $this->fail('validateTimeline() should not reject past dates: ' . $e->getMessage());
        }
    }

    /** @test */
    public function validateTimeline_still_validates_chronological_order(): void
    {
        // The permissive method should still validate other rules
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date must be before end date');

        $this->election->voting_starts_at = now()->addDays(5);
        $this->election->voting_ends_at = now()->addDays(3);  // Before start date

        $this->election->validateTimeline();
    }

    /** @test */
    public function validateTimelineForEdit_validates_chronological_order(): void
    {
        // validateTimelineForEdit() should validate chronological order
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date must be before end date');

        $this->election->voting_starts_at = now()->addDays(5);
        $this->election->voting_ends_at = now()->addDays(3);  // Before start

        $this->election->validateTimelineForEdit();
    }

    // ============================================================
    // Edge Cases
    // ============================================================

    /** @test */
    public function validateTimelineForEdit_allows_future_voting_start(): void
    {
        // Future voting dates should be accepted
        $fresh = Election::factory()->real()->create([
            'organisation_id' => $this->org->id,
            'state' => 'approved',
            'approved_at' => now()->subDay(),
        ]);

        $fresh->voting_starts_at = now()->addDays(5);
        $fresh->voting_ends_at = now()->addDays(10);

        try {
            $fresh->validateTimelineForEdit();
            $this->assertTrue(true, 'Future dates accepted');
        } catch (\InvalidArgumentException $e) {
            $this->fail('Should accept future dates: ' . $e->getMessage());
        }
    }

    /** @test */
    public function real_election_type_enforces_strict_timeline_validation(): void
    {
        // Real elections enforce strict validation in validateTimelineForEdit()
        $real = Election::factory()->real()->create([
            'organisation_id' => $this->org->id,
            'state' => 'approved',
            'approved_at' => now()->subDay(),
        ]);

        // Set past dates
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        $real->voting_starts_at = now()->subDays(1);
        $real->voting_ends_at = now()->addDays(5);
        $real->validateTimelineForEdit();
    }
}
