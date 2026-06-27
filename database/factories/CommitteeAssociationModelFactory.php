<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel>
 */
final class CommitteeAssociationModelFactory extends Factory
{
    protected $model = \App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel::class;

    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'association_id' => (string) \Illuminate\Support\Str::uuid(),
            'lineage_id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => Organisation::factory(),
            'member_id' => User::factory(),
            'committee_id' => CommitteeModel::factory(),
            'association_type' => $this->faker->randomElement(['residence', 'exception', 'manual']),
            'status' => 'active',
            'associated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terminated',
        ]);
    }
}
