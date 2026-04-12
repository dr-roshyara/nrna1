<?php

/**
 * TDD — Member Voting Rights
 *
 * Architecture reference:
 *   architecture/membership/20260404_2344_redefine_membership_participants_guests.md
 *
 * Domain rules under test:
 *   - A Member's voting_rights are derived from fees_status + membership status.
 *   - Only a MembershipType that carries grants_voting_rights = true can ever
 *     produce 'full' voting rights (Full Member).
 *   - Associate Member (grants_voting_rights = false) is capped at 'voice_only'
 *     regardless of fee payment.
 *   - Expired / suspended members always get 'none'.
 *
 * All tests in this file MUST FAIL on first run (Red).
 * Passing them (Green) requires:
 *   - members.fees_status  column   (enum: paid|unpaid|partial|exempt)
 *   - members.voting_rights column  (enum: full|voice_only|none)   [computed/stored]
 *   - membership_types.grants_voting_rights column  (boolean, default false)
 *   - Member::getVotingRightsAttribute() accessor
 *   - Member::canVoteInElection(Election $election): bool
 */

namespace Tests\Feature\Membership\Domain;

use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberVotingRightsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipType $fullType;
    private MembershipType $associateType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        session(['current_organisation_id' => $this->org->id]);

        // Full Member type — grants voting rights
        $this->fullType = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'name'               => 'Full Member',
            'grants_voting_rights' => true,
            'fee_amount'         => 50.00,
        ]);

        // Associate Member type — observer only, no voting rights
        $this->associateType = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'name'               => 'Associate Member',
            'grants_voting_rights' => false,
            'fee_amount'         => 20.00,
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function makeMember(
        MembershipType $type,
        string $status,
        string $feesStatus
    ): Member {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        UserOrganisationRole::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        return Member::factory()->create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id'   => $type->id,
            'status'               => $status,
            'fees_status'          => $feesStatus,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Full Member type (grants_voting_rights = true)
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function full_member_with_paid_fees_has_full_voting_rights(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'paid');

        $this->assertSame('full', $member->voting_rights);
    }

    /** @test */
    public function full_member_with_partial_fees_has_voice_only_voting_rights(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'partial');

        $this->assertSame('voice_only', $member->voting_rights);
    }

    /** @test */
    public function full_member_with_unpaid_fees_has_no_voting_rights(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'unpaid');

        $this->assertSame('none', $member->voting_rights);
    }

    /** @test */
    public function full_member_with_exempt_fees_has_full_voting_rights(): void
    {
        // Fee-exempt members (e.g. honorary) still get full rights if type grants them
        $member = $this->makeMember($this->fullType, 'active', 'exempt');

        $this->assertSame('full', $member->voting_rights);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Associate Member type (grants_voting_rights = false)
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function associate_member_with_paid_fees_has_voice_only_not_full(): void
    {
        $member = $this->makeMember($this->associateType, 'active', 'paid');

        // Associate type caps at voice_only even with paid fees
        $this->assertSame('voice_only', $member->voting_rights);
    }

    /** @test */
    public function associate_member_with_unpaid_fees_has_no_voting_rights(): void
    {
        $member = $this->makeMember($this->associateType, 'active', 'unpaid');

        $this->assertSame('none', $member->voting_rights);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Expired / suspended override
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function expired_member_always_has_no_voting_rights(): void
    {
        $member = $this->makeMember($this->fullType, 'expired', 'paid');

        $this->assertSame('none', $member->voting_rights);
    }

    /** @test */
    public function suspended_member_always_has_no_voting_rights(): void
    {
        $member = $this->makeMember($this->fullType, 'suspended', 'paid');

        $this->assertSame('none', $member->voting_rights);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — fees_status column exists on Member
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function member_stores_fees_status_in_database(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'paid');

        $this->assertDatabaseHas('members', [
            'id'          => $member->id,
            'fees_status' => 'paid',
        ]);
    }

    /** @test */
    public function fees_status_defaults_to_unpaid_when_not_specified(): void
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);

        $member = Member::factory()->create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id'   => $this->fullType->id,
            'status'               => 'active',
            // fees_status intentionally omitted
        ]);

        $this->assertSame('unpaid', $member->fresh()->fees_status);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 5 — grants_voting_rights column exists on MembershipType
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function membership_type_stores_grants_voting_rights_flag(): void
    {
        $this->assertDatabaseHas('membership_types', [
            'id'                  => $this->fullType->id,
            'grants_voting_rights' => true,
        ]);

        $this->assertDatabaseHas('membership_types', [
            'id'                  => $this->associateType->id,
            'grants_voting_rights' => false,
        ]);
    }

    /** @test */
    public function grants_voting_rights_defaults_to_false_on_new_types(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id' => $this->org->id,
            // grants_voting_rights intentionally omitted
        ]);

        $this->assertFalse((bool) $type->fresh()->grants_voting_rights);
    }
}
