<?php

namespace Database\Factories;

use App\Models\DemoCode;
use App\Models\Election;
use App\Models\User;
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
            'election_id' => Election::factory(),
            'user_id' => User::factory(),
            'code1' => 'DEMO' . strtoupper(Str::random(8)),
            'code2' => 'DEMO' . strtoupper(Str::random(8)),
            'is_code1_usable' => true,
            'is_code2_usable' => true,
            'code1_used_at' => null,
            'code2_used_at' => null,
            'has_code1_sent' => true,
            'has_code2_sent' => false,
            'can_vote_now' => false,
            'has_voted' => false,
            'voting_time_in_minutes' => 30,
            'client_ip' => '127.0.0.1',
        ];
    }
}
