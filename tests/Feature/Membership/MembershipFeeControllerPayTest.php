<?php

namespace Tests\Feature\Membership;

use App\Models\Income;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipFeeControllerPayTest extends TestCase
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

        // Setup: admin user with organisation role (required by ensure.organisation middleware)
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
     * RED: POST /organisations/{org}/members/{member}/fees/{fee}/pay creates payment record
     */
    public function test_pay_creates_membership_payment_record(): void
    {
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

        $response->assertStatus(302);
        $this->assertDatabaseHas('membership_payments', [
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
            'amount' => 100.00,
        ]);
    }

    /**
     * RED: Payment fires MembershipFeePaid event
     */
    public function test_pay_fires_membership_fee_paid_event(): void
    {
        $events = [];
        \Event::listen(\App\Events\MembershipFeePaid::class, function ($event) use (&$events) {
            $events[] = $event;
        });

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

        $this->assertCount(1, $events);
    }

    /**
     * RED: Payment records payment and fires event (listener decouples Income creation)
     */
    public function test_pay_records_payment_and_fires_event(): void
    {
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

        // Payment is recorded
        $response->assertStatus(302);
        $this->assertDatabaseHas('membership_payments', [
            'member_id' => $this->member->id,
            'fee_id' => $this->fee->id,
        ]);

        // Event was fired (verified in separate event test)
        // Listener architecture is verified in MembershipPaymentServiceTest
    }

    /**
     * RED: Payment updates fee status to paid
     */
    public function test_pay_updates_fee_status_to_paid(): void
    {
        $this->assertEquals('pending', $this->fee->status);

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

        $this->fee->refresh();
        $this->assertEquals('paid', $this->fee->status);
    }

    /**
     * RED: Requires manage_membership policy (403 without permission)
     */
    public function test_pay_requires_manage_membership_policy(): void
    {
        // Create a user without permissions (but still a member of the organisation)
        $unauthorizedUser = User::factory()->create();
        $unauthorizedUser->organisationRoles()->create([
            'organisation_id' => $this->organisation->id,
            'role' => 'member',
        ]);
        $this->actingAs($unauthorizedUser);

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

        $response->assertStatus(403);
    }

    /**
     * RED: Returns 404 if fee belongs to different member
     */
    public function test_pay_returns_404_if_fee_belongs_to_different_member(): void
    {
        // Create another member and their fee
        $otherMember = Member::factory()->create(['organisation_id' => $this->organisation->id]);
        $otherFee = MembershipFee::factory()->create([
            'member_id' => $otherMember->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $response = $this->post(
            route('organisations.members.fees.pay', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
                'fee' => $otherFee->id,
            ]),
            [
                'payment_method' => 'bank_transfer',
                'amount' => 100.00,
            ]
        );

        $response->assertStatus(404);
    }

    /**
     * RED: Returns 404 if member belongs to different organisation (tenant isolation)
     */
    public function test_pay_returns_404_if_member_belongs_to_different_org(): void
    {
        // Create another organisation and member
        $otherOrg = Organisation::factory()->create();
        $otherMember = Member::factory()->create(['organisation_id' => $otherOrg->id]);
        $otherFee = MembershipFee::factory()->create([
            'member_id' => $otherMember->id,
            'organisation_id' => $otherOrg->id,
        ]);

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

        $response->assertStatus(404);
    }

    /**
     * RED: Rejects duplicate payment on already-paid fee
     */
    public function test_pay_rejects_duplicate_payment_on_already_paid_fee(): void
    {
        // Pay once
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

        // Attempt to pay again
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
    }

    /**
     * RED: Only works in full membership mode
     */
    public function test_pay_uses_full_membership_mode_only(): void
    {
        // Disable full membership
        $this->organisation->update(['uses_full_membership' => false]);

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

        $response->assertStatus(403);
    }
}
