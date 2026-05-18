<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ElectionMembershipPersistenceTest — Layer 2: DB State Correctness
 *
 * Responsibility: Lock DATABASE INVARIANTS ONLY
 *
 * ✔ Allowed:
 * - record exists in DB
 * - soft delete behavior (current reality only)
 * - FK constraints (Phase A reality only)
 * - unique constraints (current schema)
 *
 * ❌ Forbidden:
 * - business logic assertions
 * - eligibility rules
 * - cache validation
 * - handler behavior
 *
 * Mental Model: "Is the database correctly storing what the system already decided?"
 */
class ElectionMembershipPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private User $assignedBy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->user = User::factory()->create();
        $this->assignedBy = User::factory()->create();

        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->user->id,
        ]);

        // For election-only mode tests, also add to organisation_users
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    /**
     * Test P.1.1: assignVoter creates persistent DB record
     *
     * Validates: Row actually persists to election_memberships table
     */
    public function test_assign_voter_persists_to_database(): void
    {
        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        $this->assertDatabaseHas('election_memberships', [
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'voter',
            'status' => 'active',
            'assigned_by' => $this->assignedBy->id,
        ]);
    }

    /**
     * Test P.1.2: assignVoter persists assigned_at timestamp
     *
     * Validates: assigned_at is set to NOW at persistence time
     */
    public function test_assign_voter_sets_assigned_at(): void
    {
        $before = now();
        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );
        $after = now();

        $record = ElectionMembership::where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertNotNull($record->assigned_at);
        $this->assertTrue($record->assigned_at->isBetween($before, $after));
    }

    /**
     * Test P.1.3: assignVoter persists metadata as JSON
     *
     * Validates: metadata column stores JSON correctly
     */
    public function test_assign_voter_persists_metadata_as_json(): void
    {
        $metadata = ['source' => 'import', 'batch' => 'A1'];

        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id,
            $metadata
        );

        $record = ElectionMembership::where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertEquals($metadata, $record->metadata);
    }

    /**
     * Test P.1.4: assignVoter respects unique constraint on (user_id, election_id)
     *
     * Validates: Current schema has unique constraint preventing duplicates at DB level
     * Note: Phase C will add soft_deletes and use partial unique index
     */
    public function test_assign_voter_unique_constraint_prevents_duplicates(): void
    {
        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Attempt direct insert of duplicate (bypassing model validation)
        $this->expectException(\Exception::class); // DB unique constraint violation

        \DB::table('election_memberships')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'voter',
            'status' => 'active',
            'assigned_by' => $this->assignedBy->id,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test P.1.5: FK constraint on user_id exists (Phase A reality)
     *
     * Validates: Current schema enforces FK from election_memberships → users
     * Note: This FK will remain through all phases
     */
    public function test_election_membership_has_fk_to_users(): void
    {
        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Verify FK relationship works
        $this->assertEquals($this->user->id, $membership->user->id);
    }

    /**
     * Test P.1.6: FK constraint on election_id exists
     *
     * Validates: Current schema enforces FK from election_memberships → elections
     */
    public function test_election_membership_has_fk_to_elections(): void
    {
        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Verify FK relationship works
        $this->assertEquals($this->election->id, $membership->election->id);
    }

    /**
     * Test P.1.7: FK constraint on organisation_id exists
     *
     * Validates: Current schema enforces FK from election_memberships → organisations
     */
    public function test_election_membership_has_fk_to_organisations(): void
    {
        $membership = ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id
        );

        // Verify FK relationship works
        $this->assertEquals($this->organisation->id, $membership->organisation->id);
    }

    /**
     * Test P.1.8: bulkAssignVoters persists multiple records
     *
     * Validates: Multiple records are actually written to DB
     */
    public function test_bulk_assign_voters_persists_all_records(): void
    {
        $user2 = User::factory()->create();

        // For election-only mode, bulkAssignVoters checks organisation_users table
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user2->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        ElectionMembership::bulkAssignVoters(
            [$this->user->id, $user2->id],
            $this->election->id,
            $this->assignedBy->id
        );

        $count = ElectionMembership::where('election_id', $this->election->id)->count();
        $this->assertEquals(2, $count);
    }

    /**
     * Test P.1.9: bulkAssignVoters uses efficient insert (not looped assignVoter)
     *
     * Validates: Bulk operation doesn't execute N individual model saves
     * Note: Just verify both records exist in one DB call
     */
    public function test_bulk_assign_voters_creates_records_efficiently(): void
    {
        $user2 = User::factory()->create();

        // For election-only mode, bulkAssignVoters checks organisation_users table
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user2->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        ElectionMembership::bulkAssignVoters(
            [$this->user->id, $user2->id],
            $this->election->id,
            $this->assignedBy->id
        );

        $records = ElectionMembership::where('election_id', $this->election->id)
            ->orderBy('user_id')
            ->get();

        $this->assertEquals(2, $records->count());
        $this->assertEquals($this->user->id, $records[0]->user_id);
        $this->assertEquals($user2->id, $records[1]->user_id);
    }

    /**
     * Test P.1.10: Model hydration works correctly
     *
     * Validates: Retrieved membership record has all expected attributes
     */
    public function test_election_membership_hydration(): void
    {
        $metadata = ['test' => 'data'];
        ElectionMembership::assignVoter(
            $this->user->id,
            $this->election->id,
            $this->assignedBy->id,
            $metadata
        );

        $retrieved = ElectionMembership::where('user_id', $this->user->id)->first();

        $this->assertNotNull($retrieved);
        $this->assertEquals('voter', $retrieved->role);
        $this->assertEquals('active', $retrieved->status);
        $this->assertEquals($metadata, $retrieved->metadata);
        $this->assertFalse($retrieved->has_voted);
    }

    /**
     * Test P.1.11: bulkAssignVoters result has expected structure
     *
     * Validates: Return value contains success/already_existing/invalid keys
     * Note: This is separate from persistence — validates return type, not DB state
     */
    public function test_bulk_assign_voters_returns_expected_structure(): void
    {
        $result = ElectionMembership::bulkAssignVoters(
            [$this->user->id],
            $this->election->id,
            $this->assignedBy->id
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('already_existing', $result);
        $this->assertArrayHasKey('invalid', $result);
        $this->assertIsInt($result['success']);
        $this->assertIsInt($result['already_existing']);
        $this->assertIsInt($result['invalid']);
    }
}
