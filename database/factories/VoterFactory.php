<?php

namespace Database\Factories;

use App\Models\Voter;
use App\Models\Member;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voter>
 */
class VoterFactory extends Factory
{
    protected $model = Voter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'member_id' => Member::factory(),
            'election_id' => Election::factory(),
            'status' => 'eligible',
            'ineligibility_reason' => null,
            'has_voted' => false,
            'voted_at' => null,
            'voter_number' => 'V' . uniqid(),
        ];
    }

    /**
     * State for voted status
     */
    public function voted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'voted',
            'has_voted' => true,
            'voted_at' => now(),
        ]);
    }

    /**
     * State for ineligible status
     */
    public function ineligible($reason = 'Member suspended'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ineligible',
            'ineligibility_reason' => $reason,
        ]);
    }

    /**
     * State for eligible status without vote
     */
    public function eligible(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'eligible',
            'has_voted' => false,
            'voted_at' => null,
        ]);
    }
}
