<?php

namespace Database\Factories;

use App\Models\DemoPost;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DemoPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DemoPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(3, true);

        return [
            'election_id' => Election::factory(),
            'post_id' => 'post-' . Str::random(8),
            'name' => $name,
            'nepali_name' => $name,
            'position_order' => $this->faker->numberBetween(1, 10),
            'required_number' => $this->faker->numberBetween(1, 3),
            'is_national_wide' => true,
            'state_name' => null,
        ];
    }

    /**
     * Indicate that this is a regional post
     */
    public function regional($region = 'Bayern')
    {
        return $this->state([
            'is_national_wide' => false,
            'state_name' => $region,
        ]);
    }
}
