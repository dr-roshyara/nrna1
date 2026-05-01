<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * TDD: Voting Closure Validation Bug Fix
 *
 * REQUIREMENT:
 * - closeVoting() must work even if voting started in the past
 * - validateTimelineForEdit() must reject past voting start dates
 * - validateTimeline() (permissive) must NOT reject past dates
 */
class VotingClosureValidationTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Create authenticated user with election officer role
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Create organisation
        $org = \App\Models\Organisation::factory()->create();

        // Create election with voting dates in the past
        $this->election = Election::factory()->real()->create([
            'organisation_id' => $org->id,
            'state' => 'voting',
            'voting_starts_at' => now()->subDays(2),  // Started 2 days ago
            'voting_ends_at' => now()->addDay(),      // Ends tomorrow
            'administration_completed' => true,
            'nomination_completed' => true,
            'posts_count' => 1,
            'voters_count' => 10,
            'candidates_count' => 5,
        ]);

        // Add user as chief election officer
        \App\Models\ElectionOfficer::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $org->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        // Add user as organisation owner for authorizations
        \App\Models\UserOrganisationRole::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);
    }

    // ============================================================
    // RED PHASE: Tests that would fail before the fix
    // ============================================================

    /** @test */
    public function closeVoting_works_even_if_voting_started_in_past()
    {
        // BEFORE FIX: Would throw "Voting start date cannot be in the past"
        // AFTER FIX: Should succeed

        $this->assertEquals('voting', $this->election->state);
        $this->assertTrue(now()->gt($this->election->voting_starts_at));

        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'close_voting',
                actorId: auth()->id() ?? 'test-actor',
                reason: 'Testing voting closure with past start date'
            )
        );

        $this->assertNotNull($transition);
        $this->election->refresh();
        // Should transition (to voting_closed or results_pending depending on auto-transition)
        $this->assertContains($this->election->state, ['voting_closed', 'results_pending']);
    }

    /** @test */
    public function lockVoting_works_even_if_voting_started_in_past()
    {
        // Lock voting should also work without timeline validation errors
        $this->election->lockVoting('test-actor');

        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
    }

    /** @test */
    public function validateTimelineForEdit_rejects_past_voting_start_on_update()
    {
        // When updating an election with past voting start, validateTimelineForEdit should fail
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        // Use the existing election which already has past voting_starts_at
        // This election has voting_starts_at = now()->subDays(2) from setUp()
        // Call validateTimelineForEdit() directly - it should throw
        $this->election->validateTimelineForEdit();
    }

    /** @test */
    public function validateTimelineForEdit_rejects_past_voting_start_directly()
    {
        // Create a fresh election in a state where we can change dates
        $fresh = Election::factory()->real()->create([
            'organisation_id' => $this->election->organisation_id,
            'state' => 'planning',
            'voting_starts_at' => now()->addDays(10),
            'voting_ends_at' => now()->addDays(20),
        ]);

        // Now try to set past dates directly
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        $fresh->voting_starts_at = now()->subDays(5);
        $fresh->validateTimelineForEdit();
    }

    // ============================================================
    // GREEN PHASE: Verify the fix works
    // ============================================================

    /** @test */
    public function validateTimeline_permissive_accepts_past_voting_start()
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
    public function validateTimeline_still_validates_chronological_order()
    {
        // The permissive method should still validate other rules
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date must be before end date');

        $this->election->voting_starts_at = now()->addDays(5);
        $this->election->voting_ends_at = now()->addDays(3);  // Before start date

        $this->election->validateTimeline();
    }

    /** @test */
    public function validateTimelineForEdit_validates_all_chronological_constraints()
    {
        // validateTimelineForEdit() should validate chronological order
        $this->expectException(\InvalidArgumentException::class);

        $this->election->voting_starts_at = now()->addDays(5);
        $this->election->voting_ends_at = now()->addDays(3);  // Before start

        $this->election->validateTimelineForEdit();
    }

    // ============================================================
    // INTEGRATION: Full workflow
    // ============================================================

    /** @test */
    public function full_voting_lifecycle_works_without_timeline_validation_errors()
    {
        // Use existing election already set up with officer role in setUp()
        $this->assertEquals('voting', $this->election->state);
        $this->assertTrue(now()->gt($this->election->voting_starts_at));

        // Simulate voting having continued and now we close it
        // This is the actual bug scenario: close voting when start date is in the past
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('close_voting', auth()->id(), 'Close voting')
        );

        // Should succeed without "Voting start date cannot be in the past" error
        $this->assertNotNull($transition);
        $this->election->refresh();
        $this->assertContains($this->election->state, ['voting_closed', 'results_pending']);
    }

    /** @test */
    public function validateTimelineForEdit_still_requires_duration_minimums()
    {
        // The edit validation should still enforce minimum phase durations
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Administration phase must last at least 24 hours');

        // Create dates with too-short duration
        $this->election->administration_starts_at = now()->addDays(10);
        $this->election->administration_ends_at = now()->addDays(10)->addMinutes(30);  // Only 30 min

        $this->election->validateTimelineForEdit();
    }

    // ============================================================
    // EDGE CASES
    // ============================================================

    /** @test */
    public function real_election_validates_strict_on_timeline_edits()
    {
        // Attempting to update voting dates to the past should be rejected
        // This tests the controller-level validation
        $fresh = Election::factory()->real()->create([
            'organisation_id' => $this->election->organisation_id,
            'state' => 'planning',
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(10),
        ]);

        // Set past dates and validate
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Voting start date cannot be in the past');

        $fresh->voting_starts_at = now()->subDays(1);
        $fresh->validateTimelineForEdit();
    }

    /** @test */
    public function validateTimelineForEdit_allows_future_voting_start()
    {
        // Future voting dates should be accepted
        $this->election->voting_starts_at = now()->addDays(5);
        $this->election->voting_ends_at = now()->addDays(10);

        try {
            $this->election->validateTimelineForEdit();
            $this->assertTrue(true, 'Future dates accepted');
        } catch (\InvalidArgumentException $e) {
            $this->fail('Should accept future dates: ' . $e->getMessage());
        }
    }
}
