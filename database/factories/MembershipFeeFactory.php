<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MembershipFee>
 */
class MembershipFeeFactory extends Factory
{
    protected $model = MembershipFee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'member_id' => Member::factory(),
            'membership_type_id' => MembershipType::factory(),
            'amount' => 100.00,
            'currency' => 'EUR',
            'fee_amount_at_time' => 100.00,
            'currency_at_time' => 'EUR',
            'period_label' => '2025',
            'due_date' => now()->addMonth(),
            'paid_at' => null,
            'status' => 'pending',
            'payment_method' => null,
            'payment_reference' => null,
            'idempotency_key' => null,
            'recorded_by' => null,
            'notes' => null,
        ];
    }

    /**
     * State for paid fee
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * State for overdue fee
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => now()->subMonth(),
        ]);
    }

    /**
     * State for waived fee
     */
    public function waived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'waived',
            'paid_at' => now(),
        ]);
    }
}
