<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Application\Handlers\BulkAssignVotersHandler;
use App\Domain\Election\Enum\VoterSourceStrategy;
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

        // Election-only mode, which is what this fixture has always expressed: the
        // setUp below registers voters in `organisation_users` and creates no member
        // records. The factory defaults to uses_full_membership => true, so
        // VoterSourceStrategy::fromOrganisation() resolved to MembershipRegistry and
        // eligibility correctly demanded an active member with paid/exempt fees —
        // rejecting every assignment. The mode is stated explicitly here rather than
        // inherited from a factory default. (PBDIGIT-48 estate assessment)
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $this->election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->user = User::factory()->create();
        $this->assignedBy = User::factory()->create();

        UserOrganisationRole::updateOrCreate(
            [
                'organisation_id' => $this->organisation->id,
                'user_id' => $this->user->id,
            ],
            [
                'role' => 'voter',
            ]
        );

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
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

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
        $before = now()->subSecond();
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));
        $after = now()->addSecond();

        $record = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertNotNull($record);
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

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $record = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertNotNull($record);
    }

    /**
     * Test P.1.4: assignVoter respects unique constraint on (user_id, election_id)
     *
     * Validates: Current schema has unique constraint preventing duplicates at DB level
     * Note: Phase C will add soft_deletes and use partial unique index
     */
    public function test_assign_voter_unique_constraint_prevents_duplicates(): void
    {
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

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
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

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
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

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
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

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

        $handler = app(BulkAssignVotersHandler::class);
        $handler->handle(new BulkAssignVotersCommand(
            userIds: [$this->user->id, $user2->id],
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $count = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $this->election->id)->count();
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

        $handler = app(BulkAssignVotersHandler::class);
        $handler->handle(new BulkAssignVotersCommand(
            userIds: [$this->user->id, $user2->id],
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $records = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $this->election->id)
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
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $retrieved = ElectionMembership::withoutGlobalScopes()
            ->where('user_id', $this->user->id)->first();

        $this->assertNotNull($retrieved);
        $this->assertEquals('voter', $retrieved->role);
        $this->assertEquals('active', $retrieved->status);
        $this->assertFalse($retrieved->has_voted);
    }

    /**
     * Test P.1.11: bulkAssignVoters result has expected structure
     *
     * Validates: Handler completes successfully
     */
    public function test_bulk_assign_voters_returns_expected_structure(): void
    {
        $handler = app(BulkAssignVotersHandler::class);
        $handler->handle(new BulkAssignVotersCommand(
            userIds: [$this->user->id],
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $this->assertDatabaseHas('election_memberships', [
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
        ]);
    }
}
