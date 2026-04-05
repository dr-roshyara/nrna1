<?php

namespace Database\Factories;

use App\Models\MembershipType;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MembershipTypeFactory extends Factory
{
    protected $model = MembershipType::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'organisation_id'    => Organisation::factory(),
            'name'               => $name,
            'slug'               => Str::slug($name) . '-' . Str::random(4),
            'description'        => $this->faker->sentence(),
            'grants_voting_rights' => false,
            'fee_amount'         => $this->faker->randomFloat(2, 10, 200),
            'fee_currency'       => 'EUR',
            'duration_months'    => 12,
            'requires_approval'  => false,
            'is_active'          => true,
            'sort_order'         => 0,
        ];
    }

    /** Full Member — grants voting rights */
    public function fullMember(): static
    {
        return $this->state(fn () => ['grants_voting_rights' => true]);
    }

    /** Associate Member — observer only */
    public function associateMember(): static
    {
        return $this->state(fn () => ['grants_voting_rights' => false]);
    }
}
