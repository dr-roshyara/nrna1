<?php

namespace Database\Factories;

use App\Models\DemoCandidacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * DemoCandidacyFactory
 *
 * Generates demo candidates for testing election voting workflows.
 * Demo candidates are much simpler than real candidacies - just post, user, and ordering.
 *
 * Usage:
 *   DemoCandidacy::factory()->create()
 *   DemoCandidacy::factory()->create(['post_id' => $post->id])
 *   DemoCandidacy::factory()->count(5)->create(['post_id' => $post->id])
 */
class DemoCandidacyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DemoCandidacy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'election_id'    => null, // MUST be explicitly set when creating via factory
            'organisation_id' => null, // MUST be explicitly set when creating via factory
            'user_id'        => User::factory()->create(['region' => 'Test Region'])->id,
            'name'           => $this->faker->name(),
            'user_name'      => null,
            'position_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
