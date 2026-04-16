<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoterInvitation>
 */
class VoterInvitationFactory extends Factory
{
    protected $model = VoterInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'user_id' => User::factory(),
            'organisation_id' => Organisation::factory(),
            'token' => Str::random(64),
            'email_status' => 'pending',
            'email_error' => null,
            'sent_at' => null,
            'used_at' => null,
            'expires_at' => now()->addDays(7),
        ];
    }

    /**
     * State for sent invitation
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * State for failed invitation
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_status' => 'failed',
            'email_error' => 'SMTP connection failed',
        ]);
    }

    /**
     * State for used invitation
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_status' => 'sent',
            'sent_at' => now()->subDay(),
            'used_at' => now(),
        ]);
    }

    /**
     * State for expired invitation
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }
}
