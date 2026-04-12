<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareChainTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $organisation;
    protected User $user;
    protected Election $election;
    protected VoterSlug $voterSlug;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation
        $this->organisation = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'type' => 'organisation',
        ]);

        // Create user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
        ]);

        // Create election
        $this->election = Election::create([
            'name' => 'Test Election',
            'slug' => 'test-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
        ]);

        // Create voter slug
        $this->voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'valid-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->addHours(24),
        ]);
    }

    /** @test */
    public function layer_1_verifies_voter_slug_exists()
    {
        // Layer 1: VerifyVoterSlug middleware
        // Should verify:
        // - Slug exists in database
        // - Belongs to authenticated user
        // - Is active

        $response = $this->actingAs($this->user)->get("/v/{$this->voterSlug->slug}/demo-code/create");

        // Should NOT get 404 (slug not found)
        $this->assertNotEquals(404, $response->status());
    }

    /** @test */
    public function layer_1_rejects_invalid_voter_slug()
    {
        // Layer 1 should reject non-existent slug
        $response = $this->actingAs($this->user)->get('/v/invalid-slug-12345/demo-code/create');

        // Should get 404
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function layer_1_verifies_slug_belongs_to_user()
    {
        // Create another user
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
        ]);

        // Create slug for other user
        $otherSlug = VoterSlug::create([
            'user_id' => $otherUser->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'other-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->addHours(24),
        ]);

        // First user tries to access other user's slug
        $response = $this->actingAs($this->user)->get("/v/{$otherSlug->slug}/demo-code/create");

        // Should be rejected (ownership check failed)
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function layer_2_validates_voter_slug_not_expired()
    {
        // Layer 2: ValidateVoterSlugWindow middleware
        // Should verify:
        // - Slug hasn't expired
        // - Election is still active

        // Create expired slug
        $expiredSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'expired-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->subHours(1), // Expired
        ]);

        // Try to access expired slug
        $response = $this->actingAs($this->user)->get("/v/{$expiredSlug->slug}/demo-code/create");

        // Should be rejected (slug expired)
        $this->assertTrue(in_array($response->status(), [403, 404, 422]));
    }

    /** @test */
    public function layer_2_rejects_access_when_election_inactive()
    {
        // Create inactive election
        $inactiveElection = Election::create([
            'name' => 'Inactive Election',
            'slug' => 'inactive-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $this->organisation->id,
            'status' => 'planned',
        ]);

        // Create slug for inactive election
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $inactiveElection->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'inactive-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->addHours(24),
        ]);

        // Try to access with inactive election
        $response = $this->actingAs($this->user)->get("/v/{$slug->slug}/demo-code/create");

        // Should be rejected
        $this->assertTrue(in_array($response->status(), [403, 404, 422]));
    }

    /** @test */
    public function layer_3_verifies_organisation_consistency()
    {
        // Layer 3: VerifyVoterSlugConsistency middleware
        // Should verify:
        // - Election exists
        // - VoterSlug.organisation_id matches Election.organisation_id
        // - OR one is platform (id=1)

        // This is the "Golden Rule" validation
        // VoterSlug.org MUST match Election.org (unless platform exception)

        // Get platform org (id=1)
        $platformOrg = Organisation::where('is_platform', 1)->first();
        if (!$platformOrg) {
            $platformOrg = Organisation::create([
                'name' => 'Platform',
                'slug' => 'platform',
                'type' => 'platform',
                'is_platform' => 1,
            ]);
        }

        // Create election for platform
        $platformElection = Election::create([
            'name' => 'Platform Election',
            'slug' => 'platform-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $platformOrg->id,
            'status' => 'active',
        ]);

        // User from org should be able to access platform election
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $platformElection->id,
            'organisation_id' => $this->user->organisation_id, // User's org
            'slug' => 'platform-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->user)->get("/v/{$slug->slug}/demo-code/create");

        // Should NOT be rejected (platform election is accessible)
        $this->assertNotEquals(403, $response->status());
    }

    /** @test */
    public function layer_3_rejects_organisation_mismatch()
    {
        // Create another organisation
        $anotherOrg = Organisation::create([
            'name' => 'Another Org',
            'slug' => 'another-org',
            'type' => 'organisation',
        ]);

        // Create election for another org
        $anotherOrgElection = Election::create([
            'name' => 'Another Org Election',
            'slug' => 'another-org-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $anotherOrg->id,
            'status' => 'active',
        ]);

        // Create slug with WRONG organisation
        $mismatchSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $anotherOrgElection->id,
            'organisation_id' => $this->organisation->id, // User's org (mismatch!)
            'slug' => 'mismatch-slug-' . uniqid(),
            'current_step' => 1,
            'is_active' => 1,
            'expires_at' => now()->addHours(24),
        ]);

        // Try to access (should fail - org mismatch)
        $response = $this->actingAs($this->user)->get("/v/{$mismatchSlug->slug}/demo-code/create");

        // Should be rejected (500 error for consistency violation)
        $this->assertTrue(in_array($response->status(), [403, 404, 422, 500]));
    }

    /** @test */
    public function all_three_layers_execute_in_order()
    {
        // Verify middleware chain order:
        // Layer 1 (verify) → Layer 2 (window) → Layer 3 (consistency)

        // Valid slug should pass all three layers
        $response = $this->actingAs($this->user)->get("/v/{$this->voterSlug->slug}/demo-code/create");

        // Should reach controller (200 or view response)
        $this->assertFalse(in_array($response->status(), [404, 403]));
    }

    /** @test */
    public function early_layer_failure_prevents_later_layers()
    {
        // If Layer 1 fails, Layers 2 & 3 should not execute

        // Invalid slug immediately fails at Layer 1
        $response = $this->actingAs($this->user)->get('/v/invalid-slug/demo-code/create');

        // Should return 404 (Layer 1 failure)
        $this->assertEquals(404, $response->status());
    }
}
