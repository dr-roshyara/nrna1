<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipFeeCreateTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private Member $member;
    private MembershipType $membershipType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['uses_full_membership' => true]);

        $this->admin = User::factory()->create();
        $this->admin->organisationRoles()->create([
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id]);

        $this->membershipType = MembershipType::factory()->create([
            'organisation_id' => $this->organisation->id,
            'fee_amount'      => 150.00,
            'fee_currency'    => 'EUR',
            'is_active'       => true,
        ]);

        $this->actingAs($this->admin);
        session(['current_organisation_id' => $this->organisation->id]);
    }

    public function test_create_page_renders_for_authorised_admin(): void
    {
        $response = $this->get(
            route('organisations.members.fees.create', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ])
        );

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Organisations/Membership/Member/FeeCreate')
            ->has('membershipTypes')
            ->has('member')
            ->has('organisation')
        );
    }

    public function test_create_page_returns_403_for_non_admin(): void
    {
        $nonAdmin = User::factory()->create();
        $nonAdmin->organisationRoles()->create([
            'organisation_id' => $this->organisation->id,
            'role'            => 'member',
        ]);
        $this->actingAs($nonAdmin);

        $response = $this->get(
            route('organisations.members.fees.create', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ])
        );

        $response->assertStatus(403);
    }

    public function test_create_page_returns_404_for_member_in_different_org(): void
    {
        $otherOrg    = Organisation::factory()->create();
        $otherMember = Member::factory()->create(['organisation_id' => $otherOrg->id]);

        $response = $this->get(
            route('organisations.members.fees.create', [
                'organisation' => $this->organisation->slug,
                'member'       => $otherMember->id,
            ])
        );

        $response->assertStatus(404);
    }

    public function test_store_creates_membership_fee_for_member(): void
    {
        $this->assertDatabaseCount('membership_fees', 0);

        $response = $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ]),
            [
                'membership_type_id' => $this->membershipType->id,
                'due_date'           => now()->addMonth()->toDateString(),
                'period_label'       => '2026',
                'notes'              => 'Annual membership fee',
            ]
        );

        $response->assertStatus(302);
        $this->assertDatabaseHas('membership_fees', [
            'member_id'          => $this->member->id,
            'organisation_id'    => $this->organisation->id,
            'membership_type_id' => $this->membershipType->id,
        ]);
    }

    public function test_store_snapshots_fee_amount_at_time_from_type(): void
    {
        $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ]),
            [
                'membership_type_id' => $this->membershipType->id,
                'due_date'           => now()->addMonth()->toDateString(),
            ]
        );

        $this->assertDatabaseHas('membership_fees', [
            'member_id'          => $this->member->id,
            'amount'             => 150.00,
            'fee_amount_at_time' => 150.00,
            'currency'           => 'EUR',
            'currency_at_time'   => 'EUR',
        ]);
    }

    public function test_store_sets_status_to_pending(): void
    {
        $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ]),
            [
                'membership_type_id' => $this->membershipType->id,
                'due_date'           => now()->addMonth()->toDateString(),
            ]
        );

        $fee = MembershipFee::first();
        $this->assertEquals('pending', $fee->status);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ]),
            []
        );

        $response->assertSessionHasErrors(['membership_type_id', 'due_date']);
    }

    public function test_store_returns_404_if_member_belongs_to_different_org(): void
    {
        $otherOrg    = Organisation::factory()->create();
        $otherMember = Member::factory()->create(['organisation_id' => $otherOrg->id]);

        $response = $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $otherMember->id,
            ]),
            [
                'membership_type_id' => $this->membershipType->id,
                'due_date'           => now()->addMonth()->toDateString(),
            ]
        );

        $response->assertStatus(404);
    }

    public function test_store_redirects_to_fees_index_on_success(): void
    {
        $response = $this->post(
            route('organisations.members.fees.store', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ]),
            [
                'membership_type_id' => $this->membershipType->id,
                'due_date'           => now()->addMonth()->toDateString(),
            ]
        );

        $response->assertRedirect(
            route('organisations.members.fees.index', [
                'organisation' => $this->organisation->slug,
                'member'       => $this->member->id,
            ])
        );
        $response->assertSessionHas('success');
    }
}
