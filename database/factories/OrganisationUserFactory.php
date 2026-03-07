<?php

namespace Database\Factories;

use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganisationUser>
 */
class OrganisationUserFactory extends Factory
{
    protected $model = OrganisationUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['owner', 'admin', 'staff', 'member']),
            'status' => 'active',
            'joined_at' => now(),
        ];
    }

    /**
     * State for owner role
     */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'owner',
        ]);
    }

    /**
     * State for admin role
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State for staff role
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'staff',
        ]);
    }

    /**
     * State for member role
     */
    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'member',
        ]);
    }

    /**
     * State for inactive status
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * State for suspended status
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}
