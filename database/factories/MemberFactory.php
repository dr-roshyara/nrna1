<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\OrganisationUser;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id'      => Organisation::factory(),
            'organisation_user_id' => OrganisationUser::factory(),
            'membership_type_id'   => null,
            'membership_number'    => 'M' . uniqid(),
            'status'               => 'active',
            'fees_status'          => 'unpaid',
            'joined_at'            => now(),
            'membership_expires_at' => null,
            'last_renewed_at'      => null,
        ];
    }

    /**
     * State for expired membership
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'membership_expires_at' => now()->subDay(),
        ]);
    }

    /**
     * State for suspended membership
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * State with expiry date
     */
    public function withExpiry($date = null): static
    {
        return $this->state(fn (array $attributes) => [
            'membership_expires_at' => $date ?? now()->addYear(),
        ]);
    }
}
