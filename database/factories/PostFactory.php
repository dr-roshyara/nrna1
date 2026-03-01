<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'election_id' => Election::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'is_national_wide' => $this->faker->boolean(70),
            'state_name' => $this->faker->optional()->state(),
            'required_number' => $this->faker->numberBetween(1, 5),
            'select_all_required' => $this->faker->boolean(80),
            'position_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
