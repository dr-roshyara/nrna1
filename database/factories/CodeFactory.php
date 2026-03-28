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
            'is_code_to_save_vote_usable' => 1,
            'can_vote_now' => 0,
            'has_voted' => 0,
            'vote_submitted' => 0,
            'client_ip' => $this->faker->ipv4(),
            'code_to_open_voting_form'           => strtoupper(\Illuminate\Support\Str::random(8)),
            'code_to_open_voting_form_sent_at'   => now(),
            'is_code_to_open_voting_form_usable' => 1,
            'has_code1_sent'                     => 1,
            'voting_time_in_minutes'             => 30,
        ];
    }

    /**
     * Indicate that the code has been used (code1 spent)
     */
    public function used()
    {
        return $this->state(fn () => [
            'is_code_to_save_vote_usable' => 0,
        ]);
    }

    /**
     * Indicate that the voter has already voted
     */
    public function voted()
    {
        return $this->state(fn () => [
            'has_voted'                          => true,
            'vote_submitted'                     => true,
            'can_vote_now'                       => 0,
            'is_code_to_open_voting_form_usable' => 0,
        ]);
    }

    /**
     * Indicate that the code has been verified (can_vote_now = 1)
     */
    public function verified()
    {
        return $this->state(fn () => [
            'can_vote_now'                       => 1,
            'is_code_to_open_voting_form_usable' => 0,
            'code_to_open_voting_form_used_at'   => now(),
        ]);
    }
}
