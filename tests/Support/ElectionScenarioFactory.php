<?php

namespace Tests\Support;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
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

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::Approved);
        $election->update(['state' => ElectionLifecycleState::Approved->value]);

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
     * Allowed actions: open_voting
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
                // Timezone required for open_voting precondition
                'timezone' => 'UTC',
            ]);

        // Create at least one post and candidate (required for open_voting)
        $post = \App\Models\Post::factory()
            ->create([
                'election_id' => $election->id,
                'organisation_id' => $org->id,
                'name' => 'Test Position',
                'is_national_wide' => true,
            ]);

        // Create an approved candidacy for the post with correct organization
        $user = \App\Models\User::factory()->forOrganisation($org)->create();
        \App\Models\Candidacy::factory()
            ->create([
                'organisation_id' => $org->id,
                'post_id' => $post->id,
                'user_id' => $user->id,
                'status' => 'approved',
            ]);

        // Update election counts
        $election->update([
            'candidates_count' => 1,
            'posts_count' => 1,
            'pending_candidacies_count' => 0,
        ]);

        // Verify engine derives correct state and SYNC IT TO DATABASE
        $state = ElectionLifecycle::of($election)->state();
        $election->update(['state' => $state->value]);

        \Illuminate\Support\Facades\Log::info('ElectionScenarioFactory::configurationComplete', [
            'election_id' => $election->id,
            'derived_state' => $state->value,
            'approved_at' => $election->approved_at?->toIso8601String(),
            'voting_starts_at' => $election->voting_starts_at?->toIso8601String(),
            'voting_ends_at' => $election->voting_ends_at?->toIso8601String(),
            'administration_completed' => $election->administration_completed,
            'nomination_completed' => $election->nomination_completed,
            'candidates_count' => $election->candidates_count,
            'posts_count' => $election->posts_count,
        ]);
        self::assertStateIsOneOf($election, [
            ElectionLifecycleState::SetupAdministration,
            ElectionLifecycleState::SetupNomination,
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
     * Allowed actions: close_voting
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

        // EM-VOT-002 (adopted): an approved candidate is part of the facts
        // that constitute a legitimately VotingActive election — this scenario's
        // declared contract (assertDerivedState below). Domain-fixture
        // correction, not test convenience; no consumer observes candidates
        // (34-call-site classification, 2026-08-13).
        $post = \App\Models\Post::factory()
            ->create([
                'election_id' => $election->id,
                'organisation_id' => $org->id,
                'name' => 'Test Position',
                'is_national_wide' => true,
            ]);
        $candidate = User::factory()->forOrganisation($org)->create();
        \App\Models\Candidacy::factory()
            ->create([
                'organisation_id' => $org->id,
                'post_id' => $post->id,
                'user_id' => $candidate->id,
                'status' => 'approved',
            ]);
        $election->update([
            'candidates_count' => 1,
            'posts_count' => 1,
            'pending_candidacies_count' => 0,
        ]);

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::VotingActive);
        $election->update(['state' => ElectionLifecycleState::VotingActive->value]);

        return $election;
    }

    /**
     * Create a VotingActive election with a chief ElectionOfficer.
     *
     * Returns [Election, User(chief), Organisation] for use in HTTP flow tests.
     * The election is derived via constitutional facts (not state fabrication).
     * The chief has an active ElectionOfficer record for authorization gates.
     */
    public static function votingActiveWithChief(?Organisation $organisation = null): array
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $election = self::votingActive($org);

        $chief = User::factory()->forOrganisation($org)->create();
        self::createChiefOfficer($chief, $election, $org);

        return [$election, $chief, $org];
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

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::Counting);
        $election->update(['state' => ElectionLifecycleState::Counting->value]);

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

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::ResultsPublished);
        $election->update(['state' => ElectionLifecycleState::ResultsPublished->value]);

        return $election;
    }

    /**
     * Create an election in SetupNomination state (nomination phase active)
     *
     * Constitutional facts:
     * - approved_at is set
     * - administration_completed = true (setup done)
     * - nomination_completed = false (nomination phase active)
     * - No voting window configured yet
     * - No nomination window dates set
     *
     * Expected derived state: SetupNomination
     * Allowed actions: apply_candidacy, complete_nomination, open_voting
     */
    public static function setupNomination(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Nomination Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => now()->subDay(),
                // Setup phase completed, nomination phase active
                'administration_completed' => true,
                'administration_completed_at' => now()->subHours(4),
                'nomination_completed' => false,
                // No voting window — not yet configured
                'voting_starts_at' => null,
                'voting_ends_at' => null,
                // No results
                'results_published_at' => null,
                // No nomination window dates — engine derives SetupNomination directly
                'nomination_suggested_start' => null,
                'nomination_suggested_end' => null,
            ]);

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::SetupNomination);
        $election->update(['state' => ElectionLifecycleState::SetupNomination->value]);

        return $election;
    }

    /**
     * Create a SetupNomination election with a chief ElectionOfficer.
     *
     * Returns [Election, User(chief), Organisation].
     * Constitutional-safe: delegates to setupNomination() which asserts engine derivation.
     */
    public static function setupNominationWithChief(?Organisation $organisation = null): array
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $election = self::setupNomination($org);

        $chief = User::factory()->forOrganisation($org)->create();
        self::createChiefOfficer($chief, $election, $org);

        return [$election, $chief, $org];
    }

    /**
     * Create an election in SetupAdministration state (governance setup phase)
     *
     * Constitutional facts:
     * - approved_at is set
     * - setup_started_at is set (committee began setup)
     * - administration_completed = false (setup not finished)
     *
     * Expected derived state: SetupAdministration
     * Allowed actions: complete_administration
     */
    public static function setupAdministration(?Organisation $organisation = null): Election
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'name' => 'Administration Election ' . uniqid(),
                'type' => 'real',
                'approved_at' => now()->subDay(),
                // Setup started but not completed
                'setup_started_at' => now()->subHours(2),
                'administration_completed' => false,
                'nomination_completed' => false,
                // No voting window
                'voting_starts_at' => null,
                'voting_ends_at' => null,
                // No results
                'results_published_at' => null,
            ]);

        // Verify engine derives correct state and SYNC IT TO DATABASE
        self::assertDerivedState($election, ElectionLifecycleState::SetupAdministration);
        $election->update(['state' => ElectionLifecycleState::SetupAdministration->value]);

        return $election;
    }

    /**
     * Create a SetupAdministration election with a chief ElectionOfficer.
     *
     * Returns [Election, User(chief), Organisation].
     * Constitutional-safe: delegates to setupAdministration() which asserts engine derivation.
     */
    public static function setupAdministrationWithChief(?Organisation $organisation = null): array
    {
        $org = $organisation ?? Organisation::factory()->create(['type' => 'tenant']);
        $election = self::setupAdministration($org);

        $chief = User::factory()->forOrganisation($org)->create();
        self::createChiefOfficer($chief, $election, $org);

        return [$election, $chief, $org];
    }

    /**
     * Create a chief ElectionOfficer record for governing an election.
     *
     * Must never be called outside the factory — prevents floating governance actors.
     */
    private static function createChiefOfficer(User $chief, Election $election, Organisation $org): void
    {
        ElectionOfficer::create([
            'user_id' => $chief->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);
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
