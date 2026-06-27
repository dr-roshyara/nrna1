<?php

namespace Tests\Feature\Contexts\Elections;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ElectionMembershipsMigrationTest
 *
 * Phase C.1: Verify schema changes for soft deletes + partial unique index
 * - SoftDeletes trait enabled
 * - Partial unique index allows re-import of removed voters
 * - Database FK to user_organisation_roles removed (application-level guard takes over)
 */
class ElectionMembershipsMigrationTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function election_memberships_table_has_deleted_at_column(): void
    {
        $columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()
            ->getColumns('election_memberships');

        $hasDeletedAt = collect($columns)
            ->pluck('name')
            ->contains('deleted_at');

        $this->assertTrue($hasDeletedAt, 'election_memberships table missing deleted_at column');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function partial_unique_index_allows_same_user_election_when_first_is_soft_deleted(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $election = Election::factory()->for($organisation)->create();

        // Create OrganisationUser (required for election-only mode)
        OrganisationUser::factory()
            ->for($user)
            ->for($organisation)
            ->create(['status' => 'active']);

        // First assignment
        $first = ElectionMembership::create([
            'user_id'        => $user->id,
            'election_id'    => $election->id,
            'organisation_id'=> $organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        // Soft delete first membership
        $first->delete();
        $this->assertTrue($first->trashed(), 'First membership not soft-deleted');

        // Second assignment — should NOT hit unique constraint
        // because partial index excludes deleted_at IS NOT NULL rows
        $second = ElectionMembership::create([
            'user_id'        => $user->id,
            'election_id'    => $election->id,
            'organisation_id'=> $organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        $this->assertEquals($user->id, $second->user_id);
        $this->assertEquals($election->id, $second->election_id);
        $this->assertNull($second->deleted_at);
        $this->assertNotEquals($first->id, $second->id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function voter_can_be_assigned_with_only_organisation_user_record_no_user_org_role(): void
    {
        // THE ROOT BUG TEST:
        // In election-only mode, user has OrganisationUser record only.
        // No UserOrganisationRole record exists.
        // FK constraint to user_organisation_roles blocked assignment.
        // After migration, application-level policy validates instead.

        $user = User::factory()->create();
        $organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $election = Election::factory()->for($organisation)->create();

        // Create OrganisationUser only (no UserOrganisationRole)
        OrganisationUser::factory()
            ->for($user)
            ->for($organisation)
            ->create(['status' => 'active']);

        // Should NOT throw foreign key constraint error
        $membership = ElectionMembership::create([
            'user_id'        => $user->id,
            'election_id'    => $election->id,
            'organisation_id'=> $organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        $this->assertNotNull($membership->id);
        $this->assertEquals($user->id, $membership->user_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function soft_deleted_voter_can_be_restored(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $election = Election::factory()->for($organisation)->create();

        OrganisationUser::factory()
            ->for($user)
            ->for($organisation)
            ->create(['status' => 'active']);

        $membership = ElectionMembership::create([
            'user_id'        => $user->id,
            'election_id'    => $election->id,
            'organisation_id'=> $organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        $membership->delete();
        $this->assertTrue($membership->trashed());

        $membership->restore();
        $this->assertFalse($membership->trashed());
        $this->assertEquals('voter', $membership->role);
    }
}
