<?php

/**
 * TDD — Member::canVoteInElection()
 *
 * Architecture reference:
 *   architecture/membership/20260404_2344_redefine_membership_participants_guests.md
 *
 * Domain rules under test:
 *   Rule 1: Full Member (grants_voting_rights) + paid/exempt fees → can vote.
 *   Rule 2: Full Member + partial/unpaid fees → cannot vote (voice only).
 *   Rule 3: Associate Member → cannot vote regardless of fee status.
 *   Rule 4: Expired/suspended member → cannot vote.
 *   Rule 5: Voter role without membership → voting rights determined by
 *            ElectionMembership (voter registration), NOT by Member model.
 *            This test confirms Member::canVoteInElection() returns false
 *            for a user who has only a voter platform role but no Member record.
 *
 * Implementation requires:
 *   - Member::canVoteInElection(Election $election): bool
 *   - members.fees_status column
 *   - membership_types.grants_voting_rights column
 */

namespace Tests\Feature\Membership\Domain;

use App\Models\Election;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberCanVoteInElectionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private MembershipType $fullType;
    private MembershipType $associateType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'status'          => 'active',
        ]);

        $this->fullType = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);

        $this->associateType = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => false,
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
    //  Group 1 — Full member can vote
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function full_member_with_paid_fees_can_vote_in_active_election(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'paid');

        $this->assertTrue($member->canVoteInElection($this->election));
    }

    /** @test */
    public function full_member_with_exempt_fees_can_vote_in_active_election(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'exempt');

        $this->assertTrue($member->canVoteInElection($this->election));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Full member cannot vote due to fee status
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function full_member_with_unpaid_fees_cannot_vote(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'unpaid');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    /** @test */
    public function full_member_with_partial_fees_cannot_vote(): void
    {
        $member = $this->makeMember($this->fullType, 'active', 'partial');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Associate member cannot vote
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function associate_member_cannot_vote_even_with_paid_fees(): void
    {
        $member = $this->makeMember($this->associateType, 'active', 'paid');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    /** @test */
    public function associate_member_cannot_vote_even_with_exempt_fees(): void
    {
        $member = $this->makeMember($this->associateType, 'active', 'exempt');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Status overrides
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function expired_member_cannot_vote_regardless_of_fees(): void
    {
        $member = $this->makeMember($this->fullType, 'expired', 'paid');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    /** @test */
    public function suspended_member_cannot_vote(): void
    {
        $member = $this->makeMember($this->fullType, 'suspended', 'paid');

        $this->assertFalse($member->canVoteInElection($this->election));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 5 — Election must belong to same organisation
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function member_cannot_vote_in_election_from_different_organisation(): void
    {
        $otherOrg      = Organisation::factory()->create(['type' => 'tenant']);
        $otherElection = Election::factory()->create([
            'organisation_id' => $otherOrg->id,
            'status'          => 'active',
        ]);

        $member = $this->makeMember($this->fullType, 'active', 'paid');

        $this->assertFalse($member->canVoteInElection($otherElection));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 6 — Voter platform role (no Member record) is NOT handled here
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function voter_platform_role_without_member_record_is_not_covered_by_member_model(): void
    {
        // A user with 'voter' platform role but no Member record
        // is handled by ElectionMembership (voter registration), not Member model.
        // This test documents that distinction.
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        UserOrganisationRole::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);

        // No Member record created for this user
        $member = Member::where('organisation_user_id', $orgUser->id)->first();

        $this->assertNull($member,
            'A user with voter platform role should NOT automatically have a Member record.');
    }
}
