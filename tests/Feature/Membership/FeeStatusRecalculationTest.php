<?php

/**
 * TDD — Phase 2: fees_status must be recalculated after MembershipFeePaid event
 *
 * Bug: MembershipFeeController::pay() fires MembershipFeePaid event but no
 * listener updates member.fees_status. The column stays 'unpaid' permanently,
 * meaning voting_rights never advances to 'full' even after the member pays.
 *
 * All tests MUST FAIL before the listener is created (Red).
 */

namespace Tests\Feature\Membership;

use App\Events\MembershipFeePaid;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FeeStatusRecalculationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipType $fullType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->fullType = MembershipType::factory()->fullMember()->create([
            'organisation_id' => $this->org->id,
            'fee_amount'      => 100.00,
        ]);
    }

    private function makeMember(): Member
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        return Member::factory()->create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id'   => $this->fullType->id,
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);
    }

    private function makeFee(Member $member, string $status = 'pending', float $amount = 100.00): MembershipFee
    {
        return MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $member->id,
            'membership_type_id' => $this->fullType->id,
            'amount'             => $amount,
            'currency'           => 'EUR',
            'fee_amount_at_time' => $amount,
            'currency_at_time'   => 'EUR',
            'status'             => $status,
        ]);
    }

    private function makePaymentForEvent(MembershipFee $fee): MembershipPayment
    {
        return MembershipPayment::create([
            'id'                 => (string) Str::uuid(),
            'member_id'          => $fee->member_id,
            'fee_id'             => $fee->id,
            'organisation_id'    => $this->org->id,
            'amount'             => $fee->amount,
            'currency'           => 'EUR',
            'payment_method'     => 'bank_transfer',
            'recorded_by'        => User::factory()->create()->id,
            'paid_at'            => now(),
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Single fee scenarios
    // ══════════════════════════════════════════════════════════════════════════

    public function test_paying_the_only_pending_fee_sets_fees_status_to_paid(): void
    {
        $member = $this->makeMember();
        $fee    = $this->makeFee($member, 'pending');

        // Mark fee as paid and fire event
        $fee->update(['status' => 'paid', 'paid_at' => now()]);
        $payment = $this->makePaymentForEvent($fee->fresh());
        event(new MembershipFeePaid($fee->fresh()->load('member'), $payment, $this->org));

        $this->assertEquals('paid', $member->fresh()->fees_status,
            'fees_status should be paid after only fee is paid');
    }

    public function test_paying_the_only_fee_grants_full_voting_rights(): void
    {
        $member = $this->makeMember();
        $fee    = $this->makeFee($member, 'pending');

        $fee->update(['status' => 'paid', 'paid_at' => now()]);
        $payment = $this->makePaymentForEvent($fee->fresh());
        event(new MembershipFeePaid($fee->fresh()->load('member'), $payment, $this->org));

        $member->refresh()->load('membershipType');
        $this->assertEquals('full', $member->voting_rights,
            'Full Member with paid fees must have full voting rights');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Multiple fee scenarios
    // ══════════════════════════════════════════════════════════════════════════

    public function test_paying_one_of_two_fees_sets_fees_status_to_partial(): void
    {
        $member = $this->makeMember();
        $fee1   = $this->makeFee($member, 'pending', 50.00);
        $fee2   = $this->makeFee($member, 'pending', 50.00);

        // Pay only the first fee
        $fee1->update(['status' => 'paid', 'paid_at' => now()]);
        $payment1 = $this->makePaymentForEvent($fee1->fresh());
        event(new MembershipFeePaid($fee1->fresh(), $payment1, $this->org));

        $this->assertEquals('partial', $member->fresh()->fees_status,
            'fees_status should be partial when one of two fees is paid');
    }

    public function test_paying_all_fees_sets_fees_status_to_paid(): void
    {
        $member = $this->makeMember();
        $fee1   = $this->makeFee($member, 'pending', 50.00);
        $fee2   = $this->makeFee($member, 'pending', 50.00);

        $fee1->update(['status' => 'paid', 'paid_at' => now()]);
        $payment1 = $this->makePaymentForEvent($fee1->fresh());
        event(new MembershipFeePaid($fee1->fresh(), $payment1, $this->org));

        $fee2->update(['status' => 'paid', 'paid_at' => now()]);
        $payment2 = $this->makePaymentForEvent($fee2->fresh());
        event(new MembershipFeePaid($fee2->fresh(), $payment2, $this->org));

        $this->assertEquals('paid', $member->fresh()->fees_status,
            'fees_status should be paid when all fees are paid');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Waived / exempt
    // ══════════════════════════════════════════════════════════════════════════

    public function test_waiving_all_fees_sets_fees_status_to_exempt(): void
    {
        $member = $this->makeMember();
        $fee    = $this->makeFee($member, 'pending');

        $fee->update(['status' => 'waived']);

        // Recalculation via direct event (waive doesn't fire MembershipFeePaid)
        // We dispatch manually to test the listener logic
        $payment = $this->makePaymentForEvent($fee->fresh());
        event(new MembershipFeePaid($fee->fresh()->load('member'), $payment, $this->org));

        $this->assertEquals('exempt', $member->fresh()->fees_status,
            'fees_status should be exempt when all fees are waived');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Tenant isolation
    // ══════════════════════════════════════════════════════════════════════════

    public function test_recalculation_does_not_affect_other_members(): void
    {
        $member1 = $this->makeMember();
        $member2 = $this->makeMember();

        $fee1 = $this->makeFee($member1, 'pending');
        $this->makeFee($member2, 'pending'); // member2 still unpaid

        $fee1->update(['status' => 'paid', 'paid_at' => now()]);
        $payment1 = $this->makePaymentForEvent($fee1->fresh());
        event(new MembershipFeePaid($fee1->fresh(), $payment1, $this->org));

        // member1 should be paid, member2 must remain unpaid
        $this->assertEquals('paid',   $member1->fresh()->fees_status);
        $this->assertEquals('unpaid', $member2->fresh()->fees_status,
            'Recalculation must not affect other members');
    }
}
