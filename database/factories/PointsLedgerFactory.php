<?php

namespace Database\Factories;

use App\Models\Contribution;
use App\Models\Organisation;
use App\Models\PointsLedger;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PointsLedgerFactory extends Factory
{
    protected $model = PointsLedger::class;

    public function definition(): array
    {
        $organisation = Organisation::withoutGlobalScopes()
            ->where('is_default', true)
            ->first();

        $user = User::withoutGlobalScopes()->first();

        return [
            'organisation_id' => $organisation?->id,
            'user_id'         => $user?->id,
            'contribution_id' => Contribution::factory(),
            'points'          => $this->faker->numberBetween(5, 50),
            'action'          => 'earned',
            'reason'          => 'Test ledger entry',
            'created_by'      => $user?->id,
        ];
    }
}
