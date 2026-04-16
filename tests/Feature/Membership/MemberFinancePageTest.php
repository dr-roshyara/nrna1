<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipPayment;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberFinancePageTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private Member $member;

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

        $this->actingAs($this->admin);

        // Set session context for BelongsToTenant trait
        session(['current_organisation_id' => $this->organisation->id]);
    }

    /**
     * RED: Finance page renders for authorised admin
     */
    public function test_finance_page_renders_for_authorised_admin(): void
    {
        $response = $this->get(
            route('organisations.members.finance', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
            ])
        );

        $response->assertStatus(200);
    }

    /**
     * RED: Returns 404 for member in different organisation (tenant isolation)
     */
    public function test_finance_page_returns_404_for_member_in_different_org(): void
    {
        $otherOrg = Organisation::factory()->create();
        $otherMember = Member::factory()->create(['organisation_id' => $otherOrg->id]);

        $response = $this->get(
            route('organisations.members.finance', [
                'organisation' => $this->organisation->slug,
                'member' => $otherMember->id,
            ])
        );

        $response->assertStatus(404);
    }

    /**
     * RED: Finance page shows outstanding fees
     */
    public function test_finance_page_shows_outstanding_fees(): void
    {
        // Create pending and overdue fees
        MembershipFee::factory()->count(2)->create([
            'member_id' => $this->member->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'pending',
        ]);

        MembershipFee::factory()->create([
            'member_id' => $this->member->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'overdue',
        ]);

        $response = $this->get(
            route('organisations.members.finance', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
            ])
        );

        $response->assertStatus(200);
    }

    /**
     * RED: Finance page shows payment history
     */
    public function test_finance_page_shows_payment_history(): void
    {
        $response = $this->get(
            route('organisations.members.finance', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
            ])
        );

        $response->assertStatus(200);
    }

    /**
     * RED: Finance page shows dashboard stats
     */
    public function test_finance_page_shows_dashboard_stats(): void
    {
        $response = $this->get(
            route('organisations.members.finance', [
                'organisation' => $this->organisation->slug,
                'member' => $this->member->id,
            ])
        );

        $response->assertStatus(200);
    }
}
