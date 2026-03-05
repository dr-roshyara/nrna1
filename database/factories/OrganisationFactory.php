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
            'type' => 'tenant',
            'is_default' => false,
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
            'settings' => [],
            'languages' => ['de', 'en'],
        ];
    }

    /**
     * Create a platform organisation
     */
    public function platform()
    {
        return $this->state([
            'type' => 'platform',
            'is_default' => true,
            'slug' => 'platform',
            'name' => 'Platform',
        ]);
    }

    /**
     * Create a tenant organisation
     */
    public function tenant()
    {
        return $this->state([
            'type' => 'tenant',
            'is_default' => false,
        ]);
    }
}
