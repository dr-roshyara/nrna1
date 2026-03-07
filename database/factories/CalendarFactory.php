<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calendar>
 */
class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'google_id' => $this->faker->unique()->uuid(),
            'name' => $this->faker->name(),
            'color' => $this->faker->hexColor(),
            'timezone' => $this->faker->timezone(),
            'organisation_id' => Organisation::factory(),
        ];
    }
}
