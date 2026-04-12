<?php

namespace Tests\Unit\Models;

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

class MemberTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $memberUser = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $memberUser->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);
        $orgUser = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $memberUser->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);

        $this->member = Member::create([
            'id'                    => (string) Str::uuid(),
            'organisation_id'       => $this->org->id,
            'organisation_user_id'  => $orgUser->id,
            'status'                => 'active',
            'membership_expires_at' => now()->addMonths(1),
        ]);
    }

    // ── canSelfRenew ──────────────────────────────────────────────────────────

    /** @test */
    public function can_self_renew_within_30_days_before_expiry(): void
    {
        $this->member->update(['membership_expires_at' => now()->addDays(30)]);
        $this->assertTrue($this->member->fresh()->canSelfRenew());
    }

    /** @test */
    public function can_self_renew_within_90_days_after_expiry(): void
    {
        $this->member->update(['membership_expires_at' => now()->subDays(89)]);
        $this->assertTrue($this->member->fresh()->canSelfRenew());
    }

    /** @test */
    public function cannot_self_renew_91_days_after_expiry(): void
    {
        $this->member->update(['membership_expires_at' => now()->subDays(91)]);
        $this->assertFalse($this->member->fresh()->canSelfRenew());
    }

    /** @test */
    public function lifetime_member_cannot_self_renew(): void
    {
        $this->member->update(['membership_expires_at' => null]);
        $this->assertFalse($this->member->fresh()->canSelfRenew());
    }

    /** @test */
    public function ended_member_cannot_self_renew(): void
    {
        $this->member->update(['status' => 'ended']);
        $this->assertFalse($this->member->fresh()->canSelfRenew());
    }

    // ── endMembership ─────────────────────────────────────────────────────────

    /** @test */
    public function end_membership_sets_status_ended_with_reason(): void
    {
        $this->member->endMembership('Voluntary resignation');

        $this->assertDatabaseHas('members', [
            'id'         => $this->member->id,
            'status'     => 'ended',
            'end_reason' => 'Voluntary resignation',
        ]);

        $this->assertNotNull($this->member->fresh()->ended_at);
    }

    /** @test */
    public function end_membership_waives_pending_fees(): void
    {
        $type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $this->member->id,
            'membership_type_id' => $type->id,
            'amount'             => 50.00,
            'currency'           => 'EUR',
            'fee_amount_at_time' => 50.00,
            'currency_at_time'   => 'EUR',
            'status'             => 'pending',
        ]);

        $this->member->endMembership('Resigned');

        $this->assertDatabaseHas('membership_fees', [
            'member_id' => $this->member->id,
            'status'    => 'waived',
        ]);
    }

    /** @test */
    public function end_membership_removes_from_active_elections(): void
    {
        $election = Election::factory()->create([
            'organisation_id' => $this->org->id,
        ]);

        $em = ElectionMembership::create([
            'id'              => (string) Str::uuid(),
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->organisationUser->user_id,
            'status'          => 'active',
        ]);

        $this->member->endMembership('Resigned');

        $this->assertDatabaseHas('election_memberships', [
            'id'     => $em->id,
            'status' => 'removed',
        ]);
    }
}
