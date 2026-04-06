<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ✅ TEST: Foreign key constraint prevents hard deleting organisations with active users
     *
     * This test verifies that the database foreign key constraint on users.organisation_id
     * is working correctly. When an organisation has active users, it cannot be hard deleted.
     *
     * Domain Rule: Users MUST always belong to a valid organisation
     * Constraint: onDelete('restrict') - Never allow deletion if users exist
     *
     * Note: Soft deletes don't actually remove rows from the database, so FK constraint
     * isn't triggered for soft deletes. We test forceDelete() instead.
     */
    public function test_cannot_hard_delete_organisation_with_active_users(): void
    {
        // Arrange - Create a platform organisation
        $org = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => false,
        ]);

        // Create a user belonging to that organisation
        // Note: HasOrganisation trait moves organisation_id to property, so we use DB::table
        $user_id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        \DB::table('users')->insert([
            'id' => $user_id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $org->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::find($user_id);

        // Assert - User exists in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'organisation_id' => $org->id,
        ]);

        // Act & Assert - Attempting to force delete organisation should throw QueryException
        $this->expectException(QueryException::class);
        $org->forceDelete();
    }

    /**
     * ✅ TEST: Can hard delete organisation without users
     *
     * This test verifies that organisations without users can be hard deleted.
     * This is the valid path for organisation cleanup.
     */
    public function test_can_hard_delete_organisation_without_users(): void
    {
        // Arrange - Create a platform organisation without users
        $org = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => false,
        ]);

        // Assert - Organisation exists
        $this->assertDatabaseHas('organisations', ['id' => $org->id]);

        // Act - Hard delete organisation (should succeed because no users reference it)
        $org->forceDelete();

        // Assert - Organisation is completely deleted from database
        $this->assertDatabaseMissing('organisations', ['id' => $org->id]);
    }

    /**
     * ✅ TEST: User cannot be created with non-existent organisation_id
     *
     * This test verifies that the foreign key constraint prevents creating users
     * that reference non-existent organisations.
     */
    public function test_cannot_create_user_with_invalid_organisation(): void
    {
        // Arrange - Use a fake UUID that doesn't exist
        $fakeOrgId = \Ramsey\Uuid\Uuid::uuid4()->toString();

        // Act & Assert - Attempting to create user should throw QueryException
        $this->expectException(QueryException::class);
        User::factory()->create([
            'organisation_id' => $fakeOrgId,
        ]);
    }
}
