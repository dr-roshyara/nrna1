<?php

namespace Database\Factories;

use App\Models\DemoVote;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

class DemoVoteFactory extends Factory
{
    protected $model = DemoVote::class;

    /**
     * Define the model's default state.
     *
     * Demo votes table stores votes as JSON in candidate_01 through candidate_60 columns.
     * No user_id column for anonymity (votes are anonymous).
     *
     * @return array
     */
    public function definition()
    {
        return [
            'election_id' => Election::factory()->demo(),
            'no_vote_option' => false,
            'voting_code' => $this->faker->unique()->word() . '-' . $this->faker->randomNumber(5),
            'candidate_01' => json_encode(['candidacy_id' => $this->faker->word()]),
            'candidate_02' => null,
            'candidate_03' => null,
            'voted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Create demo vote for a specific election
     */
    public function forElection($election)
    {
        return $this->state(function (array $attributes) use ($election) {
            return [
                'election_id' => $election->id,
            ];
        });
    }
}
