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

    /**
     * Create organisation with worldwide scope
     */
    public function worldwide()
    {
        return $this->state([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'worldwide',
            'geographic_levels' => [
                ['index' => 1, 'type' => 'static', 'db_level' => null, 'label' => 'Worldwide'],
                ['index' => 2, 'type' => 'region', 'db_level' => null, 'label' => 'Region'],
                ['index' => 3, 'type' => 'country', 'db_level' => null, 'label' => 'Country'],
                ['index' => 4, 'type' => 'geo_unit', 'db_level' => 1, 'label' => 'Province'],
            ],
        ]);
    }

    /**
     * Create organisation with Nepal scope
     */
    public function nepal()
    {
        return $this->state([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'NP',
            'geographic_levels' => [
                ['index' => 1, 'type' => 'geo_unit', 'db_level' => 1, 'label' => 'Province'],
                ['index' => 2, 'type' => 'geo_unit', 'db_level' => 2, 'label' => 'District'],
                ['index' => 3, 'type' => 'geo_unit', 'db_level' => 3, 'label' => 'Municipality'],
                ['index' => 4, 'type' => 'geo_unit', 'db_level' => 4, 'label' => 'Ward'],
            ],
        ]);
    }
}
