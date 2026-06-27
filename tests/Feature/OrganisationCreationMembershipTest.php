<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrganisationCreationMembershipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: User's organisation_id must update to new organisation ID after creation
     */
    public function test_user_organisation_id_updates_after_creating_organisation(): void
    {
        $user = User::factory()->create();
        $originalOrgId = $user->organisation_id;

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->post(route('organisations.store'), [
                'name' => 'Test Organisation',
                'email' => 'test@example.com',
            ]);

        $user->refresh();
        $newOrg = Organisation::where('name', 'Test Organisation')->first();

        // CRITICAL: User's organisation_id must equal new organisation's ID
        $this->assertEquals($newOrg->id, $user->organisation_id);
        $this->assertNotEquals($originalOrgId, $user->organisation_id);

        // Verify UserOrganisationRole exists
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $newOrg->id)
            ->first();
        $this->assertNotNull($role, 'User must have role in new organisation');
        $this->assertEquals('owner', $role->role);
    }

    /**
     * TEST 2: Cache must be cleared after organisation creation
     */
    public function test_cache_is_cleared_after_organisation_creation(): void
    {
        $user = User::factory()->create();

        // Set a cached value BEFORE organisation creation
        Cache::put("user.{$user->id}.organisation_id", 'cached-old-value', 60);

        // Verify cache was set
        $this->assertEquals('cached-old-value', Cache::get("user.{$user->id}.organisation_id"));

        // POST to create organisation (should clear cache inside controller)
        $response = $this->actingAs($user)
            ->withoutMiddleware()  // Bypass all middleware for this test
            ->post(route('organisations.store'), [
                'name' => 'Cache Test Org',
                'email' => 'cache@test.com',
            ]);

        // Verify POST was successful (302 redirect)
        $response->assertStatus(302);

        // After POST, cache should be cleared
        // Use cache() helper to ensure we're using the same store
        $cachedValue = cache("user.{$user->id}.organisation_id");
        $this->assertNull($cachedValue, 'Cache must be cleared after organisation creation');
    }

    /**
     * TEST 3: User should be able to access organisation show page immediately (no 403)
     */
    public function test_user_can_access_organisation_show_page_immediately(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->post(route('organisations.store'), [
                'name' => 'Access Test Org',
                'email' => 'access@test.com',
            ]);

        $org = Organisation::where('name', 'Access Test Org')->first();

        // Immediately try to access the organisation show page
        $showResponse = $this->actingAs($user)
            ->withoutMiddleware()
            ->get(route('organisations.show', $org->slug));

        $showResponse->assertStatus(200);  // Not 403
    }

    /**
     * TEST 4: Verify all three conditions together in one flow
     */
    public function test_complete_organisation_creation_flow(): void
    {
        $user = User::factory()->create();
        $originalOrgId = $user->organisation_id;

        // Step 1: Create organisation
        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->post(route('organisations.store'), [
                'name' => 'Complete Flow Org',
                'email' => 'complete@test.com',
            ]);

        $response->assertStatus(302);

        // Step 2: Verify user's organisation_id updated
        $user->refresh();
        $newOrg = Organisation::where('name', 'Complete Flow Org')->first();
        $this->assertEquals($newOrg->id, $user->organisation_id);

        // Step 3: Verify UserOrganisationRole created
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $newOrg->id)
            ->first();
        $this->assertNotNull($role);
        $this->assertEquals('owner', $role->role);

        // Step 4: Verify cache was cleared
        $cachedValue = Cache::get("user.{$user->id}.organisation_id");
        $this->assertNull($cachedValue);

        // Step 5: Verify user can access organisation show page (no 403)
        $showResponse = $this->actingAs($user)
            ->get(route('organisations.show', $newOrg->slug));
        $showResponse->assertStatus(200);
    }
}
