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
            'post_id' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'name' => $this->faker->word(),
            'nepali_name' => $this->faker->word(),
            'required_number' => $this->faker->numberBetween(1, 10),
            'position_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
