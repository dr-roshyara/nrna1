<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginOrganisationAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function newly_registered_user_has_pivot_record_created()
    {
        // RED: Write test first - this should FAIL initially
        $userData = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'region' => 'Bayern',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'terms' => 'on',
        ];

        // Register user
        $response = $this->post('/register', $userData);

        // Get the registered user
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user, 'User should be created');

        // CRITICAL: Check if pivot record exists
        $pivotRecord = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $user->organisation_id)
            ->first();

        $this->assertNotNull($pivotRecord, "Pivot record should exist for user {$user->id} in organisation {$user->organisation_id}");
        $this->assertEquals('member', $pivotRecord->role, 'Role should be member');
    }

    /** @test */
    public function user_can_access_their_default_organisation_after_login()
    {
        // RED: This test reproduces the 403 error
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Verify user has organisation_id set
        $this->assertNotNull($user->organisation_id, 'User should have organisation_id');

        // Manually create pivot record (simulating what RegisterController should do)
        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $user->organisation_id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify pivot record exists
        $pivotExists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $user->organisation_id)
            ->exists();
        $this->assertTrue($pivotExists, 'Pivot record should exist');

        // Get the organisation
        $organisation = \App\Models\Organisation::find($user->organisation_id);
        $this->assertNotNull($organisation, 'Organisation should exist');

        // Log in as user
        $response = $this->actingAs($user)
            ->get('/organisations/' . $organisation->slug);

        // Should NOT get 403
        $response->assertStatus(200, "User should be able to access organisation {$organisation->slug}");
    }

    /** @test */
    public function user_membership_check_query_returns_correct_result()
    {
        // RED: Debug the membership check query
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $organisation = \App\Models\Organisation::find($user->organisation_id);

        // Manually create pivot
        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $user->organisation_id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test the membership query that OrganisationController uses
        $isMember = $organisation->users()
            ->where('users.id', $user->id)
            ->exists();

        $this->assertTrue($isMember, "User {$user->id} should be member of organisation {$organisation->id}");

        // Also test the reverse
        $allUsers = $organisation->users()->get();
        $this->assertGreaterThan(0, $allUsers->count(), 'Organisation should have at least one user');
    }

    /** @test */
    public function pivot_record_data_integrity_check()
    {
        // Check all columns in pivot record
        $user = User::factory()->create();

        // Simulate what RegisterController does
        $insertResult = DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $user->organisation_id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($insertResult, 'Insert should succeed');

        // Verify all columns are correct
        $pivot = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($pivot);
        $this->assertEquals($user->id, $pivot->user_id);
        $this->assertEquals($user->organisation_id, $pivot->organisation_id);
        $this->assertEquals('member', $pivot->role);
        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);
    }
}
