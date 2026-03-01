<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganisationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();

        return [
            'name' => $name,
            'email' => $this->faker->unique()->companyEmail(),
            'slug' => Str::slug($name),
            'type' => $this->faker->randomElement(['diaspora', 'ngo', 'professional', 'other']),
            'address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'zip' => $this->faker->postcode(),
                'country' => 'DE',
            ],
            'representative' => [
                'name' => $this->faker->name(),
                'role' => 'Chairman',
                'email' => $this->faker->email(),
            ],
            'created_by' => \App\Models\User::factory(),
            'settings' => [],
            'languages' => ['de', 'en'],
        ];
    }
}
