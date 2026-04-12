<?php

namespace Tests\Unit\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Policies\MembershipPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * MembershipPolicy — role-based access control for membership management.
 *
 * Role hierarchy (from UserOrganisationRole):
 *   owner      (100) — full access including type management
 *   admin       (80) — approve/reject applications, record fees, renew
 *   commission  (60) — view applications only
 *   voter       (40) — no management access
 *   member      (20) — no management access
 */
class MembershipPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org    = Organisation::factory()->create(['type' => 'tenant']);
        $this->policy = new MembershipPolicy();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
        ]);
        return $user;
    }

    // ── viewApplications ─────────────────────────────────────────────────────

    /** @test */
    public function owner_can_view_applications(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function admin_can_view_applications(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function commission_can_view_applications(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertTrue($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function voter_cannot_view_applications(): void
    {
        $user = $this->userWithRole('voter');
        $this->assertFalse($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function member_cannot_view_applications(): void
    {
        $user = $this->userWithRole('member');
        $this->assertFalse($this->policy->viewApplications($user, $this->org));
    }

    // ── approveApplication ───────────────────────────────────────────────────

    /** @test */
    public function owner_can_approve_applications(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function admin_can_approve_applications(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function commission_cannot_approve_applications(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function voter_cannot_approve_applications(): void
    {
        $user = $this->userWithRole('voter');
        $this->assertFalse($this->policy->approveApplication($user, $this->org));
    }

    // ── rejectApplication ────────────────────────────────────────────────────

    /** @test */
    public function owner_can_reject_applications(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->rejectApplication($user, $this->org));
    }

    /** @test */
    public function admin_can_reject_applications(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->rejectApplication($user, $this->org));
    }

    /** @test */
    public function commission_cannot_reject_applications(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->rejectApplication($user, $this->org));
    }

    // ── manageMembershipTypes ────────────────────────────────────────────────

    /** @test */
    public function owner_can_manage_membership_types(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->manageMembershipTypes($user, $this->org));
    }

    /** @test */
    public function admin_cannot_manage_membership_types(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertFalse($this->policy->manageMembershipTypes($user, $this->org));
    }

    /** @test */
    public function commission_cannot_manage_membership_types(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->manageMembershipTypes($user, $this->org));
    }

    // ── recordFeePayment ─────────────────────────────────────────────────────

    /** @test */
    public function owner_can_record_fee_payment(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->recordFeePayment($user, $this->org));
    }

    /** @test */
    public function admin_can_record_fee_payment(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->recordFeePayment($user, $this->org));
    }

    /** @test */
    public function commission_cannot_record_fee_payment(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->recordFeePayment($user, $this->org));
    }

    /** @test */
    public function member_cannot_record_fee_payment(): void
    {
        $user = $this->userWithRole('member');
        $this->assertFalse($this->policy->recordFeePayment($user, $this->org));
    }

    // ── initiateRenewal ──────────────────────────────────────────────────────

    /** @test */
    public function owner_can_initiate_renewal(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->initiateRenewal($user, $this->org));
    }

    /** @test */
    public function admin_can_initiate_renewal(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->initiateRenewal($user, $this->org));
    }

    /** @test */
    public function commission_cannot_initiate_renewal(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->initiateRenewal($user, $this->org));
    }

    /** @test */
    public function member_can_initiate_own_renewal(): void
    {
        $user = $this->userWithRole('member');
        // self-renewal is handled at controller level by checking $user->id === $member->user_id
        // the policy grants access to member role for self-service
        $this->assertTrue($this->policy->initiateRenewal($user, $this->org, isSelf: true));
    }

    /** @test */
    public function member_cannot_initiate_renewal_for_others(): void
    {
        $user = $this->userWithRole('member');
        $this->assertFalse($this->policy->initiateRenewal($user, $this->org, isSelf: false));
    }

    // ── cross-organisation isolation ─────────────────────────────────────────

    /** @test */
    public function admin_of_other_org_cannot_view_applications(): void
    {
        $otherOrg  = Organisation::factory()->create(['type' => 'tenant']);
        $adminUser = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $adminUser->id,
            'organisation_id' => $otherOrg->id,
            'role'            => 'admin',
        ]);

        // This user is admin in otherOrg but has NO role in $this->org
        $this->assertFalse($this->policy->viewApplications($adminUser, $this->org));
    }
}
