<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Contexts\Membership\Infrastructure\Models\CommitteeModel>
 */
class CommitteeModelFactory extends Factory
{
    protected $model = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['central', 'province', 'district']);

        return [
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => \App\Models\Organisation::factory(),
            'name' => $this->faker->word,
            'code' => strtoupper($this->faker->word),
            'slug' => $this->faker->unique()->slug,
            'type' => $type,
            'level' => 0,
            'status' => 'active',
            'formation_date' => now(),
            'geo_unit_id' => $type === 'central' ? null : function () {
                return \Illuminate\Support\Facades\DB::table('geo_administrative_units')->insertGetId([
                    'country_code' => 'NP',
                    'admin_level' => 1,
                    'admin_type' => 'province',
                    'parent_id' => null,
                    'path' => '/' . \Illuminate\Support\Str::random(2) . '/',
                    'code' => 'NP-' . \Illuminate\Support\Str::random(2),
                    'local_code' => \Illuminate\Support\Str::random(2),
                    'name_local' => json_encode(['en' => \Illuminate\Support\Str::title(\Illuminate\Support\Str::random(10)), 'np' => 'प्रदेश']),
                    'metadata' => json_encode(['total_wards' => rand(10, 20)]),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            },
        ];
    }
}
