<?php

namespace Database\Factories;

use App\Models\DemoCandidacy;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DemoCandidacyFactory extends Factory
{
    protected $model = DemoCandidacy::class;

    /**
     * Define the model's default state.
     *
     * CRITICAL: election_id must ALWAYS be set to satisfy NOT NULL constraint.
     * Creates a new election if not explicitly provided.
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'candidacy_id' => $this->faker->unique()->word(),
            'candidacy_name' => $this->faker->name(),
            'proposer_id' => $this->faker->unique()->word(),
            'proposer_name' => $this->faker->name(),
            'supporter_id' => $this->faker->unique()->word(),
            'supporter_name' => $this->faker->name(),
            'post_id' => 'PRES',
            'post_name' => 'President',
            'image_path_1' => $this->faker->word() . '.png',
            'image_path_2' => null,
            'image_path_3' => null,
            'election_id' => Election::factory(), // CRITICAL: Always create an election
            'organisation_id' => null, // MODE 1 by default
            'position_order' => 1,
        ];
    }

    /**
     * Set organisation_id for MODE 2 demo election
     */
    public function forOrganisation($organisationId)
    {
        return $this->state(function (array $attributes) use ($organisationId) {
            return [
                'organisation_id' => $organisationId,
            ];
        });
    }
}
