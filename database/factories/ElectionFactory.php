<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Organisation;
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
        return $this->state([
            'organisation_id' => $organisation->id,
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
                'submitted_by' => fake()->uuid(),
            ];
        });
    }

    public function inAdministrationState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'setup',
                'approved_at' => now(),
                'approved_by' => fake()->uuid(),
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
                'results_published_by' => fake()->uuid(),
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
}
