<?php

namespace Tests\Feature\Membership;

use App\Models\Election;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * TDD: Path A Complete Flow - Application → Approval → Mark Paid → Voter Eligible
 *
 * Rule: Members approved via the application flow start as unpaid, making them
 * invisible in the voters dropdown. Only after marking paid do they become
 * voter-eligible (fees_status='exempt'). This test verifies the complete flow.
 *
 * This is the production issue: "Approved members invisible in voters dropdown"
 */
class ApplicationApprovedToVoterEligibilityTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User        $admin;
    private User        $applicant;
    private MembershipType $membershipType;
    private Election     $election;

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

        // Setup applicant user
        $this->applicant = User::factory()->create(['email_verified_at' => now()]);

        // Create membership type
        $this->membershipType = MembershipType::factory()
            ->for($this->org)
            ->create(['grants_voting_rights' => true, 'requires_approval' => true]);

        // Create election for voter dropdown test
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
     * Helper: Create a membership application for the applicant
     */
    private function makeApplication(): MembershipApplication
    {
        return MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->membershipType->id,
            'status'             => 'submitted',
            'submitted_at'       => now(),
            'expires_at'         => now()->addDays(30),
        ]);
    }

    /**
     * Helper: Approve an application (calls MembershipApplicationController::approve)
     */
    private function approveApplication(MembershipApplication $application): void
    {
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));
    }

    /**
     * Helper: Mark a member as paid (calls MemberController::markPaid)
     */
    private function markMemberAsPaid(Member $member): void
    {
        $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->patch("/organisations/{$this->org->slug}/members/{$member->id}/mark-paid");
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  STAGE 1: APPLICATION APPROVAL (Path A starts with unpaid)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_application_approval_creates_member_with_unpaid_fees(): void
    {
        $application = $this->makeApplication();

        $this->approveApplication($application);

        // Member should exist with fees_status='unpaid'
        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
            'fees_status'     => 'unpaid',
        ]);
    }

    public function test_approved_member_with_unpaid_fees_cannot_be_voter(): void
    {
        $application = $this->makeApplication();

        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();
        $this->assertNotNull($member);

        // fees_status='unpaid' is NOT in voter eligibility check
        // Voter eligibility requires: fees_status IN ('paid', 'exempt')
        $this->assertFalse(in_array($member->fees_status, ['paid', 'exempt']));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  STAGE 2: MARK PAID (Path A transforms from unpaid to exempt)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_mark_paid_transforms_unpaid_to_exempt(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();
        $this->assertEquals('unpaid', $member->fees_status);

        // Mark as paid
        $this->markMemberAsPaid($member);

        // Refresh from database
        $member->refresh();
        $this->assertEquals('exempt', $member->fees_status);
    }

    public function test_marked_paid_member_becomes_voter_eligible(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();

        // Mark as paid
        $this->markMemberAsPaid($member);

        // Refresh from database
        $member->refresh();

        // Now fees_status='exempt' should pass voter eligibility check
        $this->assertTrue(in_array($member->fees_status, ['paid', 'exempt']));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  STAGE 3: VOTER DROPDOWN VISIBILITY
    // ══════════════════════════════════════════════════════════════════════════

    public function test_approved_member_unpaid_not_in_unassigned_voters_list(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();
        $this->assertNotNull($member);

        // Query from ElectionVoterController (line 57-73)
        $unassignedVoters = Member::where('organisation_id', $this->org->id)
            ->whereIn('fees_status', ['paid', 'exempt'])
            ->get();

        // Member with fees_status='unpaid' should NOT be in list
        $this->assertFalse($unassignedVoters->contains($member->id));
    }

    public function test_marked_paid_member_appears_in_unassigned_voters_list(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();

        // Mark as paid
        $this->markMemberAsPaid($member);

        // Query from ElectionVoterController
        $unassignedVoters = Member::where('organisation_id', $this->org->id)
            ->whereIn('fees_status', ['paid', 'exempt'])
            ->get();

        // Member with fees_status='exempt' should be in list
        $this->assertTrue($unassignedVoters->contains($member->id));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  STAGE 4: PENDING FEES MANAGEMENT
    // ══════════════════════════════════════════════════════════════════════════

    public function test_approved_member_has_pending_fee_row(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();

        // Fee should be created as 'pending'
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);
    }

    public function test_mark_paid_waives_pending_fee_row(): void
    {
        $application = $this->makeApplication();
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();

        // Confirm pending fee exists
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);

        // Mark as paid (should waive the pending fee)
        $this->markMemberAsPaid($member);

        // Pending fee should now be waived
        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'waived',
        ]);

        // No pending fees should remain
        $this->assertDatabaseMissing('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  END-TO-END FLOW TEST
    // ══════════════════════════════════════════════════════════════════════════

    public function test_full_path_a_flow_application_approve_mark_paid_voter_eligible(): void
    {
        // STEP 1: Create application
        $application = $this->makeApplication();
        $this->assertDatabaseHas('membership_applications', [
            'id'     => $application->id,
            'status' => 'submitted',
        ]);

        // STEP 2: Approve application
        $this->approveApplication($application);

        $member = Member::where('organisation_id', $this->org->id)->first();
        $this->assertNotNull($member);
        $this->assertEquals('unpaid', $member->fees_status);

        // At this stage, member is invisible to voters dropdown
        $votersQuery1 = Member::where('organisation_id', $this->org->id)
            ->whereIn('fees_status', ['paid', 'exempt']);
        $this->assertFalse($votersQuery1->where('id', $member->id)->exists());

        // STEP 3: Mark as paid
        $this->markMemberAsPaid($member);

        // Refresh member
        $member->refresh();
        $this->assertEquals('exempt', $member->fees_status);

        // Now member is visible to voters dropdown
        $votersQuery2 = Member::where('organisation_id', $this->org->id)
            ->whereIn('fees_status', ['paid', 'exempt']);
        $this->assertTrue($votersQuery2->where('id', $member->id)->exists());

        // Pending fee should be waived
        $this->assertDatabaseMissing('membership_fees', [
            'member_id' => $member->id,
            'status'    => 'pending',
        ]);
    }
}
