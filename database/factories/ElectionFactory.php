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
     * For demo elections with organisation_id=0, convert to platform org (ID=1)
     */
    public function create($attributes = [], ?\Illuminate\Database\Eloquent\Model $parent = null)
    {
        // Get or create platform organisation
        $platformOrg = Organisation::where('slug', 'platform')->first();
        if (!$platformOrg) {
            $platformOrg = Organisation::create([
                'name' => 'Platform',
                'slug' => 'platform',
                'type' => 'other',
            ]);
        }

        // Handle organisation_id
        if (isset($attributes['organisation_id'])) {
            // If organisation_id is 0 (old sentinel value), convert to platform org ID
            if ($attributes['organisation_id'] === 0 || $attributes['organisation_id'] === '0') {
                $attributes['organisation_id'] = $platformOrg->id;
            } elseif ($attributes['organisation_id']) {
                // If organisation_id is provided and non-zero, ensure it exists
                $org_id = $attributes['organisation_id'];
                if (!Organisation::find($org_id)) {
                    // Create a test organisation - let auto_increment assign the ID naturally
                    $org = Organisation::create([
                        'name' => 'Test Organisation',
                        'slug' => 'test-org-' . uniqid(),
                        'type' => 'other',
                    ]);

                    // Use the created organisation's actual ID
                    $attributes['organisation_id'] = $org->id;
                }
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
