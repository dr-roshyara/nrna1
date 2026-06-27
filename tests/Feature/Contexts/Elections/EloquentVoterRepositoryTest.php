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
 * EloquentVoterRepositoryTest
 *
 * Phase C.2: Repository behavior for voter assignment
 * - Find with trashed (soft-deleted recovery)
 * - Create new membership
 * - Restore and update (re-import without collision)
 * - Bulk insert for high-volume scenarios
 * - Existing voter ID list
 */
class EloquentVoterRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organisation $organisation;
    private Election $election;
    private \App\Contexts\Elections\Infrastructure\Repositories\EloquentVoterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $this->election = Election::factory()->for($this->organisation)->create();

        // Create OrganisationUser for election-only mode
        OrganisationUser::factory()
            ->for($this->user)
            ->for($this->organisation)
            ->create(['status' => 'active']);

        $this->repository = new \App\Contexts\Elections\Infrastructure\Repositories\EloquentVoterRepository();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function find_with_trashed_finds_soft_deleted_membership(): void
    {
        $membership = ElectionMembership::create([
            'user_id'        => $this->user->id,
            'election_id'    => $this->election->id,
            'organisation_id'=> $this->organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        $membership->delete();
        $this->assertTrue($membership->trashed());

        // Repository should find soft-deleted
        $found = $this->repository->findWithTrashed($this->user->id, $this->election->id);

        $this->assertNotNull($found);
        $this->assertEquals($membership->id, $found->id);
        $this->assertTrue($found->trashed());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function find_with_trashed_returns_null_for_non_existent(): void
    {
        $nonExistentId = 'ffffffff-ffff-ffff-ffff-ffffffffffff';
        $found = $this->repository->findWithTrashed($nonExistentId, $this->election->id);
        $this->assertNull($found);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function create_inserts_new_membership(): void
    {
        $attributes = [
            'user_id'        => $this->user->id,
            'election_id'    => $this->election->id,
            'organisation_id'=> $this->organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ];

        $created = $this->repository->create($attributes);

        $this->assertNotNull($created->id);
        $this->assertEquals($this->user->id, $created->user_id);
        $this->assertEquals($this->election->id, $created->election_id);
        $this->assertNull($created->deleted_at);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function restore_and_update_undeletes_and_updates_row(): void
    {
        $membership = ElectionMembership::create([
            'user_id'        => $this->user->id,
            'election_id'    => $this->election->id,
            'organisation_id'=> $this->organisation->id,
            'role'           => 'voter',
            'status'         => 'inactive',
        ]);

        $membership->delete();
        $this->assertTrue($membership->trashed());

        // Repository restores and updates
        $restored = $this->repository->restoreAndUpdate(
            $membership,
            ['status' => 'active', 'assigned_at' => now()]
        );

        $this->assertFalse($restored->trashed());
        $this->assertEquals('active', $restored->status);
        $this->assertNotNull($restored->assigned_at);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function existing_voter_ids_excludes_soft_deleted_rows(): void
    {
        // Create active membership
        ElectionMembership::create([
            'user_id'        => $this->user->id,
            'election_id'    => $this->election->id,
            'organisation_id'=> $this->organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);

        // Create and soft-delete another
        $deleted = User::factory()->create();
        OrganisationUser::factory()
            ->for($deleted)
            ->for($this->organisation)
            ->create(['status' => 'active']);

        $membership = ElectionMembership::create([
            'user_id'        => $deleted->id,
            'election_id'    => $this->election->id,
            'organisation_id'=> $this->organisation->id,
            'role'           => 'voter',
            'status'         => 'active',
        ]);
        $membership->delete();

        $existing = $this->repository->existingVoterIds($this->election->id);

        // Should only include active (not soft-deleted)
        $this->assertCount(1, $existing);
        $this->assertContains($this->user->id, $existing);
        $this->assertNotContains($deleted->id, $existing);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function bulk_insert_creates_multiple_records(): void
    {
        $user2 = User::factory()->create();
        OrganisationUser::factory()
            ->for($user2)
            ->for($this->organisation)
            ->create(['status' => 'active']);

        $id1 = '11111111-1111-1111-1111-111111111111';
        $id2 = '22222222-2222-2222-2222-222222222222';
        $now = new \DateTime();

        $rows = [
            [
                'id'             => $id1,
                'user_id'        => $this->user->id,
                'election_id'    => $this->election->id,
                'organisation_id'=> $this->organisation->id,
                'role'           => 'voter',
                'status'         => 'active',
                'created_at'     => $now->format('Y-m-d H:i:s'),
                'updated_at'     => $now->format('Y-m-d H:i:s'),
            ],
            [
                'id'             => $id2,
                'user_id'        => $user2->id,
                'election_id'    => $this->election->id,
                'organisation_id'=> $this->organisation->id,
                'role'           => 'voter',
                'status'         => 'active',
                'created_at'     => $now->format('Y-m-d H:i:s'),
                'updated_at'     => $now->format('Y-m-d H:i:s'),
            ],
        ];

        $this->repository->bulkInsert($rows);

        $count = ElectionMembership::query()
            ->withoutGlobalScopes()
            ->where('election_id', $this->election->id)
            ->count();
        $this->assertEquals(2, $count);
    }
}
