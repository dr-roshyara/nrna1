<?php

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * TDD: OrganisationRoleController::addMember() - Path B (Direct Admin Add)
 *
 * Rule: When an admin clicks "Add as Member" on the Roles page, a member is created
 * with fees_status='exempt' (Path B), granting immediate voter eligibility.
 * This is different from Path A (Application → Approve), which creates fees_status='unpaid'.
 *
 * The controller uses private authorizeAdmin() method, requiring 'owner' or 'admin' role.
 */
class OrganisationRoleControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User        $admin;
    private User        $nonAdmin;
    private User        $targetUser;

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

        // Target user to be added as member (not yet in this org)
        $this->targetUser = User::factory()->create(['email_verified_at' => now()]);
    }

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  AUTHORIZATION TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_non_admin_cannot_add_member(): void
    {
        $response = $this->actingAs($this->nonAdmin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        $response->assertStatus(403);

        // Member should not be created
        $this->assertDatabaseMissing('members', [
            'organisation_id' => $this->org->id,
        ]);
    }

    public function test_admin_can_add_user_as_formal_member(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        $response->assertRedirect();

        // Member should be created
        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
        ]);
    }

    public function test_add_member_returns_success_redirect(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  FEE STATUS TESTS (Path B = EXEMPT = IMMEDIATE VOTER ELIGIBILITY)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_added_member_has_fees_status_exempt(): void
    {
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        // Path B: fees_status='exempt' (immediate voter eligibility)
        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
            'fees_status'     => 'exempt',
        ]);
    }

    public function test_added_member_is_immediately_voter_eligible(): void
    {
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        // Member with fees_status='exempt' should pass voter eligibility check
        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->first();
        $this->assertNotNull($member);
        $this->assertTrue(in_array($member->fees_status, ['paid', 'exempt']));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  ORGANISATION USER CREATION TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_add_member_creates_organisation_user_if_not_exists(): void
    {
        // targetUser is not yet in this org
        $this->assertDatabaseMissing('organisation_users', [
            'organisation_id' => $this->org->id,
            'user_id'         => $this->targetUser->id,
        ]);

        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        // OrganisationUser should be created
        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->org->id,
            'user_id'         => $this->targetUser->id,
            'status'          => 'active',
        ]);
    }

    public function test_add_member_reuses_existing_organisation_user(): void
    {
        // Pre-create an OrganisationUser for targetUser
        $orgUser = OrganisationUser::factory()
            ->for($this->org)
            ->for($this->targetUser)
            ->create(['status' => 'active']);

        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        // Should reuse the existing OrganisationUser
        $member = Member::where('organisation_id', $this->org->id)->first();
        $this->assertNotNull($member);
        $this->assertEquals($orgUser->id, $member->organisation_user_id);

        // Exactly one OrganisationUser should exist for this user in this org
        $this->assertDatabaseCount('organisation_users', 1);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  DUPLICATE PREVENTION TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_cannot_add_same_user_as_formal_member_twice(): void
    {
        // Add user first time
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        // Try to add same user again
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $this->targetUser->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Should still have exactly one Member record
        $memberCount = Member::where('organisation_id', $this->org->id)
            ->where('organisation_user_id', OrganisationUser::where('user_id', $this->targetUser->id)->value('id'))
            ->count();
        $this->assertEquals(1, $memberCount);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  VALIDATION TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_add_member_validates_user_id_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", []);

        $response->assertSessionHasErrors('user_id');
    }

    public function test_add_member_validates_user_exists(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => (string) Str::uuid(), // Non-existent user ID
            ]);

        $response->assertSessionHasErrors('user_id');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  MEMBERSHIP NUMBER TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_added_member_gets_unique_membership_number(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", [
                'user_id' => $user->id,
            ]);

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNotNull($member);
        $this->assertNotNull($member->membership_number);
        $this->assertStringStartsWith('M', $member->membership_number);
    }

    public function test_each_added_member_gets_unique_membership_number(): void
    {
        $user1 = User::factory()->create(['email_verified_at' => now()]);
        $user2 = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", ['user_id' => $user1->id]);

        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post("/organisations/{$this->org->slug}/membership/roles/add-member", ['user_id' => $user2->id]);

        $members = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(2, $members);

        $numbers = $members->pluck('membership_number')->toArray();
        $this->assertCount(2, array_unique($numbers), 'Two members must have unique membership numbers');
    }
}
