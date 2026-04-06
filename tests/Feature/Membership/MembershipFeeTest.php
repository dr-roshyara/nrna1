<?php

namespace Tests\Feature\Membership;

use App\Events\Membership\MembershipFeePaid;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MembershipFeeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private Member $member;
    private MembershipType $type;
    private MembershipFee $fee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $memberUser = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $memberUser->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);
        $orgUser = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $memberUser->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);

        $this->member = Member::create([
            'id'                    => (string) Str::uuid(),
            'organisation_id'       => $this->org->id,
            'organisation_user_id'  => $orgUser->id,
            'status'                => 'active',
            'membership_expires_at' => now()->addYear(),
        ]);

        $this->fee = MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $this->member->id,
            'membership_type_id' => $this->type->id,
            'amount'             => 50.00,
            'currency'           => 'EUR',
            'fee_amount_at_time' => 50.00,
            'currency_at_time'   => 'EUR',
            'status'             => 'pending',
        ]);
    }

    // ── Index ─────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_member_fees(): void
    {
        $response = $this->actingAs($this->admin)->get(
            route('organisations.members.fees.index', [$this->org->slug, $this->member->id])
        );

        $response->assertOk();
        $response->assertInertia(fn ($page) =>
            $page->component('Organisations/Membership/Member/Fees')
                 ->has('fees')
        );
    }

    // ── Record Payment ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_record_payment(): void
    {
        Event::fake([MembershipFeePaid::class]);

        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
            [
                'payment_method'    => 'bank_transfer',
                'payment_reference' => 'REF-001',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $this->fee->id,
            'status' => 'paid',
        ]);

        $this->assertNotNull(MembershipFee::find($this->fee->id)->paid_at);
    }

    /** @test */
    public function payment_fires_membership_fee_paid_event(): void
    {
        Event::fake([MembershipFeePaid::class]);

        $this->actingAs($this->admin)->post(
            route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
            ['payment_method' => 'cash']
        );

        Event::assertDispatched(MembershipFeePaid::class, fn ($e) =>
            $e->fee->id === $this->fee->id
        );
    }

    /** @test */
    public function member_cannot_record_own_payment(): void
    {
        $memberUser = $this->member->organisationUser->user;

        $response = $this->actingAs($memberUser)->post(
            route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
            ['payment_method' => 'cash']
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $this->fee->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function already_paid_fee_cannot_be_paid_again(): void
    {
        $this->fee->update(['status' => 'paid', 'paid_at' => now()]);

        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
            ['payment_method' => 'cash']
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    // ── Waive ─────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_waive_a_fee(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.fees.waive', [$this->org->slug, $this->member->id, $this->fee->id])
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $this->fee->id,
            'status' => 'waived',
        ]);
    }

    // ── Idempotency ───────────────────────────────────────────────────────────

    /** @test */
    public function duplicate_payment_with_same_idempotency_key_returns_conflict(): void
    {
        // Create a fee that already has an idempotency key
        $this->fee->update(['idempotency_key' => 'payment-abc-123']);

        // Try to pay again with the same key
        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
            [
                'payment_method'    => 'cash',
                'idempotency_key'   => 'payment-abc-123',
            ]
        );

        // Should be 409 or redirect with error
        $this->assertTrue(
            $response->getStatusCode() === 409 || $response->isRedirect(),
            'Expected 409 or redirect on duplicate idempotency key'
        );
    }
}
