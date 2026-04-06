<?php

/**
 * TDD — Phase 1: Member approval must set membership_type_id and membership_number
 *
 * Bug confirmed: MembershipApplicationController::approve() creates Member
 * WITHOUT membership_type_id (line ~188). The $type variable is in scope but
 * not passed. This causes getVotingRightsAttribute() to always return 'none'
 * because membershipType() relation is always null.
 *
 * All tests in this file MUST FAIL before the fix is applied (Red).
 */

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MemberApprovalCreatesCorrectMemberTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $applicant;
    private MembershipType $fullType;
    private MembershipType $associateType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->applicant = User::factory()->create();

        $this->fullType = MembershipType::create([
            'id'                  => (string) Str::uuid(),
            'organisation_id'     => $this->org->id,
            'name'                => 'Full Member',
            'slug'                => 'full-member',
            'grants_voting_rights' => true,
            'fee_amount'          => 100.00,
            'fee_currency'        => 'EUR',
            'duration_months'     => 12,
            'requires_approval'   => true,
            'is_active'           => true,
            'sort_order'          => 1,
        ]);

        $this->associateType = MembershipType::create([
            'id'                  => (string) Str::uuid(),
            'organisation_id'     => $this->org->id,
            'name'                => 'Associate Member',
            'slug'                => 'associate-member',
            'grants_voting_rights' => false,
            'fee_amount'          => 50.00,
            'fee_currency'        => 'EUR',
            'duration_months'     => 12,
            'requires_approval'   => true,
            'is_active'           => true,
            'sort_order'          => 2,
        ]);
    }

    private function makeApplication(MembershipType $type): MembershipApplication
    {
        return MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $type->id,
            'status'             => 'submitted',
            'submitted_at'       => now(),
            'expires_at'         => now()->addDays(30),
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — membership_type_id set on Member
    // ══════════════════════════════════════════════════════════════════════════

    public function test_approving_full_member_application_sets_membership_type_id(): void
    {
        $application = $this->makeApplication($this->fullType);

        $response = $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));

        // Ensure the approval route actually processed (not 403/404)
        $response->assertRedirect();

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNotNull($member, 'Member record was not created');
        $this->assertEquals($this->fullType->id, $member->membership_type_id,
            'membership_type_id not set on Member after approval');
    }

    public function test_approving_associate_application_sets_correct_membership_type_id(): void
    {
        $application = $this->makeApplication($this->associateType);

        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNotNull($member);
        $this->assertEquals($this->associateType->id, $member->membership_type_id);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — membership_number generated
    // ══════════════════════════════════════════════════════════════════════════

    public function test_approving_application_generates_membership_number(): void
    {
        $application = $this->makeApplication($this->fullType);

        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNotNull($member);
        $this->assertNotNull($member->membership_number,
            'membership_number is null after approval');
        $this->assertStringStartsWith('M', $member->membership_number,
            'membership_number should start with M');
    }

    public function test_each_approved_application_gets_unique_membership_number(): void
    {
        $applicant2 = User::factory()->create();
        $app1 = $this->makeApplication($this->fullType);

        $app2 = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $applicant2->id,
            'membership_type_id' => $this->fullType->id,
            'status'             => 'submitted',
            'submitted_at'       => now(),
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $app1->id]));
        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $app2->id]));

        $numbers = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->pluck('membership_number')
            ->toArray();

        $this->assertCount(2, array_unique($numbers),
            'Two approved members must have unique membership numbers');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — voting rights are correct after approval
    // ══════════════════════════════════════════════════════════════════════════

    public function test_approved_full_member_with_unpaid_fee_has_no_voting_rights(): void
    {
        $application = $this->makeApplication($this->fullType);

        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->with('membershipType')
            ->first();

        $this->assertNotNull($member);
        // Fee is created as 'pending' → fees_status stays 'unpaid' → no rights
        $this->assertEquals('none', $member->voting_rights,
            'Unpaid Full Member should have voting_rights = none');
    }

    public function test_approved_member_has_correct_membership_type_relationship(): void
    {
        $application = $this->makeApplication($this->fullType);

        $this->actingAs($this->admin)
            ->patch(route('organisations.membership.applications.approve', [$this->org->slug, $application->id]));

        $member = Member::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->with('membershipType')
            ->first();

        $this->assertNotNull($member->membershipType,
            'membershipType() relation should not be null after approval');
        $this->assertTrue($member->membershipType->grants_voting_rights,
            'Full member type should grant voting rights');
    }
}
