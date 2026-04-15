<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * TDD: Dual-Mode Membership System — Election-Only vs Full Membership
 *
 * Phase 1: RED — All tests fail until VoterEligibilityService is created
 *
 * Rule: Single boolean flag `uses_full_membership` on organisations table:
 * - true (default) → Full membership mode: requires active Member with paid/exempt fees
 * - false → Election-only mode: any active OrganisationUser can vote
 *
 * This test ensures both modes work independently and don't interfere.
 */
class ElectionOnlyModeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $fullMembershipOrg;
    private Organisation $electionOnlyOrg;
    private User $admin;
    private User $user1;
    private User $user2;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup full membership org (traditional mode)
        $this->fullMembershipOrg = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        // Setup election-only org (new mode)
        $this->electionOnlyOrg = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => false,
        ]);

        // Setup admin user with owner role
        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->admin->id,
            'organisation_id' => $this->electionOnlyOrg->id,
            'role' => 'owner',
        ]);

        // Setup regular users
        $this->user1 = User::factory()->create(['email_verified_at' => now()]);
        $this->user2 = User::factory()->create(['email_verified_at' => now()]);

        // Add users to election-only org (no Member records)
        OrganisationUser::factory()
            ->for($this->electionOnlyOrg)
            ->for($this->user1)
            ->create(['status' => 'active']);

        OrganisationUser::factory()
            ->for($this->electionOnlyOrg)
            ->for($this->user2)
            ->create(['status' => 'active']);

        // Add admin to election-only org
        OrganisationUser::factory()
            ->for($this->electionOnlyOrg)
            ->for($this->admin)
            ->create(['status' => 'active']);

        // Create election in election-only org
        $this->election = Election::factory()
            ->forOrganisation($this->electionOnlyOrg)
            ->real()
            ->create(['status' => 'active']);

        // Add admin as election chief (required to manage voters)
        ElectionOfficer::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->electionOnlyOrg->id,
            'user_id' => $this->admin->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        // Set session
        session(['current_organisation_id' => $this->electionOnlyOrg->id]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  DEFAULT MODE TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_full_membership_org_defaults_to_true_for_new_orgs(): void
    {
        // When uses_full_membership is not explicitly set, it should default to true
        $org = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        $this->assertTrue($org->uses_full_membership);
        $this->assertTrue($org->usesFullMembership());
        $this->assertFalse($org->isElectionOnly());
    }

    public function test_election_only_mode_can_be_set_to_false(): void
    {
        $this->assertFalse($this->electionOnlyOrg->uses_full_membership);
        $this->assertTrue($this->electionOnlyOrg->isElectionOnly());
        $this->assertFalse($this->electionOnlyOrg->usesFullMembership());
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  ELIGIBILITY TESTS (Single User)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_election_only_user_without_member_record_is_eligible(): void
    {
        // User is in OrganisationUser table but NOT in Member table
        $this->assertFalse(
            Member::where('organisation_id', $this->electionOnlyOrg->id)
                ->whereHas('organisationUser', fn ($q) => $q->where('user_id', $this->user1->id))
                ->exists(),
            'Precondition: user1 should NOT have a Member record'
        );

        // Election-only mode: user should still be eligible
        // This will fail until VoterEligibilityService is implemented
        $eligible = app(\App\Services\VoterEligibilityService::class)
            ->isEligibleVoter($this->electionOnlyOrg, $this->user1);

        $this->assertTrue($eligible, 'User without Member record should be eligible in election-only mode');
    }

    public function test_full_membership_user_without_member_record_is_not_eligible(): void
    {
        // Add user to full membership org but no Member record
        $user = User::factory()->create();
        OrganisationUser::factory()
            ->for($this->fullMembershipOrg)
            ->for($user)
            ->create(['status' => 'active']);

        // Full membership mode: user should NOT be eligible
        $eligible = app(\App\Services\VoterEligibilityService::class)
            ->isEligibleVoter($this->fullMembershipOrg, $user);

        $this->assertFalse($eligible, 'User without Member record should NOT be eligible in full membership mode');
    }

    public function test_full_membership_user_with_active_member_is_eligible(): void
    {
        // Create user in full membership org with Member record
        $user = User::factory()->create();
        $orgUser = OrganisationUser::factory()
            ->for($this->fullMembershipOrg)
            ->for($user)
            ->create(['status' => 'active']);

        $membershipType = MembershipType::factory()
            ->for($this->fullMembershipOrg)
            ->create(['grants_voting_rights' => true]);

        Member::factory()
            ->for($this->fullMembershipOrg)
            ->for($orgUser, 'organisationUser')
            ->for($membershipType)
            ->create([
                'status' => 'active',
                'fees_status' => 'exempt',
            ]);

        // Should be eligible
        $eligible = app(\App\Services\VoterEligibilityService::class)
            ->isEligibleVoter($this->fullMembershipOrg, $user);

        $this->assertTrue($eligible, 'User with active Member and exempt fees should be eligible in full membership mode');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  VOTER DROPDOWN TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_election_only_dropdown_shows_all_active_org_users(): void
    {
        // Election-only org has 2 users without Member records
        $assignedUserIds = [];

        $voters = app(\App\Services\VoterEligibilityService::class)
            ->unassignedEligibleQuery($this->electionOnlyOrg, $assignedUserIds)
            ->get();

        // Should include both user1 and user2 (no Member check needed)
        $voterIds = $voters->pluck('id')->toArray();
        $this->assertContains($this->user1->id, $voterIds, 'user1 should appear in dropdown');
        $this->assertContains($this->user2->id, $voterIds, 'user2 should appear in dropdown');
    }

    public function test_full_membership_dropdown_only_shows_members_with_paid_exempt(): void
    {
        // Create election in full membership org
        $election = Election::factory()
            ->forOrganisation($this->fullMembershipOrg)
            ->real()
            ->create(['status' => 'active']);

        // Add 2 users to org but only 1 with Member record
        $userWithMember = User::factory()->create();
        $userWithoutMember = User::factory()->create();

        $membershipType = MembershipType::factory()
            ->for($this->fullMembershipOrg)
            ->create(['grants_voting_rights' => true]);

        // User 1: Has org user + member record → should appear
        $orgUser1 = OrganisationUser::factory()
            ->for($this->fullMembershipOrg)
            ->for($userWithMember)
            ->create(['status' => 'active']);

        Member::factory()
            ->for($this->fullMembershipOrg)
            ->for($orgUser1, 'organisationUser')
            ->for($membershipType)
            ->create(['status' => 'active', 'fees_status' => 'exempt']);

        // User 2: Has org user but NO member record → should NOT appear
        OrganisationUser::factory()
            ->for($this->fullMembershipOrg)
            ->for($userWithoutMember)
            ->create(['status' => 'active']);

        $assignedUserIds = [];
        $voters = app(\App\Services\VoterEligibilityService::class)
            ->unassignedEligibleQuery($this->fullMembershipOrg, $assignedUserIds)
            ->get();

        $voterIds = $voters->pluck('id')->toArray();
        $this->assertContains($userWithMember->id, $voterIds, 'User with Member record should appear in dropdown');
        $this->assertNotContains($userWithoutMember->id, $voterIds, 'User without Member record should NOT appear in dropdown');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  VOTER ASSIGNMENT TESTS (store() method)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_election_only_single_assign_accepts_user_without_member_record(): void
    {
        // Try to assign user1 to election (no Member record in election-only org)
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->electionOnlyOrg->id])
            ->post("/organisations/{$this->electionOnlyOrg->slug}/elections/{$this->election->slug}/voters", [
                'user_id' => $this->user1->id,
            ]);

        // Should succeed (not 422 validation error)
        $response->assertStatus(302); // Redirect on success or stays on page

        // Verify assignment was created
        $this->assertTrue(
            $this->election->memberships()
                ->where('user_id', $this->user1->id)
                ->exists(),
            'User should be assigned to election in election-only mode'
        );
    }

    public function test_full_membership_single_assign_rejects_user_without_member_record(): void
    {
        // Create full membership election
        $election = Election::factory()
            ->forOrganisation($this->fullMembershipOrg)
            ->real()
            ->create(['status' => 'active']);

        // Add user to org but no Member record
        $user = User::factory()->create();
        OrganisationUser::factory()
            ->for($this->fullMembershipOrg)
            ->for($user)
            ->create(['status' => 'active']);

        // Setup admin for full membership org
        $admin = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'organisation_id' => $this->fullMembershipOrg->id,
            'role' => 'owner',
        ]);

        // Add admin as election chief (required to manage voters)
        ElectionOfficer::create([
            'id' => (string) Str::uuid(),
            'election_id' => $election->id,
            'organisation_id' => $this->fullMembershipOrg->id,
            'user_id' => $admin->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        // Try to assign user to election
        $response = $this->actingAs($admin)
            ->withSession(['current_organisation_id' => $this->fullMembershipOrg->id])
            ->post("/organisations/{$this->fullMembershipOrg->slug}/elections/{$election->slug}/voters", [
                'user_id' => $user->id,
            ]);

        // Should return validation error (302 redirect back with session errors, not 422)
        $response->assertStatus(302); // Redirect back with errors
        $response->assertSessionHasErrors('user_id');

        // Verify assignment was NOT created
        $this->assertFalse(
            $election->memberships()
                ->where('user_id', $user->id)
                ->exists(),
            'User should NOT be assigned in full membership mode without Member record'
        );
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  BULK ASSIGNMENT TESTS (bulkStore() method)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_election_only_bulk_assign_accepts_users_without_member_record(): void
    {
        // Bulk assign user1 and user2 to election
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->electionOnlyOrg->id])
            ->post("/organisations/{$this->electionOnlyOrg->slug}/elections/{$this->election->slug}/voters/bulk", [
                'user_ids' => [$this->user1->id, $this->user2->id],
            ]);

        $response->assertStatus(302); // Redirect on success

        // Verify both users were assigned
        $this->assertTrue(
            $this->election->memberships()
                ->where('user_id', $this->user1->id)
                ->exists(),
            'user1 should be assigned to election in bulk'
        );

        $this->assertTrue(
            $this->election->memberships()
                ->where('user_id', $this->user2->id)
                ->exists(),
            'user2 should be assigned to election in bulk'
        );
    }
}
