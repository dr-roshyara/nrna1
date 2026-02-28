<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Organisation;
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
            // organisation_id will be set by model boot() if not provided
        ];
    }

    /**
     * Ensure organisation exists when created.
     */
    public function create($attributes = [], ?\Illuminate\Database\Eloquent\Model $parent = null)
    {
        // If organisation_id is provided, ensure it exists
        if (isset($attributes['organisation_id']) && $attributes['organisation_id']) {
            $org_id = $attributes['organisation_id'];
            if (!Organisation::find($org_id)) {
                Organisation::create([
                    'id' => $org_id,
                    'name' => 'Test Organisation',
                    'slug' => 'test-org-' . $org_id,
                    'type' => 'other', // Must use valid enum value
                ]);
            }
        }

        return parent::create($attributes, $parent);
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
