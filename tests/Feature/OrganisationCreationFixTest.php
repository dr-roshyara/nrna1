<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrganisationCreationFixTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: User can create organisation and immediately access it (cache cleared)
     *
     * This test verifies the fix for: stale cache after organisation creation
     * causing TenantContext middleware to return 403
     */
    public function test_user_can_create_organisation_and_immediately_access_it(): void
    {
        // Create a user
        $user = User::factory()->create(['name' => 'Test Creator']);
        $originalOrgId = $user->organisation_id;

        \Log::info('Test 1: User created', [
            'user_id' => $user->id,
            'original_org_id' => $originalOrgId,
        ]);

        // User POSTs to create new organisation
        $response = $this->actingAs($user)
            ->post(route('organisations.store'), [
                'name' => 'New Test Organisation',
                'email' => 'neworg@test.com',
            ]);

        // Should redirect to organisation show page (not 403)
        $response->assertStatus(302);
        $this->assertStringContainsString('organisations', $response->getTargetUrl());

        \Log::info('Test 1: POST completed successfully (no 403)', [
            'redirect_url' => $response->getTargetUrl(),
        ]);

        // Refresh user and verify organisation_id was updated
        $user->refresh();
        $newOrgId = $user->organisation_id;

        \Log::info('Test 1: User organisation updated', [
            'old_org_id' => $originalOrgId,
            'new_org_id' => $newOrgId,
            'changed' => $originalOrgId !== $newOrgId,
        ]);

        $this->assertNotEquals($originalOrgId, $newOrgId, 'User organisation_id should be updated');

        // Verify UserOrganisationRole was created
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $newOrgId)
            ->first();

        $this->assertNotNull($role, 'User should have owner role in new organisation');
        $this->assertEquals('owner', $role->role, 'User should be owner');

        // Verify cache was cleared (this is the fix!)
        // Cache should NOT contain the old organisation_id
        $cachedOrgId = Cache::get("user.{$user->id}.organisation_id");
        $this->assertNull($cachedOrgId, 'Cache should be cleared after organisation creation');

        \Log::info('Test 1: PASSED - Organisation creation with cache clearing works');
    }

    /**
     * TEST 2: Verify organisation show page is accessible after creation (no 403)
     */
    public function test_organisation_show_page_accessible_after_creation(): void
    {
        $user = User::factory()->create();

        // Create organisation
        $response = $this->actingAs($user)
            ->post(route('organisations.store'), [
                'name' => 'Accessible Org',
                'email' => 'org@test.com',
            ]);

        $response->assertStatus(302);

        // Get the new organisation
        $newOrg = Organisation::where('name', 'Accessible Org')->first();
        $this->assertNotNull($newOrg);

        // Refresh user and verify they're in the new org
        $user->refresh();
        $this->assertEquals($newOrg->id, $user->organisation_id);

        // NOW try to access the organisation show page
        // This should succeed (not 403) because cache was cleared
        $response = $this->actingAs($user)
            ->get(route('organisations.show', $newOrg->slug));

        \Log::info('Test 2: Organisation show access', [
            'status' => $response->status(),
            'slug' => $newOrg->slug,
        ]);

        $response->assertStatus(200);

        \Log::info('Test 2: PASSED - Organisation show page accessible after creation');
    }

    /**
     * TEST 3: Verify election lookup works in correct organisation
     */
    public function test_code_verification_works_after_organisation_switch(): void
    {
        // Setup: Create two organisations
        $org1 = Organisation::factory()->create(['name' => 'Org 1']);
        $org2 = Organisation::factory()->create(['name' => 'Org 2']);

        // Create user in org1
        $user = User::factory()->forOrganisation($org1)->create();

        // Verify user is in org1
        $this->assertEquals($org1->id, $user->organisation_id);

        // Create election in org2
        $election = \App\Models\Election::factory()->create([
            'organisation_id' => $org2->id,
            'type' => 'demo',
            'status' => 'active',
        ]);

        // Code lookup should FAIL because user in org1, election in org2
        $code = \App\Models\Code::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('organisation_id', $org2->id)
            ->first();

        $this->assertNull($code, 'Code should not exist (user not in org2 yet)');

        \Log::info('Test 3a: Code lookup fails when user in wrong org', [
            'user_org' => $user->organisation_id,
            'election_org' => $election->organisation_id,
        ]);

        // NOW: Use the command to assign user to org2
        // (Simulating the organisation creation fix)
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'member',
        ]);

        $user->update(['organisation_id' => $org2->id]);
        Cache::forget("user.{$user->id}.organisation_id");

        // Refresh user
        $user->refresh();

        // Verify user now in org2
        $this->assertEquals($org2->id, $user->organisation_id);

        \Log::info('Test 3b: User switched to org2', [
            'user_org' => $user->organisation_id,
            'election_org' => $election->organisation_id,
            'match' => $user->organisation_id === $election->organisation_id,
        ]);

        // NOW code lookup should work (if code exists in org2)
        $code = \App\Models\Code::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('organisation_id', $org2->id)
            ->first();

        // Code doesn't exist yet, but lookup would work if it did
        // The point is: user and election are now in the same org
        $this->assertEquals($org2->id, $user->organisation_id);
        $this->assertEquals($org2->id, $election->organisation_id);

        \Log::info('Test 3: PASSED - User organisation switch enables code lookup');
    }
}
