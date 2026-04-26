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
                'state' => 'pending_approval',
                'submitted_for_approval_at' => now(),
                'submitted_by' => fake()->uuid(),
            ];
        });
    }

    public function inAdministrationState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'administration',
                'approved_at' => now(),
                'approved_by' => fake()->uuid(),
            ];
        });
    }

    public function inNominationState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'nomination',
                'administration_completed' => true,
                'administration_completed_at' => now(),
            ];
        });
    }

    public function inVotingState()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'voting',
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
                'state' => 'results_pending',
                'voting_locked' => true,
            ];
        });
    }
}
