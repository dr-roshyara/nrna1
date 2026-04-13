<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionMembershipFactory extends Factory
{
    protected $model = ElectionMembership::class;

    public function definition(): array
    {
        // Don't auto-create related objects - tests provide IDs explicitly
        // This prevents factory from creating mismatched organisations/elections
        return [
            'id' => fake()->uuid(),
            'organisation_id' => null,  // Must be provided by test
            'election_id' => null,      // Must be provided by test
            'user_id' => null,          // Must be provided by test
            'role' => 'voter',
            'status' => 'active',
            'metadata' => [],
            'has_voted' => false,
            'suspension_status' => 'none',
        ];
    }

    public function voter(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'voter',
            'status' => 'active',
        ]);
    }

    public function admin(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function candidate(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'candidate',
            'status' => 'active',
        ]);
    }
}
