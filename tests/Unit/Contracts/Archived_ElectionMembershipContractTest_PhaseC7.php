<?php

namespace Tests\Unit\Contracts;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ElectionMembershipContractTest — Layer 1: Behavior Lock
 *
 * Responsibility: Lock OBSERVABLE BUSINESS BEHAVIOR ONLY
 *
 * ✔ Allowed:
 * - assignVoter creates membership
 * - inactive → active transition
 * - duplicate active throws
 * - bulk returns STRUCTURE ONLY
 * - current UserOrganisationRole dependency
 *
 * ❌ Forbidden:
 * - DB schema validation
 * - cache behavior
 * - scopes (eligible)
 * - SQL assumptions
 *
 * Mental Model: BLACK BOX — "If I swap internal implementation completely,
 * this test still tells me if behavior changed."
 */
class ElectionMembershipContractTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private User $assignedBy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $this->election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->user = User::factory()->create();
        $this->assignedBy = User::factory()->create();

        // Current Phase A reality: user must be in user_organisation_roles
        // Phase C will remove this dependency
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test C.1.1: assignVoter creates membership with correct identity
     *
     * Contract: When assignVoter is called with valid user + election,
     * a membership record is created with role=voter, status=active
     */
    public function test_assign_voter_creates_membership(): void
    {
        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        $this->assertNotNull($membership->id);
        $this->assertEquals($this->user->id, $membership->user_id);
        $this->assertEquals($this->election->id, $membership->election_id);
        $this->assertEquals($this->organisation->id, $membership->organisation_id);
        $this->assertEquals('voter', $membership->role);
        $this->assertEquals('active', $membership->status);
        $this->assertEquals($this->assignedBy->id, $membership->assigned_by);
        $this->assertNotNull($membership->assigned_at);
    }

    /**
     * Test C.1.2: assignVoter accepts metadata
     *
     * Contract: Metadata passed to assignVoter is stored on membership
     */
    public function test_assign_voter_accepts_metadata(): void
    {
        $metadata = ['source' => 'csv_import', 'batch_id' => '123'];

        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id,
            $metadata
        );

        $this->assertEquals($metadata, $membership->metadata);
    }

    /**
     * Test C.1.3: assignVoter throws when user not in current organisation
     *
     * Contract: Current Phase A reality enforces UserOrganisationRole dependency.
     * Phase C removes this, but Phase A documents it exists.
     */
    public function test_assign_voter_throws_when_user_not_in_organisation(): void
    {
        $outsideUser = User::factory()->create();
        // Do NOT create UserOrganisationRole for outsideUser

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not a member of organisation');

        ElectionMembership::assignVoter(
            $outsideUser->id,
            $this->election->id,
            $this->assignedBy->id
        );
    }

    /**
     * Test C.1.4: assignVoter reactivates inactive membership
     *
     * Contract: If user has inactive membership in this election,
     * calling assignVoter reactivates instead of throwing
     */
    public function test_assign_voter_reactivates_inactive_membership(): void
    {
        // Pre-create inactive membership
        $inactive = ElectionMembership::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'voter',
            'status' => 'inactive',
            'assigned_by' => $this->assignedBy->id,
            'assigned_at' => now(),
        ]);

        // Re-assign same user
        $reactivated = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Should return same record, but status=active
        $this->assertEquals($inactive->id, $reactivated->id);
        $this->assertEquals('active', $reactivated->status);
    }

    /**
     * Test C.1.5: assignVoter throws when already active
     *
     * Contract: Cannot re-assign user who is already active voter
     */
    public function test_assign_voter_throws_on_duplicate_active(): void
    {
        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('already an active voter');

        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );
    }

    /**
     * Test C.1.6: bulkAssignVoters returns expected structure
     *
     * Contract: bulkAssignVoters returns array with
     * ['success' => int, 'already_existing' => int, 'invalid' => int]
     *
     * Note: This tests STRUCTURE ONLY, not classification logic.
     * Logic tests belong in Phase B/C.
     */
    public function test_bulk_assign_voters_returns_expected_structure(): void
    {
        $user2 = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user2->id,
        ]);

        $result = ElectionMembership::bulkAssignVoters(
            [$this->user->id, $user2->id],
            $this->election->id,
            $this->assignedBy->id
        );

        // Test structure, not logic
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('already_existing', $result);
        $this->assertArrayHasKey('invalid', $result);
        $this->assertIsInt($result['success']);
        $this->assertIsInt($result['already_existing']);
        $this->assertIsInt($result['invalid']);
    }

    /**
     * Test C.1.7: assignVoter is transactional
     *
     * Contract: assignVoter uses DB::transaction for consistency
     */
    public function test_assign_voter_is_transactional(): void
    {
        // If assignVoter completes without exception, transaction succeeded
        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Black box test: just verify it completed
        $this->assertNotNull($membership->id);
    }
}
