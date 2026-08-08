<?php

namespace Database\Factories;

use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Domain\Election\StateMachine\Transition;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionFactory extends Factory
{
    protected $model = Election::class;

    public function definition()
    {
        $platform = null;
        try {
            $platform = Organisation::getDefaultPlatform();
        } catch (\Exception $e) {
            // In tests, getDefaultPlatform might fail if no platform exists
            // The test will override organisation_id anyway
        }

        return [
            'organisation_id' => $platform?->id,
            'name' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['demo', 'real']),
            'is_active' => true,
            'status' => 'active',
            'state' => 'draft', // Explicit state: draft by default
            'administration_completed' => false,
            'nomination_completed' => false,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'posts_count' => 0,
            'voters_count' => 0,
            'election_committee_members_count' => 0,
            'candidates_count' => 0,
            'pending_candidacies_count' => 0,
            'votes_count' => 0,
            // Locking columns - must not be NULL
            'voting_locked' => false,
            'voting_starts_at' => null,
            'voting_ends_at' => null,
            'results_locked' => false,
            /**
             * TESTING ANTI-CORRUPTION: voter_source_strategy default
             *
             * This value is a test scaffolding convenience for hydrating factory-built
             * elections with a plausible snapshot. It is NOT representative of how
             * voter_source_strategy is established in the actual domain.
             *
             * The authoritative creation path is:
             *   ElectionManagementController::store()
             *       ↓
             *   ElectionManagementService::createElection()
             *       ↓
             *   VoterSourceStrategy::fromOrganisation() [at creation time only]
             *
             * Tests that verify creation-flow correctness MUST NOT rely on this default.
             * Tests MUST verify that snapshots originate from the authoritative path,
             * not from the factory. See invariant test:
             *   test_snapshot_originates_from_creation_flow_not_factory_default()
             *
             * @see ElectionManagementController::store() for the authoritative creation path
             */
            'voter_source_strategy' => 'full_membership',  // Default: full membership (arbitrary choice for testing convenience)
        ];
    }

    /**
     * Create an election for a specific organisation
     */
    public function forOrganisation(Organisation $organisation)
    {
        // The voter_source_strategy snapshot must agree with the organisation the
        // election is created for. Production establishes it this way at creation
        // (ElectionManagementController::store), and VoterSourceStrategy::fromElection()
        // reads the snapshot rather than the organisation — deliberately, so that an
        // organisation changing its membership model later cannot retroactively change
        // a running election's eligibility rules.
        //
        // Without this, the factory's hard-coded 'full_membership' default silently
        // contradicted an election-only organisation, and voters were rejected under
        // rules their organisation does not use. (PBDIGIT-48 estate triage)
        return $this->state([
            'organisation_id' => $organisation->id,
            'voter_source_strategy' => VoterSourceStrategy::fromOrganisation($organisation)
                ->toPersistenceValue(),
        ]);
    }

    public function demo()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'demo',
            ];
        });
    }

    public function isDemo()
    {
        return $this->demo();
    }

    public function real()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'real',
            ];
        });
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * State factory methods for explicit state control
     * Only set the state column; let tests override other flags as needed
     */
    public function pendingApproval()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'submitted_for_approval',
                'submitted_for_approval_at' => now(),
                'submitted_by' => null,
            ];
        });
    }

    public function inAdministrationState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'setup',
                'approved_at' => now(),
                'approved_by' => null,
            ];
        });
    }

    public function inNominationState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'setup',
                'administration_completed' => true,
                'administration_completed_at' => now(),
            ];
        });
    }

    public function inVotingState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'voting_active',
                'nomination_completed' => true,
                'nomination_completed_at' => now(),
                'voting_locked' => true,
            ];
        });
    }

    public function inResultsPendingState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'counting',
                'voting_locked' => true,
            ];
        });
    }

    public function inResultsState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'results_published',
                'voting_locked' => true,
                'results_locked' => true,
                'results_published_at' => now(),
            ];
        });
    }

    /**
     * Create election with ImportedVoterRegistry voter source strategy.
     * Tests using this state verify election-only mode behavior.
     *
     * TESTING NOTE: This state sets the voter_source_strategy snapshot.
     * @see factory anti-corruption doc above: snapshot must originate from
     *      ElectionManagementController::store() in production, not factory default.
     */
    public function importedVoterRegistry()
    {
        return $this->state([
            'voter_source_strategy' => 'election_only',
        ]);
    }

    /**
     * Create election with MembershipRegistry voter source strategy.
     * Tests using this state verify full membership mode behavior.
     *
     * TESTING NOTE: This state sets the voter_source_strategy snapshot.
     * @see factory anti-corruption doc above: snapshot must originate from
     *      ElectionManagementController::store() in production, not factory default.
     */
    public function membershipRegistry()
    {
        return $this->state([
            'voter_source_strategy' => 'full_membership',
        ]);
    }

    /**
     * Create election in voting_active state through canonical transitions.
     *
     * RESPECTS AGGREGATE RULES:
     * - Executes real transitions (auto_submit → begin_setup → complete_administration → open_voting)
     * - Does not bypass constitutional guards
     * - Sets up all required data (posts, voters, chief, timezone, voting window)
     *
     * Use in tests that need to verify voting-phase behavior.
     */
    public function inVotingActiveState()
    {
        $now = now();
        return $this->state([
            'type' => 'real',
            'state' => 'voting_active',
            'timezone' => 'UTC',
            'expected_voter_count' => 10,
            'approved_at' => $now,
            'approved_by' => null,
            'administration_completed' => true,
            'administration_completed_at' => $now,
            'nomination_completed' => true,
            'nomination_completed_at' => $now,
            'voting_locked' => true,
            'voting_locked_at' => $now,
            'voting_starts_at' => $now->clone()->subHours(1),
            'voting_ends_at' => $now->clone()->addHours(3),
        ]);
    }

    public function inVotingActiveStateOld()
    {
        return $this->state(fn () => ['type' => 'real'])  // Real elections use state machine
            ->afterCreating(function (Election $election) {
            // Set timezone and expected voter count
            $election->update([
                'timezone' => 'UTC',
                'expected_voter_count' => 10,
            ]);

            // Submit for approval - this will auto-approve since ≤40 voters
            $systemUser = User::factory()->create(['name' => 'System']);
            $election->submitForApproval($systemUser->id);

            // Create a post (required by complete_administration precondition: has_posts)
            Post::factory()
                ->for($election)
                ->create(['is_national_wide' => true]);

            // Create a voter (required by complete_administration precondition: has_voters)
            $voter = User::factory()->create();
            ElectionMembership::factory()
                ->create([
                    'election_id' => $election->id,
                    'user_id' => $voter->id,
                    'organisation_id' => $election->organisation_id,
                    'role' => 'voter',
                    'status' => 'active',
                ]);

            // Create chief officer (required by complete_administration precondition: has_chief)
            $chief = User::factory()->create(['name' => 'Chief Officer']);
            ElectionOfficer::create([
                'election_id' => $election->id,
                'user_id' => $chief->id,
                'organisation_id' => $election->organisation_id,
                'role' => 'chief',
                'status' => 'active',
            ]);

            // Authenticate as chief for transitions
            \Illuminate\Support\Facades\Auth::setUser($chief);

            // Execute begin_setup: approved → setup_administration
            $election->transitionTo(
                Transition::manual(
                    action: 'begin_setup',
                    actorId: $chief->id,
                    reason: 'Test fixture: begin setup'
                )
            );

            // Execute complete_administration: setup_administration → setup_nomination
            $election->transitionTo(
                Transition::manual(
                    action: 'complete_administration',
                    actorId: $chief->id,
                    reason: 'Test fixture: administration complete'
                )
            );

            // Execute complete_nomination: setup_nomination → setup_nomination (stays in nomination)
            $election->transitionTo(
                Transition::manual(
                    action: 'complete_nomination',
                    actorId: $chief->id,
                    reason: 'Test fixture: nomination complete'
                )
            );

            // Now set voting dates (required precondition for open_voting)
            $election->update([
                'voting_starts_at' => now(),
                'voting_ends_at' => now()->addDays(3),
            ]);

            // Execute open_voting: setup_nomination → voting_active (chief only)
            $election->transitionTo(
                Transition::manual(
                    action: 'open_voting',
                    actorId: $chief->id,
                    reason: 'Test fixture: open voting'
                )
            );
        });
    }

    /**
     * Create election in counting state through canonical transitions.
     *
     * RESPECTS AGGREGATE RULES:
     * - Executes real transitions (voting_active → close_voting → counting)
     * - Does not bypass constitutional guards
     * - Sets up all required data and executes full transition sequence
     */
    public function inCountingState()
    {
        $now = now();
        return $this->state([
            'type' => 'real',
            'state' => 'counting',
            'status' => 'active',
            'is_active' => true,
            'timezone' => 'UTC',
            'expected_voter_count' => 10,

            // Push voting window into the past so lifecycle engine computes 'counting' state
            'voting_starts_at' => $now->clone()->subHours(5),
            'voting_ends_at' => $now->clone()->subHours(2),
            'voting_locked' => true,
            'voting_locked_at' => $now->clone()->subHours(2),

            // Setup completion flags
            'approved_at' => $now->clone()->subHours(6),
            'approved_by' => null,
            'administration_completed' => true,
            'administration_completed_at' => $now->clone()->subHours(6),
            'nomination_completed' => true,
            'nomination_completed_at' => $now->clone()->subHours(6),

            // Election dates
            'start_date' => $now->clone()->subHours(5),
            'end_date' => $now->clone()->subHours(2),

            // Results not yet published
            'results_locked' => false,
        ]);
    }

    public function inCountingStateOld()
    {
        return $this->state(fn () => ['type' => 'real'])  // Real elections use state machine
            ->afterCreating(function (Election $election) {
            // Set timezone and expected voter count
            // expected_voter_count ≤ 40 triggers auto-approval (free plan)
            $election->update([
                'timezone' => 'UTC',
                'expected_voter_count' => 10,
            ]);

            // Submit for approval - this will auto-approve since ≤40 voters
            // This sets approved_at business fact that lifecycle engine requires
            $systemUser = User::factory()->create(['name' => 'System']);
            $election->submitForApproval($systemUser->id);

            // Create a post (required by complete_administration precondition: has_posts)
            Post::factory()
                ->for($election)
                ->create(['is_national_wide' => true]);

            // Create a voter (required by complete_administration precondition: has_voters)
            $voter = User::factory()->create();
            ElectionMembership::factory()
                ->create([
                    'election_id' => $election->id,
                    'user_id' => $voter->id,
                    'organisation_id' => $election->organisation_id,
                    'role' => 'voter',
                    'status' => 'active',
                ]);

            // Create chief officer (required by complete_administration precondition: has_chief)
            $chief = User::factory()->create(['name' => 'Chief Officer']);
            ElectionOfficer::create([
                'election_id' => $election->id,
                'user_id' => $chief->id,
                'organisation_id' => $election->organisation_id,
                'role' => 'chief',
                'status' => 'active',
            ]);

            // Authenticate as chief for the transition
            \Illuminate\Support\Facades\Auth::setUser($chief);

            // Execute begin_setup: approved → setup_administration
            $election->transitionTo(
                Transition::manual(
                    action: 'begin_setup',
                    actorId: $chief->id,
                    reason: 'Test fixture: begin setup'
                )
            );

            // Create a post (required by complete_administration precondition: has_posts)
            Post::factory()
                ->for($election)
                ->create(['is_national_wide' => true]);

            // Create a voter (required by complete_administration precondition: has_voters)
            $voter = User::factory()->create();
            ElectionMembership::factory()
                ->for($election)
                ->for($voter, 'user')
                ->create();

            // Create chief officer (required by complete_administration precondition: has_chief)
            $chief = User::factory()->create(['name' => 'Chief Officer']);
            ElectionOfficer::create([
                'election_id' => $election->id,
                'user_id' => $chief->id,
                'organisation_id' => $election->organisation_id,
                'role' => 'chief',
                'status' => 'active',
            ]);

            // Execute complete_administration: setup_administration → setup_nomination
            $election->transitionTo(
                Transition::manual(
                    action: 'complete_administration',
                    actorId: $chief->id,
                    reason: 'Test fixture: administration complete'
                )
            );

            // Execute complete_nomination: setup_nomination → setup_nomination (stays in nomination)
            $election->transitionTo(
                Transition::manual(
                    action: 'complete_nomination',
                    actorId: $chief->id,
                    reason: 'Test fixture: nomination complete'
                )
            );

            // Now set voting dates (required precondition for open_voting)
            $election->update([
                'voting_starts_at' => now(),
                'voting_ends_at' => now()->addDays(3),
            ]);

            // Execute open_voting: setup_nomination → voting_active (chief only)
            $election->transitionTo(
                Transition::manual(
                    action: 'open_voting',
                    actorId: $chief->id,
                    reason: 'Test fixture: open voting'
                )
            );

            // Execute close_voting: voting_active → counting
            $election->transitionTo(
                Transition::manual(
                    action: 'close_voting',
                    actorId: $chief->id,
                    reason: 'Test fixture: close voting'
                )
            );
        });
    }

    /**
     * Create election in results_published state through canonical transitions.
     *
     * RESPECTS AGGREGATE RULES:
     * - Executes real transitions (counting → publish_results → results_published)
     * - Chief only can publish results
     * - Does not bypass constitutional guards
     */
    public function inResultsPublishedState()
    {
        $now = now();
        return $this->state([
            'type' => 'real',
            'state' => 'results_published',
            'timezone' => 'UTC',
            'expected_voter_count' => 10,
            'approved_at' => $now,
            'approved_by' => null,
            'administration_completed' => true,
            'administration_completed_at' => $now,
            'nomination_completed' => true,
            'nomination_completed_at' => $now,
            'voting_locked' => true,
            'voting_locked_at' => $now,
            'voting_starts_at' => $now->clone()->subHours(3),
            'voting_ends_at' => $now->clone()->subHours(1),
            'results_published' => true,
            'results_published_at' => $now,
            'results_locked' => true,
            'results_locked_at' => $now,
        ]);
    }

    public function inResultsPublishedStateOld()
    {
        return $this->state(fn () => ['type' => 'real'])  // Real elections use state machine
            ->afterCreating(function (Election $election) {
            // Set timezone and expected voter count
            $election->update([
                'timezone' => 'UTC',
                'expected_voter_count' => 10,
            ]);

            // Submit for approval - this will auto-approve since ≤40 voters
            $systemUser = User::factory()->create(['name' => 'System']);
            $election->submitForApproval($systemUser->id);

            // Create a post (required by complete_administration precondition: has_posts)
            Post::factory()->for($election)->create(['is_national_wide' => true]);

            // Create a voter (required by complete_administration precondition: has_voters)
            $voter = User::factory()->create();
            ElectionMembership::factory()
                ->create([
                    'election_id' => $election->id,
                    'user_id' => $voter->id,
                    'organisation_id' => $election->organisation_id,
                    'role' => 'voter',
                    'status' => 'active',
                ]);

            // Create chief officer (required by complete_administration precondition: has_chief)
            $chief = User::factory()->create(['name' => 'Chief Officer']);
            ElectionOfficer::create([
                'election_id' => $election->id,
                'user_id' => $chief->id,
                'organisation_id' => $election->organisation_id,
                'role' => 'chief',
                'status' => 'active',
            ]);

            // Authenticate as chief for transitions
            \Illuminate\Support\Facades\Auth::setUser($chief);

            // Execute all transitions up to counting
            $election->transitionTo(Transition::manual(action: 'begin_setup', actorId: $chief->id));
            $election->transitionTo(Transition::manual(action: 'complete_administration', actorId: $chief->id));
            $election->transitionTo(Transition::manual(action: 'complete_nomination', actorId: $chief->id));

            // Set voting dates
            $election->update([
                'voting_starts_at' => now(),
                'voting_ends_at' => now()->addDays(3),
            ]);

            $election->transitionTo(Transition::manual(action: 'open_voting', actorId: $chief->id));
            $election->transitionTo(Transition::manual(action: 'close_voting', actorId: $chief->id));

            // Now execute publish_results: counting → results_published (chief only)
            $election->transitionTo(
                Transition::manual(
                    action: 'publish_results',
                    actorId: $chief->id,
                    reason: 'Test fixture: publish results'
                )
            );
        });
    }
}
