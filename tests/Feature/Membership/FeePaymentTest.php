<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class FeePaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private \App\Models\Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organisation = \App\Models\Organisation::factory()->create();
        $this->admin = User::factory()
            ->hasAttached($this->organisation, ['role' => 'admin'])
            ->create();
    }

    /** @test */
    public function guest_cannot_record_payment(): void
    {
        $response = $this->post('/organisations/' . $this->organisation->id . '/members/record-payment', [
            'fee_id' => FeeId::generate()->value(),
            'payment_method' => 'bank_transfer',
            'transaction_reference' => 'TXN-123',
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_cannot_record_payment(): void
    {
        $user = User::factory()
            ->hasAttached($this->organisation, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->post(
            '/organisations/' . $this->organisation->id . '/members/record-payment',
            [
                'fee_id' => FeeId::generate()->value(),
                'payment_method' => 'bank_transfer',
                'transaction_reference' => 'TXN-123',
            ]
        );

        $response->assertForbidden();
    }

    /** @test */
    public function record_payment_marks_fee_as_paid(): void
    {
        [$member, $fee] = $this->createTestFeeWithMember($this->organisation->id);

        $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => 'TXN-123',
            ]
        );

        $fee->refresh();
        $this->assertEquals('paid', $fee->status);
    }

    /** @test */
    public function record_payment_persists_payment_method(): void
    {
        [$member, $fee] = $this->createTestFeeWithMember($this->organisation->id);

        $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'card',
                'transaction_reference' => 'TXN-456',
            ]
        );

        $fee->refresh();
        $this->assertEquals('card', $fee->payment_method);
    }

    /** @test */
    public function record_payment_persists_transaction_reference(): void
    {
        [$member, $fee] = $this->createTestFeeWithMember($this->organisation->id);
        $txnRef = 'TXN-789-ABC';

        $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'cash',
                'transaction_reference' => $txnRef,
            ]
        );

        $fee->refresh();
        $this->assertEquals($txnRef, $fee->transaction_reference);
    }

    /** @test */
    public function record_payment_with_duplicate_transaction_reference_throws(): void
    {
        [$member1, $fee1] = $this->createTestFeeWithMember($this->organisation->id);
        [$member2, $fee2] = $this->createTestFeeWithMember($this->organisation->id);
        $txnRef = 'TXN-DUPLICATE';

        // Record first payment
        $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member1->id . '/record-payment',
            [
                'fee_id' => $fee1->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => $txnRef,
            ]
        );

        // Attempt to record duplicate transaction reference
        $response = $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member2->id . '/record-payment',
            [
                'fee_id' => $fee2->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => $txnRef,
            ]
        );

        // Should receive error response
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function cannot_pay_fee_from_different_tenant(): void
    {
        $otherOrg = \App\Models\Organisation::factory()->create();
        $otherAdmin = User::factory()
            ->hasAttached($otherOrg, ['role' => 'admin'])
            ->create();

        [$member, $fee] = $this->createTestFeeWithMember($this->organisation->id);

        // Try to pay fee from different org using a member from that org (should not find the fee)
        $otherMember = \App\Models\Member::factory()
            ->for($otherOrg)
            ->create();

        $response = $this->actingAs($otherAdmin)->post(
            '/organisations/' . $otherOrg->id . '/members/' . $otherMember->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => 'TXN-123',
            ]
        );

        $response->assertNotFound();
    }

    /** @test */
    public function cannot_pay_already_paid_fee(): void
    {
        [$member, $fee] = $this->createTestFeeWithMember($this->organisation->id);

        // Mark fee as paid through handler (not direct DB update)
        $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => 'TXN-999',
            ]
        );

        // Try to pay again - should fail since fee is already paid
        $response = $this->actingAs($this->admin)->post(
            '/organisations/' . $this->organisation->id . '/members/' . $member->id . '/record-payment',
            [
                'fee_id' => $fee->id,
                'payment_method' => 'bank_transfer',
                'transaction_reference' => 'TXN-999-DUPLICATE',
            ]
        );

        $response->assertSessionHasErrors();
    }

    private function createTestFee(string $organisationId): \App\Models\MembershipFee
    {
        $member = \App\Models\Member::factory()
            ->for(\App\Models\Organisation::find($organisationId))
            ->create();

        return \App\Models\MembershipFee::factory()
            ->for($member)
            ->for(\App\Models\Organisation::find($organisationId))
            ->create([
                'status' => 'pending',
            ]);
    }

    private function createTestFeeWithMember(string $organisationId): array
    {
        $member = \App\Models\Member::factory()
            ->for(\App\Models\Organisation::find($organisationId))
            ->create();

        $fee = \App\Models\MembershipFee::factory()
            ->for($member)
            ->for(\App\Models\Organisation::find($organisationId))
            ->create([
                'status' => 'pending',
            ]);

        return [$member, $fee];
    }
}
