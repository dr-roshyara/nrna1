<?php

namespace Database\Factories;

use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionFactory extends Factory
{
    protected $model = Election::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['demo', 'real']),
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ];
    }

    public function demo()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'demo',
            ];
        });
    }

    public function real()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'real',
            ];
        });
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
