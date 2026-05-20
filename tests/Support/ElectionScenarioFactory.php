<?php

namespace Tests\Support;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\Organisation;
use Carbon\Carbon;

/**
 * ElectionScenarioFactory: Build elections by constitutional facts, not by direct state assignment.
 *
 * This is the cornerstone of test vocabulary alignment during the strangler migration.
 *
 * **CRITICAL PRINCIPLE:**
 * Tests should NOT set `$election->state = 'voting'`
 * Tests SHOULD set facts: `$election->voting_starts_at = now()->subHour()`
 *
 * The system then derives the correct state from facts.
 * This makes tests resilient to internal lifecycle evolution.
 *
 * **FACTORY CONTRACT:**
 * Every scenario factory:
 * 1. Sets constitutional facts explicitly
 * 2. Asserts the derived lifecycle state matches expectations
 * 3. Never creates floating governance actors (officers without elections)
 * 4. Documents expected capabilities alongside facts
 */
final class ElectionScenarioFactory
{
    /**
     * Create an election in Approved state
     *
     * Constitutional facts:
     * - approved_at is set (approval happened)
     * - No voting window configured yet
     * - No completion flags set
     *
     * Expected derived state: Approved
     * Allowed actions: begin_setup, revise_and_resubmit
     */
    public static function approved(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Approved Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => now()->subDay(),
                // No voting window - not yet configured
                'voting_starts_at' => null,
                'voting_ends_at' => null,
                // No completion flags
                'administration_completed' => false,
                'nomination_completed' => false,
                // No results
                'results_published_at' => null,
            ]);

        // Verify engine derives correct state
        self::assertDerivedState($election, ElectionLifecycleState::Approved);

        return $election;
    }

    /**
     * Create an election in Setup/Configuration state
     *
     * Constitutional facts:
     * - approved_at is set
     * - voting_starts_at is in the FUTURE (window configured but not active)
     * - administration_completed = true (setup phase completed)
     * - No results published
     *
     * Expected derived state: Setup (or Ready For Voting if all flags set)
     * Allowed actions: open_voting, lock_voting (if window started)
     */
    public static function configurationComplete(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Setup Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => $now->copy()->subDay(),
                // Voting window configured but not active
                'voting_starts_at' => $now->copy()->addDay(),
                'voting_ends_at' => $now->copy()->addDays(2),
                // Setup phase completed
                'administration_completed' => true,
                'administration_completed_at' => $now->copy()->subHours(4),
                'nomination_completed' => true,
                'nomination_completed_at' => $now->copy()->subHours(2),
                // No results
                'results_published_at' => null,
            ]);

        // Verify engine derives correct state (Setup or ReadyForVoting)
        $state = ElectionLifecycle::of($election)->state();
        self::assertStateIsOneOf($election, [
            ElectionLifecycleState::Setup,
            ElectionLifecycleState::ReadyForVoting,
        ]);

        return $election;
    }

    /**
     * Create an election actively accepting votes
     *
     * Constitutional facts:
     * - approved_at is set
     * - voting_starts_at is in the PAST
     * - voting_ends_at is in the FUTURE
     * - voting is not locked
     * - Results not published
     *
     * Expected derived state: VotingActive
     * Allowed actions: lock_voting, close_voting
     */
    public static function votingActive(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Voting Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => $now->copy()->subDays(2),
                // Voting window is active NOW
                'voting_starts_at' => $now->copy()->subHour(),
                'voting_ends_at' => $now->copy()->addHour(),
                // Setup complete
                'administration_completed' => true,
                'administration_completed_at' => $now->copy()->subDays(1),
                'nomination_completed' => true,
                'nomination_completed_at' => $now->copy()->subHours(23),
                // Voting not locked
                'voting_locked' => false,
                // No results
                'results_published_at' => null,
            ]);

        // Verify engine derives correct state
        self::assertDerivedState($election, ElectionLifecycleState::VotingActive);

        return $election;
    }

    /**
     * Create an election with voting closed but results not published
     *
     * Constitutional facts:
     * - voting_ends_at is in the PAST
     * - results_published_at is not set
     *
     * Expected derived state: Counting
     * Allowed actions: publish_results
     */
    public static function votingClosed(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Counting Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => $now->copy()->subDays(2),
                // Voting window is CLOSED
                'voting_starts_at' => $now->copy()->subDays(1),
                'voting_ends_at' => $now->copy()->subMinute(),
                // Setup complete
                'administration_completed' => true,
                'nomination_completed' => true,
                // Results not yet published
                'results_published_at' => null,
            ]);

        // Verify engine derives correct state
        self::assertDerivedState($election, ElectionLifecycleState::Counting);

        return $election;
    }

    /**
     * Create an election with results published (complete)
     *
     * Constitutional facts:
     * - results_published_at is set to past (election is complete)
     *
     * Expected derived state: ResultsPublished
     * Allowed actions: None (terminal state)
     */
    public static function resultsPublished(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Completed Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => $now->copy()->subDays(3),
                // Full voting window is in past
                'voting_starts_at' => $now->copy()->subDays(2),
                'voting_ends_at' => $now->copy()->subDay(),
                // Setup complete
                'administration_completed' => true,
                'nomination_completed' => true,
                // Results PUBLISHED
                'results_published_at' => $now,
            ]);

        // Verify engine derives correct state
        self::assertDerivedState($election, ElectionLifecycleState::ResultsPublished);

        return $election;
    }

    /**
     * Assert that election's derived lifecycle state matches expected state.
     *
     * This is the CRITICAL invariant verification.
     * If this fails, the factory is creating scenarios that don't match their names.
     *
     * @throws \AssertionError if derived state doesn't match expected
     */
    private static function assertDerivedState(
        Election $election,
        ElectionLifecycleState $expected
    ): void {
        $actual = ElectionLifecycle::of($election->fresh())->state();

        if ($actual !== $expected) {
            throw new \AssertionError(
                "Scenario factory created election that derived to unexpected state. " .
                "Expected: {$expected->value}, Actual: {$actual->value}. " .
                "This indicates facts don't match scenario semantics."
            );
        }
    }

    /**
     * Assert that election's derived state is one of multiple acceptable states.
     *
     * Use when a scenario can legitimately derive to multiple states depending on
     * other facts (e.g., configuration complete might be Setup OR ReadyForVoting).
     *
     * @throws \AssertionError if derived state isn't one of the acceptable states
     */
    private static function assertStateIsOneOf(
        Election $election,
        array $acceptableStates
    ): void {
        $actual = ElectionLifecycle::of($election->fresh())->state();
        $acceptable = array_map(fn($s) => $s->value, $acceptableStates);

        if (!in_array($actual->value, $acceptable)) {
            $expected = implode(', ', $acceptable);
            throw new \AssertionError(
                "Scenario factory created election with unexpected derived state. " .
                "Expected one of: {$expected}, Actual: {$actual->value}"
            );
        }
    }
}
