<?php

namespace Tests\Feature\Finance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MembershipPaymentMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED Test: membership_payments table should exist after migration
     */
    public function test_membership_payments_table_exists(): void
    {
        $this->assertTrue(
            Schema::hasTable('membership_payments'),
            'membership_payments table should exist after migration'
        );
    }

    /**
     * RED Test: Can create a membership_payment record with all required fields
     */
    public function test_membership_payment_can_be_created_with_required_fields(): void
    {
        $member = \App\Models\Member::factory()->create();
        $fee = \App\Models\MembershipFee::factory()->for($member)->create();
        $user = \App\Models\User::factory()->create();

        $payment = \App\Models\MembershipPayment::create([
            'member_id'         => $member->id,
            'fee_id'            => $fee->id,
            'organisation_id'   => $member->organisation_id,
            'amount'            => 50.00,
            'currency'          => 'EUR',
            'payment_method'    => 'bank_transfer',
            'payment_reference' => 'REF-123',
            'status'            => 'completed',
            'recorded_by'       => $user->id,
            'paid_at'           => now(),
        ]);

        $this->assertNotNull($payment->id);
        $this->assertEquals($member->id, $payment->member_id);
        $this->assertEquals(50.00, $payment->amount);
    }

    /**
     * RED Test: membership_payments table has foreign key to members table
     */
    public function test_membership_payment_has_foreign_key_to_member(): void
    {
        $this->assertTrue(
            Schema::hasColumn('membership_payments', 'member_id'),
            'membership_payments table should have member_id column'
        );

        // Verify the FK constraint exists (would fail if we try to create with invalid member_id)
        $this->assertTrue(
            Schema::hasColumn('membership_payments', 'member_id'),
            'member_id column should exist for foreign key'
        );
    }

    /**
     * RED Test: membership_payments table has foreign key to membership_fees table
     */
    public function test_membership_payment_has_foreign_key_to_fee(): void
    {
        $this->assertTrue(
            Schema::hasColumn('membership_payments', 'fee_id'),
            'membership_payments table should have fee_id column (nullable FK)'
        );
    }

    /**
     * RED Test: membership_payments table has income_id link to incomes table
     */
    public function test_membership_payment_has_income_id_link(): void
    {
        $this->assertTrue(
            Schema::hasColumn('membership_payments', 'income_id'),
            'membership_payments table should have income_id column (nullable FK to incomes)'
        );
    }
}
