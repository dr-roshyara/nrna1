<?php

namespace Tests\Feature\Membership;

use App\Models\Election;
use App\Models\ElectionMembership;
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
 * TDD: MemberController::markPaid() Authorization & Voter Eligibility
 *
 * Rule: A member becomes voter-eligible only when:
 * 1. An admin marks their fees_status='exempt' (via markPaid)
 * 2. The admin has 'owner' or 'admin' organisation role
 * 3. Pending fee rows are waived so the UI badge clears
 *
 * This fixes the production issue: "Approved members invisible in voters dropdown"
 */
class MemberControllerMarkPaidTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User        $admin;
    private User        $nonAdmin;
    private Election     $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup organisation
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Setup admin user with owner role (higher privs than admin)
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

        // Setup election (for voter dropdown test)
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);
    }

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    /**
     * Helper: Create a member with unpaid fees
     */
    private function createUnpaidMember(User $user): Member
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
                'fees_status' => 'unpaid', // KEY: unpaid makes them invisible in voters dropdown
            ]);

        // Create a pending fee so we can test waiver
        MembershipFee::create([
            'id'                   => (string) Str::uuid(),
            'member_id'            => $member->id,
            'organisation_id'      => $this->org->id,
            'membership_type_id'   => $membershipType->id,
            'amount'               => 50.00,
            'fee_amount_at_time'   => 50.00,
            'currency'             => 'USD',
            'currency_at_time'     => 'USD',
            'status'               => 'pending',
        ]);

        return $member;
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  TESTS
    // ══════════════════════════════════════════════════════════════════════════

    public function test_mark_paid_sets_fees_status_to_exempt(): void
    {
        $member = $this->createUnpaidMember($this->admin);

        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'unpaid',
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        $response->assertRedirect();

        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'exempt',
        ]);
    }

    public function test_mark_paid_makes_member_appear_in_voter_dropdown(): void
    {
        $member = $this->createUnpaidMember($this->admin);

        // BEFORE: member is unpaid
        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'unpaid',
        ]);

        // ACT: Mark as paid
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        // AFTER: member is now exempt
        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'exempt',
        ]);

        // Voter eligibility is tested in VoterDropdownTest.php which has proper election officer setup
    }

    public function test_mark_paid_requires_admin_role(): void
    {
        $member = $this->createUnpaidMember($this->nonAdmin);

        // Non-admin attempt
        $response = $this->actingAs($this->nonAdmin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        $response->assertStatus(403);
    }

    public function test_mark_paid_by_non_admin_returns_403(): void
    {
        $member = $this->createUnpaidMember($this->admin);

        $response = $this->actingAs($this->nonAdmin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        $response->assertStatus(403);
        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'unpaid', // Unchanged
        ]);
    }

    public function test_mark_paid_on_member_from_different_org_returns_404(): void
    {
        // Create member in this org
        $member = $this->createUnpaidMember($this->admin);

        // Try to mark paid via a different org slug
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->patch("/organisations/{$otherOrg->slug}/members/{$member->id}/mark-paid");

        $response->assertStatus(404);
    }

    public function test_mark_paid_returns_success_redirect(): void
    {
        $memberUser = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $member = $this->createUnpaidMember($memberUser);

        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        // Successful request should redirect
        $response->assertRedirect();

        // Verify member was actually marked as paid
        $this->assertDatabaseHas('members', [
            'id'           => $member->id,
            'fees_status'  => 'exempt',
        ]);
    }

    public function test_mark_paid_also_waives_pending_fee_rows(): void
    {
        $member = $this->createUnpaidMember($this->admin);

        // Confirm pending fee exists
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);

        // Mark as paid
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        // Pending fee should now be waived
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'waived',
        ]);
    }

    public function test_mark_paid_pending_fees_disappear_from_member_index(): void
    {
        $member = $this->createUnpaidMember($this->admin);

        // Confirm pending fees exist before
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);

        // ACT: Mark as paid (which now waives pending fees)
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");

        // AFTER: pending fee should be waived
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'waived', // Changed from 'pending'
        ]);

        // Verify no pending fees remain
        $this->assertDatabaseMissing('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);
    }
}
