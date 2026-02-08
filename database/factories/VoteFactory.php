<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * Votes table stores votes as JSON in candidate_01 through candidate_60 columns.
     * No user_id column for anonymity (votes are anonymous).
     *
     * @return array
     */
    public function definition()
    {
        return [
            'election_id' => Election::factory()->real(),
            'no_vote_option' => false,
            'voting_code' => $this->faker->unique()->word() . '-' . $this->faker->randomNumber(5),
            'candidate_01' => json_encode(['candidacy_id' => $this->faker->word()]),
            'candidate_02' => null,
            'candidate_03' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Create vote for a specific election
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
