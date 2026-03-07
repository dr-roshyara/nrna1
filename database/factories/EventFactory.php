<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Calendar;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $org = Organisation::factory()->create();

        return [
            'calendar_id' => Calendar::factory()->create(['organisation_id' => $org->id])->id,
            'google_id' => $this->faker->unique()->uuid(),
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'allday' => $this->faker->boolean(),
            'started_at' => $this->faker->dateTime(),
            'ended_at' => $this->faker->dateTime(),
            'organisation_id' => $org->id,
        ];
    }
}
