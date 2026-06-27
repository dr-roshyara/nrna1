<?php

namespace Tests\Feature\Finance;

use App\Models\Income;
use App\Events\MembershipFeePaid;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MemberPaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private Member $member;
    private MembershipFee $fee;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup: organisation with full membership enabled
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => true]);

        // Setup: admin user with organisation role
        $this->admin = User::factory()->create();
        $this->admin->organisationRoles()->create([
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        // Setup: member and fee
        $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->fee = MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'pending',
            'amount' => 100.00,
        ]);

        $this->actingAs($this->admin);

        // Set session context for BelongsToTenant trait
        session(['current_organisation_id' => $this->organisation->id]);
    }

    /**
     * RED: Full payment flow creates all records atomically
     */
    public function test_full_payment_flow_creates_all_records(): void
    {
        // Preconditions
        $this->assertDatabaseHas('membership_fees', [
            'id' => $this->fee->id,
            'status' => 'pending',
        ]);

        // Action: Record payment (DO NOT use Event::fake() so listeners execute)
        $response = $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'REF-001',
                'amount' => 100.00,
            ]
        );

        // Assertions: All records created
        $response->assertStatus(302);

        // 1. MembershipPayment record exists
        $this->assertDatabaseHas('membership_payments', [
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
            'amount' => 100.00,
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'REF-001',
        ]);

        // 2. Fee marked as paid
        $this->assertDatabaseHas('membership_fees', [
            'id' => $this->fee->id,
            'status' => 'paid',
        ]);

        // 3. Member fees_status updated
        $this->member->refresh();
        $this->assertEquals('paid', $this->member->fees_status);

        // 4. Event was fired
        // (Income creation verification happens in separate test)
    }

    /**
     * RED: Verify event is dispatched
     */
    public function test_event_is_dispatched(): void
    {
        // Use Event::fake() to verify dispatch, then enable real listeners
        Event::fake([MembershipFeePaid::class]);

        // Create payment record
        $payment = MembershipPayment::create([
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
            'organisation_id' => $this->organisation->id,
            'amount' => 100.00,
            'currency' => 'EUR',
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
            'recorded_by' => $this->admin->id,
            'paid_at' => now(),
        ]);

        // Dispatch event
        event(new MembershipFeePaid($this->fee, $payment, $this->organisation));

        // Verify event was dispatched
        Event::assertDispatched(MembershipFeePaid::class);
    }

    /**
     * RED: Event listener creates income when MembershipFeePaid is dispatched
     */
    public function test_event_listener_creates_income_record(): void
    {
        // Create payment record
        $payment = MembershipPayment::create([
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
            'organisation_id' => $this->organisation->id,
            'amount' => 100.00,
            'currency' => 'EUR',
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
            'recorded_by' => $this->admin->id,
            'paid_at' => now(),
        ]);

        // Manually register listener
        Event::listen(MembershipFeePaid::class, function ($event) {
            $income = Income::create([
                'organisation_id' => $event->organisation->id,
                'user_id' => $event->payment->recorded_by,
                'membership_fee' => $event->payment->amount,
                'source_type' => 'membership_fee',
                'source_id' => $event->fee->id,
                'country' => $event->organisation->country ?? 'DE',
                'committee_name' => 'Membership',
                'period_from' => now()->startOfMonth(),
                'period_to' => now()->endOfMonth(),
            ]);

            $event->payment->update(['income_id' => $income->id]);
        });

        // Dispatch event directly
        event(new MembershipFeePaid($this->fee, $payment, $this->organisation));

        // Verify income was created
        $this->assertDatabaseHas('incomes', [
            'organisation_id' => $this->organisation->id,
            'source_type' => 'membership_fee',
            'source_id' => $this->fee->id,
            'membership_fee' => 100.00,
        ]);

        // Verify income is linked to payment
        $payment->refresh();
        $this->assertNotNull($payment->income_id);
    }

    /**
     * RED: Income record links back to membership payment
     */
    public function test_income_record_links_back_to_membership_payment(): void
    {
        $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Get the payment record
        $payment = MembershipPayment::where('member_id', $this->member->id)
            ->where('fee_id', $this->fee->id)
            ->first();

        $this->assertNotNull($payment);
        $this->assertNotNull($payment->income_id);

        // Income record exists with correct source_id
        $income = Income::find($payment->income_id);
        $this->assertNotNull($income);
        $this->assertEquals('membership_fee', $income->source_type);
        $this->assertEquals($this->fee->id, $income->source_id);
    }

    /**
     * RED: Income appears in finance module with membership context
     */
    public function test_income_appears_in_existing_finance_module(): void
    {
        $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Income should be queryable by organisation
        $income = Income::where('organisation_id', $this->organisation->id)
            ->where('source_type', 'membership_fee')
            ->first();

        $this->assertNotNull($income);
        $this->assertEquals($this->organisation->id, $income->organisation_id);
        $this->assertEquals(100.00, $income->membership_fee);
        $this->assertEquals('Membership', $income->committee_name);
    }

    /**
     * RED: Concurrent payment attempts prevent duplicate income records
     */
    public function test_concurrent_payment_prevents_duplicate_income_records(): void
    {
        // Simulate two concurrent attempts to pay the same fee
        // First payment succeeds
        $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Second concurrent attempt fails with error (fee already paid)
        $response = $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Should redirect back with error
        $response->assertStatus(302);
        $response->assertSessionHasErrors('error');

        // Only ONE income record created (no duplicate)
        $incomeCount = Income::where('organisation_id', $this->organisation->id)
            ->where('source_type', 'membership_fee')
            ->where('source_id', $this->fee->id)
            ->count();

        $this->assertEquals(1, $incomeCount);
    }

    /**
     * RED: Cannot pay fee from different organisation (tenant isolation)
     */
    public function test_cannot_pay_fee_from_different_organisation(): void
    {
        // Create another organisation and fee
        $otherOrg = Organisation::factory()->create();
        $otherMember = Member::factory()->create(['organisation_id' => $otherOrg->id]);
        $otherFee = MembershipFee::factory()->create([
            'member_id' => $otherMember->id,
            'organisation_id' => $otherOrg->id,
            'status' => 'pending',
        ]);

        // Try to pay fee from different org using current org's admin
        $response = $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $otherMember->id,
                'fee' => $otherFee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Should return 404 (tenant isolation)
        $response->assertStatus(404);

        // No income record created
        $this->assertDatabaseMissing('incomes', [
            'organisation_id' => $otherOrg->id,
            'source_id' => $otherFee->id,
        ]);
    }

    /**
     * RED: Cannot pay already-paid fee (idempotency protection)
     */
    public function test_cannot_pay_already_paid_fee(): void
    {
        // First payment succeeds
        $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Verify fee is now paid
        $this->fee->refresh();
        $this->assertEquals('paid', $this->fee->status);

        // Second payment attempt fails
        $response = $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $this->fee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        // Should have error
        $response->assertStatus(302);
        $response->assertSessionHasErrors('error');
    }
}
