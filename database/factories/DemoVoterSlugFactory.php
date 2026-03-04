<?php

namespace Database\Factories;

use App\Models\DemoVoterSlug;
use App\Models\User;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemoVoterSlug>
 */
class DemoVoterSlugFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DemoVoterSlug::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => null,
            'user_id' => User::factory(),
            'election_id' => Election::factory()->create(['type' => 'demo']),
            'slug' => Str::random(40),
            'expires_at' => now()->addHour(),
            'is_active' => true,
            'current_step' => 0,
            'step_meta' => [],
            'has_voted' => false,
            'can_vote_now' => true,
            'voting_time_min' => config('voting.time_in_minutes', 30),
        ];
    }

    /**
     * Indicate that the demo voter slug is for an organisation.
     */
    public function forOrganisation($organisationId): static
    {
        return $this->state(fn (array $attributes) => [
            'organisation_id' => $organisationId,
        ]);
    }

    /**
     * Indicate that the demo voter slug has already voted.
     */
    public function hasVoted(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_voted' => true,
            'current_step' => 5,
        ]);
    }

    /**
     * Indicate that the demo voter slug cannot vote.
     */
    public function cannotVote(): static
    {
        return $this->state(fn (array $attributes) => [
            'can_vote_now' => false,
        ]);
    }

    /**
     * Indicate that the demo voter slug is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subHour(),
        ]);
    }

    /**
     * Indicate that the demo voter slug is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
