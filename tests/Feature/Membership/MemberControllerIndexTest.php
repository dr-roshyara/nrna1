<?php

namespace Tests\Feature\Membership;

use App\Models\Election;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * TDD: MemberController::index() Authorization & Data Display
 *
 * Rule: The members list page requires 'owner' or 'admin' organisation role.
 * The page displays all formal members with pending fee amounts.
 *
 * This ensures only authorized staff can view the member directory.
 */
class MemberControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User        $admin;
    private User        $nonAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup organisation
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Setup admin user with owner role
        $this->admin = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'owner',
        ]);

        // Setup non-admin user with voter role
        $this->nonAdmin = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->nonAdmin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    /**
     * Helper: Create an active member with optional pending fees
     */
    private function createActiveMember(User $user, float $pendingFees = 0.0): Member
    {
        $orgUser = OrganisationUser::factory()
            ->for($this->org)
            ->for($user)
            ->create(['status' => 'active']);

        UserOrganisationRole::firstOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $this->org->id],
            ['id' => (string) Str::uuid(), 'role' => 'voter']
        );

        $membershipType = MembershipType::factory()
            ->for($this->org)
            ->create(['grants_voting_rights' => true]);

        $member = Member::factory()
            ->for($this->org)
            ->for($orgUser, 'organisationUser')
            ->for($membershipType)
            ->create([
                'status'      => 'active',
                'fees_status' => 'exempt',
            ]);

        // Create pending fees if specified
        if ($pendingFees > 0.0) {
            MembershipFee::create([
                'id'                   => (string) Str::uuid(),
                'member_id'            => $member->id,
                'organisation_id'      => $this->org->id,
                'membership_type_id'   => $membershipType->id,
                'amount'               => $pendingFees,
                'fee_amount_at_time'   => $pendingFees,
                'currency'             => 'USD',
                'currency_at_time'     => 'USD',
                'status'               => 'pending',
            ]);
        }

        return $member;
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  AUTHORIZATION TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_index_requires_admin_or_owner_role(): void
    {
        $response = $this->actingAs($this->nonAdmin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(403);
    }

    public function test_index_by_voter_returns_403(): void
    {
        $response = $this->actingAs($this->nonAdmin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(403);
    }

    public function test_index_by_admin_returns_200(): void
    {
        $this->createActiveMember($this->admin);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(200);
    }

    public function test_index_org_isolation_non_member_of_other_org(): void
    {
        // Create member in this org
        $member = $this->createActiveMember($this->admin);

        // Try to access via a different org slug where admin is not a member
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->get("/organisations/{$otherOrg->slug}/members");

        // Should be redirected (403) because admin user is not admin of otherOrg
        $response->assertStatus(302);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  DATA DISPLAY TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_index_returns_correct_data_shape(): void
    {
        $user = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $member = $this->createActiveMember($user);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(200);

        // Check that response contains members data with required fields
        $response->assertInertia(fn ($page) =>
            $page->has('members.data', 1)
                ->has('members.data.0.id')
                ->has('members.data.0.name')
                ->has('members.data.0.email')
                ->has('members.data.0.status')
                ->has('members.data.0.pending_fees')
                ->has('members.data.0.joined_at')
        );
    }

    public function test_index_shows_pending_fees_amount(): void
    {
        $user1 = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $member1 = $this->createActiveMember($user1, 50.00);

        $user2 = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $member2 = $this->createActiveMember($user2, 75.50);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(200);

        // Verify pending fees are shown for each member (cast to float for comparison)
        $response->assertInertia(fn ($page) =>
            $page->has('members.data', 2)
                ->where('members.data.0.pending_fees', 50)
                ->where('members.data.1.pending_fees', 75.5)
        );
    }

    public function test_index_filters_by_name(): void
    {
        $user1 = User::factory()->create([
            'name'                => 'John Smith',
            'organisation_id'     => $this->org->id,
            'email_verified_at'   => now(),
        ]);
        $member1 = $this->createActiveMember($user1);

        $user2 = User::factory()->create([
            'name'                => 'Jane Doe',
            'organisation_id'     => $this->org->id,
            'email_verified_at'   => now(),
        ]);
        $member2 = $this->createActiveMember($user2);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members?name=John");

        $response->assertStatus(200);

        // Should only show John Smith
        $response->assertInertia(fn ($page) =>
            $page->has('members.data', 1)
                ->where('members.data.0.id', $member1->id)
                ->where('members.data.0.name', 'John Smith')
        );
    }

    public function test_index_filters_by_status(): void
    {
        $activeUser = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $activeMember = $this->createActiveMember($activeUser);

        // Create an expired member
        $expiredUser = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $expiredOrgUser = OrganisationUser::factory()
            ->for($this->org)
            ->for($expiredUser)
            ->create(['status' => 'active']);

        UserOrganisationRole::firstOrCreate(
            ['user_id' => $expiredUser->id, 'organisation_id' => $this->org->id],
            ['id' => (string) Str::uuid(), 'role' => 'voter']
        );

        $membershipType = MembershipType::factory()
            ->for($this->org)
            ->create(['grants_voting_rights' => true]);

        $expiredMember = Member::factory()
            ->for($this->org)
            ->for($expiredOrgUser, 'organisationUser')
            ->for($membershipType)
            ->create(['status' => 'expired', 'fees_status' => 'exempt']);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members?status=expired");

        $response->assertStatus(200);

        // Should only show expired member
        $response->assertInertia(fn ($page) =>
            $page->has('members.data', 1)
                ->where('members.data.0.id', $expiredMember->id)
                ->where('members.data.0.status', 'expired')
        );
    }

    public function test_index_shows_stats_with_totals(): void
    {
        $user1 = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $this->createActiveMember($user1);

        $user2 = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $this->createActiveMember($user2, 100.00);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->get("/organisations/{$this->org->slug}/members");

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) =>
            $page->has('stats')
                ->where('stats.total_members', 2)
                ->where('stats.pending_fees', 100)
        );
    }
}
