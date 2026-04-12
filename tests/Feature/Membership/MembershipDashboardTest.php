<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipDashboardTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;
    private User $commission;
    private User $member;
    private User $outsider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Owner
        $this->owner = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->owner->id,
            'organisation_id' => $this->org->id,
            'role'            => 'owner',
        ]);

        // Admin
        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        // Commission
        $this->commission = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->commission->id,
            'organisation_id' => $this->org->id,
            'role'            => 'commission',
        ]);

        // Member
        $this->member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        // Outsider — no role in this org
        $this->outsider = User::factory()->create();
    }

    // ── Access control ────────────────────────────────────────────────────────

    /** @test */
    public function owner_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertStatus(200);
    }

    /** @test */
    public function commission_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->commission)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertStatus(200);
    }

    /** @test */
    public function member_can_access_own_dashboard(): void
    {
        $response = $this->actingAs($this->member)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertStatus(200);
    }

    /** @test */
    public function outsider_cannot_access_dashboard(): void
    {
        // ensure.organisation middleware redirects non-members (302 to access denied page)
        $response = $this->actingAs($this->outsider)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        // Middleware issues a redirect (302) not a 403 — accepted behaviour
        $response->assertRedirect();
    }

    /** @test */
    public function guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertRedirect(route('login'));
    }

    // ── Data isolation ────────────────────────────────────────────────────────

    /** @test */
    public function dashboard_only_shows_applications_for_this_organisation(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        // Create a membership type directly without factory
        $type = MembershipType::create([
            'organisation_id' => $this->org->id,
            'name'            => 'Standard',
            'slug'            => 'standard',
            'fee_amount'      => 50,
            'fee_currency'    => 'EUR',
            'requires_approval' => true,
            'is_active'       => true,
        ]);
        $otherType = MembershipType::create([
            'organisation_id' => $otherOrg->id,
            'name'            => 'Standard',
            'slug'            => 'standard',
            'fee_amount'      => 50,
            'fee_currency'    => 'EUR',
            'requires_approval' => true,
            'is_active'       => true,
        ]);

        MembershipApplication::create([
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->owner->id,
            'membership_type_id' => $type->id,
            'status'             => 'submitted',
            'submitted_at'       => now(),
        ]);
        MembershipApplication::create([
            'organisation_id'    => $otherOrg->id,
            'user_id'            => $this->owner->id,
            'membership_type_id' => $otherType->id,
            'status'             => 'submitted',
            'submitted_at'       => now(),
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('applications.data', 1)
        );
    }

    /** @test */
    public function owner_dashboard_contains_required_stat_keys(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertInertia(fn ($page) =>
            $page->where('role', 'owner')
                 ->has('stats.total_members')
                 ->has('stats.pending_apps')
                 ->has('stats.pending_fees_total')
                 ->has('stats.expiring_in_30')
        );
    }

    /** @test */
    public function member_dashboard_contains_member_self_data(): void
    {
        $orgUser = OrganisationUser::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'member',
            'status'          => 'active',
            'joined_at'       => now(),
        ]);
        Member::create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'status'               => 'active',
        ]);

        $response = $this->actingAs($this->member)
            ->get(route('organisations.membership.dashboard', $this->org->slug));

        $response->assertInertia(fn ($page) =>
            $page->where('role', 'member')
                 ->has('memberSelf')
                 ->where('memberSelf.has_membership', true)
        );
    }
}
