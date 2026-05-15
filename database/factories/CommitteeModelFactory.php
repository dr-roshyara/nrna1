<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Contexts\Membership\Infrastructure\Models\CommitteeModel>
 */
class CommitteeModelFactory extends Factory
{
    protected $model = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => \App\Models\Organisation::factory(),
            'name' => $this->faker->word,
            'code' => strtoupper($this->faker->word),
            'slug' => $this->faker->unique()->slug,
            'type' => null,
            'level' => 0,
            'status' => 'active',
            'formation_date' => now(),
        ];
    }
}
