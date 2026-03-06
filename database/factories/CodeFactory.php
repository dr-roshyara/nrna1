<?php

namespace Database\Factories;

use App\Models\Code;
use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CodeFactory extends Factory
{
    protected $model = Code::class;

    public function definition()
    {
        $platform = Organisation::getDefaultPlatform();

        return [
            'organisation_id' => $platform->id,
            'user_id' => function() {
                $platform = Organisation::getDefaultPlatform();
                return User::factory()->forOrganisation($platform)->create()->id;
            },
            'election_id' => Election::factory(),
            'code1' => $this->faker->numerify('######'),
            'code2' => $this->faker->numerify('######'),
            'vote_show_code' => $this->faker->numerify('########'),
            'is_code1_usable' => 1,
            'code1_sent_at' => now(),
            'can_vote_now' => 0,
            'has_voted' => 0,
            'voting_time_in_minutes' => 20,
            'vote_submitted' => 0,
            'has_code1_sent' => 1,
            'has_code2_sent' => 0,
            'client_ip' => $this->faker->ipv4(),
            'has_agreed_to_vote' => 0,
            'has_used_code1' => 0,
            'has_used_code2' => 0,
        ];
    }

    /**
     * Indicate that the code has been used
     */
    public function used()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_code1_usable' => 0,
                'has_used_code1' => 1,
                'code1_used_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the user has agreed to vote
     */
    public function agreedToVote()
    {
        return $this->state(function (array $attributes) {
            return [
                'has_agreed_to_vote' => 1,
                'has_agreed_to_vote_at' => now(),
            ];
        });
    }

    /**
     * Indicate that voting has started
     */
    public function votingStarted()
    {
        return $this->state(function (array $attributes) {
            return [
                'voting_started_at' => now(),
                'can_vote_now' => 1,
            ];
        });
    }

    /**
     * Indicate that the vote has been submitted
     */
    public function submitted()
    {
        return $this->state(function (array $attributes) {
            return [
                'vote_submitted' => 1,
                'vote_submitted_at' => now(),
                'has_voted' => 1,
            ];
        });
    }
}
