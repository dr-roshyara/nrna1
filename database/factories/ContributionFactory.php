<?php

namespace Database\Factories;

use App\Models\Contribution;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContributionFactory extends Factory
{
    protected $model = Contribution::class;

    public function definition(): array
    {
        $organisation = Organisation::withoutGlobalScopes()
            ->where('is_default', true)
            ->first();

        $user = User::withoutGlobalScopes()->first();

        return [
            'organisation_id' => $organisation?->id,
            'user_id'         => $user?->id,
            'created_by'      => $user?->id,
            'title'           => $this->faker->sentence(4),
            'description'     => $this->faker->paragraph(),
            'track'           => $this->faker->randomElement(['micro', 'standard', 'major']),
            'status'          => 'approved',
            'effort_units'    => $this->faker->numberBetween(1, 10),
            'proof_type'      => 'self_report',
            'team_skills'     => ['teaching'],
            'is_recurring'    => false,
            'outcome_bonus'   => 0,
            'calculated_points' => 0,
        ];
    }
}
