<?php

namespace Database\Factories;

use App\Models\DemoCode;
use App\Models\Election;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DemoCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DemoCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organisation_id' => Organisation::factory(),
            'election_id' => Election::factory(),
            'user_id' => User::factory(),
            'code_to_open_voting_form' => 'DEMO' . strtoupper(Str::random(8)),
            'code_to_save_vote' => 'DEMO' . strtoupper(Str::random(8)),
            'is_code_to_open_voting_form_usable' => true,
            'is_code_to_save_vote_usable' => true,
            'code_to_open_voting_form_used_at' => null,
            'code_to_save_vote_used_at' => null,
            'has_code1_sent' => true,
            'has_code2_sent' => false,
            'can_vote_now' => false,
            'has_voted' => false,
            'voting_time_in_minutes' => 30,
            'client_ip' => '127.0.0.1',
        ];
    }
}
