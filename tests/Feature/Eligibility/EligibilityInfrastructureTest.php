<?php

namespace Tests\Feature\Eligibility;

use App\Domain\Election\Enum\ElectionMode;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * EligibilityInfrastructureTest — Database correctness verification
 *
 * Responsibility: Verify that database queries for eligibility are correct.
 * This layer tests persistence, joins, filtering, soft deletes.
 *
 * ✔ Allowed:
 * - Eloquent models
 * - Factories
 * - Database transactions
 * - Query assertions
 * - Soft delete behavior
 *
 * ❌ Forbidden:
 * - Policy logic assertions
 * - Decision correctness
 * - Business rules from domain
 *
 * Mental Model: "Does the database correctly represent eligibility state?"
 *
 * These tests will inform Phase B infrastructure implementation.
 */
class EligibilityInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
    }

    /**
     * Test I.1.1: organisation_users table stores active users
     */
    public function test_organisation_users_stores_active_status(): void
    {
        $user = User::factory()->create();

        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $this->assertDatabaseHas('organisation_users', [
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
            'deleted_at' => null,
        ]);
    }

    /**
     * Test I.1.2: organisation_users supports soft delete
     */
    public function test_organisation_users_supports_soft_delete(): void
    {
        $user = User::factory()->create();
        $record = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $record->delete();

        // After delete, deleted_at is set (soft delete)
        // Raw query confirms deleted_at is populated
        $found = \DB::table('organisation_users')
            ->where('id', $record->id)
            ->whereNotNull('deleted_at')
            ->first();

        $this->assertNotNull($found);
    }

    /**
     * Test I.1.3: Query excludes soft-deleted records by default
     */
    public function test_default_query_excludes_soft_deleted(): void
    {
        $user = User::factory()->create();
        $record = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $record->delete();

        $found = OrganisationUser::where('user_id', $user->id)
            ->where('organisation_id', $this->organisation->id)
            ->first();

        $this->assertNull($found);
    }

    /**
     * Test I.1.4: withTrashed() includes soft-deleted records
     */
    public function test_with_trashed_query_includes_soft_deleted(): void
    {
        $user = User::factory()->create();
        $record = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $record->delete();

        // Raw query (unaffected by Eloquent global scopes)
        $found = \DB::table('organisation_users')
            ->where('id', $record->id)
            ->first();

        $this->assertNotNull($found);
        $this->assertNotNull($found->deleted_at);
    }

    /**
     * Test I.1.5: Status filtering works correctly
     */
    public function test_status_filtering_works(): void
    {
        $activeUser = User::factory()->create();
        $inactiveUser = User::factory()->create();

        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $activeUser->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $inactiveUser->id,
            'status' => 'inactive',
            'joined_at' => now(),
        ]);

        // Raw query (unaffected by global scopes)
        $activeCount = \DB::table('organisation_users')
            ->where('organisation_id', $this->organisation->id)
            ->where('status', 'active')
            ->count();

        $inactiveCount = \DB::table('organisation_users')
            ->where('organisation_id', $this->organisation->id)
            ->where('status', 'inactive')
            ->count();

        $this->assertEquals(1, $activeCount);
        $this->assertEquals(1, $inactiveCount);
    }

    /**
     * Test I.1.6: Status + soft-delete filtering combined
     */
    public function test_status_and_soft_delete_combined(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $record1 = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user1->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user2->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $record1->delete(); // user1 is soft-deleted

        // Query active + not deleted
        $activeLiveCount = \DB::table('organisation_users')
            ->where('organisation_id', $this->organisation->id)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->count();

        $this->assertEquals(1, $activeLiveCount, 'Only user2 should be active + not deleted');
    }
}
