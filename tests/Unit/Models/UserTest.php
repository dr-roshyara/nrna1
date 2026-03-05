<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a user with an organisation
     */
    private function createUserWithOrganisation(Organisation $org): User
    {
        $userId = Str::uuid();
        \DB::table('users')->insert([
            'id' => $userId,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'organisation_id' => $org->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return User::find($userId);
    }

    /**
     * Test that belongsToOrganisation checks the pivot table correctly
     */
    public function test_belongs_to_organisation_returns_true_when_user_has_pivot_record()
    {
        // Setup: Create organisations
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Create user for org1
        $user = $this->createUserWithOrganisation($org1);

        // Action: Create pivot record with UUID id
        \DB::table('user_organisation_roles')->insert([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert: belongsToOrganisation should return true for org2
        $this->assertTrue($user->belongsToOrganisation($org2->id));
    }

    /**
     * Test that belongsToOrganisation returns false when user has no pivot record
     */
    public function test_belongs_to_organisation_returns_false_when_user_has_no_pivot_record()
    {
        // Setup: Create organisations
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Create user for org1
        $user = $this->createUserWithOrganisation($org1);

        // Assert: belongsToOrganisation should return false for org2 (no pivot record)
        $this->assertFalse($user->belongsToOrganisation($org2->id));
    }

    /**
     * Test that direct organisation_id attribute can be accessed (not auto-changed by boot hook)
     *
     * After removing the User::booted() hook, the organisation_id attribute is no longer
     * automatically assigned. Users are now bound to organisations only through the
     * organisations() relationship and user_organisation_roles pivot table.
     */
    public function test_user_organisation_id_attribute_respects_explicit_assignment()
    {
        // Setup: Create two organisations
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Create a user with org1 as their organisation_id
        $user = $this->createUserWithOrganisation($org1);

        // Assert: Direct access to organisation_id works (should be org1)
        $this->assertEquals($org1->id, $user->organisation_id);

        // Assert: User does NOT belong to org2 (no pivot record)
        $this->assertFalse($user->belongsToOrganisation($org2->id));
    }

    /**
     * Test that organisations() relationship works correctly
     */
    public function test_organisations_relationship_returns_organisations()
    {
        // Setup: Create organisations
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();
        $org3 = Organisation::factory()->create();

        // Create user for org1
        $user = $this->createUserWithOrganisation($org1);

        // Create pivots to add user to org2 and org3
        \DB::table('user_organisation_roles')->insert([
            [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'organisation_id' => $org2->id,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'organisation_id' => $org3->id,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Assert: organisations relationship returns org2 and org3 (not org1, which is just the organisation_id attribute)
        $organisations = $user->organisations()->get();
        $this->assertCount(2, $organisations);
        $this->assertTrue($organisations->pluck('id')->contains($org2->id));
        $this->assertTrue($organisations->pluck('id')->contains($org3->id));
    }
}
