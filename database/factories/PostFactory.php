<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $election = Election::factory()->create();

        return [
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'name' => $this->faker->word(),
            'is_national_wide' => $this->faker->boolean(70),
            'state_name' => $this->faker->optional()->state(),
            'required_number' => $this->faker->numberBetween(1, 5),
            'position_order' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function forElection(Election $election)
    {
        return $this->state(function (array $attributes) use ($election) {
            return [
                'organisation_id' => $election->organisation_id,
                'election_id' => $election->id,
            ];
        });
    }
}
