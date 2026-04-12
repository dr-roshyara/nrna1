<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\PublicDemoSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicDemoSessionFactory extends Factory
{
    protected $model = PublicDemoSession::class;

    public function definition(): array
    {
        return [
            'session_token' => Str::random(40),
            'election_id' => Election::factory(),
            'display_code' => strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'current_step' => 1,
            'code_verified' => false,
            'agreed' => false,
            'candidate_selections' => null,
            'has_voted' => false,
            'voted_at' => null,
            'expires_at' => now()->addMinutes(30),
        ];
    }
}
