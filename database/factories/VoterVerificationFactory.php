<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoterVerificationFactory extends Factory
{
    protected $model = VoterVerification::class;

    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'election_id' => Election::factory(),
            'user_id' => User::factory(),
            'organisation_id' => Organisation::factory(),
            'verified_ip' => fake()->ipv4(),
            'verified_device_fingerprint_hash' => fake()->sha256(),
            'verified_device_components' => [
                'userAgent' => fake()->userAgent(),
                'language' => 'en-US',
                'screenResolution' => '1920x1080',
            ],
            'verified_by' => User::factory(),
            'verified_at' => now(),
            'notes' => fake()->sentence(),
            'status' => 'active',
            'revoked_by' => null,
            'revoked_at' => null,
        ];
    }

    public function revoked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'revoked',
                'revoked_by' => User::factory(),
                'revoked_at' => now(),
            ];
        });
    }
}
