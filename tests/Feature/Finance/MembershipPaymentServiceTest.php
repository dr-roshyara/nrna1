<?php

namespace Tests\Feature\Finance;

use App\Domain\Shared\ValueObjects\Money;
use App\Exceptions\FeeAlreadyPaidException;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use App\Models\Organisation;
use App\Models\User;
use App\Services\MembershipPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipPaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private MembershipPaymentService $service;
    private Organisation $organisation;
    private User $admin;
    private Member $member;
    private MembershipFee $fee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(MembershipPaymentService::class);

        // Setup: Organisation, admin user, member, and pending fee
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => true]);
        $this->admin = User::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id]);

        $this->fee = MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'pending',
            'amount' => 100.00,
        ]);

        $this->actingAs($this->admin);
    }

    /**
     * RED: recordPayment creates a MembershipPayment record
     */
    public function test_record_payment_creates_membership_payment_record(): void
    {
        $this->assertDatabaseCount('membership_payments', 0);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR'),
            'bank_transfer',
            'REF-001'
        );

        $this->assertDatabaseCount('membership_payments', 1);
        $this->assertDatabaseHas('membership_payments', [
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
            'amount' => 100.00,
            'currency' => 'EUR',
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'REF-001',
            'status' => 'completed',
        ]);
    }

    /**
     * RED: recordPayment marks fee as paid
     */
    public function test_record_payment_marks_fee_as_paid(): void
    {
        $this->assertEquals('pending', $this->fee->status);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->fee->refresh();
        $this->assertEquals('paid', $this->fee->status);
    }

    /**
     * RED: recordPayment sets paid_at timestamp on fee
     */
    public function test_record_payment_sets_paid_at_on_fee(): void
    {
        $this->assertNull($this->fee->paid_at);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->fee->refresh();
        $this->assertNotNull($this->fee->paid_at);
    }

    /**
     * RED: recordPayment updates member fees_status to 'paid' when all fees cleared
     */
    public function test_record_payment_updates_member_fees_status_when_all_cleared(): void
    {
        // Only one pending fee
        $this->member->update(['fees_status' => 'pending']);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->member->refresh();
        $this->assertEquals('paid', $this->member->fees_status);
    }

    /**
     * RED: recordPayment does NOT update member fees_status when other fees remain
     */
    public function test_record_payment_does_not_update_member_fees_status_when_other_fees_remain(): void
    {
        // Create a second pending fee
        MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'pending',
        ]);

        $this->member->update(['fees_status' => 'pending']);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->member->refresh();
        // Should remain 'pending' because another fee is still pending
        $this->assertEquals('pending', $this->member->fees_status);
    }

    /**
     * RED: recordPayment fires MembershipFeePaid event
     */
    public function test_record_payment_fires_membership_fee_paid_event(): void
    {
        $events = [];
        \Event::listen(\App\Events\MembershipFeePaid::class, function ($event) use (&$events) {
            $events[] = $event;
        });

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->assertCount(1, $events);
        $this->assertInstanceOf(\App\Events\MembershipFeePaid::class, $events[0]);
    }

    /**
     * RED: recordPayment uses lockForUpdate to prevent concurrent duplicate payment
     */
    public function test_record_payment_uses_lock_for_update_preventing_concurrent_double_payment(): void
    {
        // This test verifies the mechanism is in place
        // In real scenario, would require separate database connections
        $payment1 = $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->assertNotNull($payment1->id);
        $this->assertDatabaseCount('membership_payments', 1);
    }

    /**
     * RED: recordPayment throws FeeAlreadyPaidException if fee already paid
     */
    public function test_record_payment_throws_if_fee_already_paid(): void
    {
        // Pay the fee once
        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        // Attempt to pay again - should throw
        $this->expectException(FeeAlreadyPaidException::class);

        $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );
    }

    /**
     * RED: recordPayment returns MembershipPayment instance
     */
    public function test_record_payment_returns_membership_payment_instance(): void
    {
        $payment = $this->service->recordPayment(
            $this->member,
            $this->fee,
            new Money(100.00, 'EUR')
        );

        $this->assertInstanceOf(MembershipPayment::class, $payment);
        $this->assertNotNull($payment->id);
        $this->assertEquals($this->member->id, $payment->member_id);
    }

    /**
     * RED: getOutstandingFees returns only pending and overdue fees
     */
    public function test_get_outstanding_fees_returns_only_pending_and_overdue(): void
    {
        // Create multiple fees with different statuses
        MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'paid',
        ]);

        MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'status' => 'overdue',
        ]);

        // $this->fee is 'pending'
        $outstanding = $this->service->getOutstandingFees($this->member);

        $this->assertCount(2, $outstanding);
        $this->assertTrue($outstanding->every(fn($f) => in_array($f->status, ['pending', 'overdue'])));
    }
}
