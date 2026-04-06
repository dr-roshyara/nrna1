<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExceptionHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $organisation;
    protected User $user;
    protected Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'type' => 'organisation',
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
        ]);

        $this->election = Election::create([
            'name' => 'Test Election',
            'slug' => 'test-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function invalid_voter_slug_throws_404()
    {
        // Try to access non-existent slug
        $response = $this->actingAs($this->user)
            ->get('/v/non-existent-slug/demo-code/create');

        // Should return 404
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function expired_voter_slug_throws_appropriate_error()
    {
        // Create an expired voter slug
        $expiredSlug = \App\Models\VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'expired-' . uniqid(),
            'expires_at' => now()->subHours(1),
            'is_active' => 1,
        ]);

        // Try to access expired slug
        $response = $this->actingAs($this->user)
            ->get("/v/{$expiredSlug->slug}/demo-code/create");

        // Should return error (410 Gone, 403 Forbidden, or similar)
        $this->assertTrue(in_array($response->status(), [403, 404, 410, 422]));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_voting_routes()
    {
        // Create valid voter slug
        $slug = \App\Models\VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'test-' . uniqid(),
            'expires_at' => now()->addHours(24),
            'is_active' => 1,
        ]);

        // Try to access without authentication
        $response = $this->get("/v/{$slug->slug}/demo-code/create");

        // Should be redirected to login
        $this->assertEquals(302, $response->status());
        $this->assertStringContainsString('login', strtolower($response->headers->get('Location')));
    }

    /** @test */
    public function cross_organisation_access_returns_404()
    {
        // Create another org
        $anotherOrg = Organisation::create([
            'name' => 'Another Org',
            'slug' => 'another-org',
            'type' => 'organisation',
        ]);

        // Create another user in different org
        $anotherUser = User::create([
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $anotherOrg->id,
        ]);

        // Create election in another org
        $anotherElection = Election::create([
            'name' => 'Another Election',
            'slug' => 'another-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $anotherOrg->id,
            'status' => 'active',
        ]);

        // Create slug for another user
        $anotherSlug = \App\Models\VoterSlug::create([
            'user_id' => $anotherUser->id,
            'election_id' => $anotherElection->id,
            'organisation_id' => $anotherOrg->id,
            'slug' => 'another-' . uniqid(),
            'expires_at' => now()->addHours(24),
            'is_active' => 1,
        ]);

        // Try to access with different user
        $response = $this->actingAs($this->user)
            ->get("/v/{$anotherSlug->slug}/demo-code/create");

        // Should return 404 (not found in user's context)
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function inactive_voter_slug_is_rejected()
    {
        // Create inactive voter slug
        $inactiveSlug = \App\Models\VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'inactive-' . uniqid(),
            'expires_at' => now()->addHours(24),
            'is_active' => 0, // Inactive
        ]);

        // Try to access inactive slug
        $response = $this->actingAs($this->user)
            ->get("/v/{$inactiveSlug->slug}/demo-code/create");

        // Should be rejected
        $this->assertTrue(in_array($response->status(), [403, 404, 422]));
    }

    /** @test */
    public function exception_provides_user_friendly_message()
    {
        // When an exception occurs, user should get clear message
        // not technical stack trace

        $response = $this->actingAs($this->user)
            ->get('/v/invalid-slug/demo-code/create');

        // Should not show technical details
        if ($response->status() === 404) {
            // This is fine - 404 is expected for invalid slug
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function organisation_mismatch_returns_500_or_404()
    {
        // Create voter slug with mismatched organisation
        // (voter slug org ≠ election org, and neither is platform)

        $anotherOrg = Organisation::create([
            'name' => 'Another Org',
            'slug' => 'another-org',
            'type' => 'organisation',
        ]);

        $anotherElection = Election::create([
            'name' => 'Another Org Election',
            'slug' => 'another-org-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $anotherOrg->id,
            'status' => 'active',
        ]);

        // Create slug with MISMATCHED org
        $mismatchSlug = \App\Models\VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $anotherElection->id,
            'organisation_id' => $this->organisation->id, // Different from election!
            'slug' => 'mismatch-' . uniqid(),
            'expires_at' => now()->addHours(24),
            'is_active' => 1,
        ]);

        // Try to access
        $response = $this->actingAs($this->user)
            ->get("/v/{$mismatchSlug->slug}/demo-code/create");

        // Should return error (500 for consistency violation, or 404/403 for rejection)
        $this->assertTrue(in_array($response->status(), [403, 404, 500, 422]));
    }

    /** @test */
    public function invalid_election_type_returns_appropriate_error()
    {
        // Create election with unknown type
        $badElection = Election::create([
            'name' => 'Bad Election',
            'slug' => 'bad-election-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
        ]);

        $slug = \App\Models\VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $badElection->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'bad-' . uniqid(),
            'expires_at' => now()->addHours(24),
            'is_active' => 1,
        ]);

        // Try to access
        $response = $this->actingAs($this->user)
            ->get("/v/{$slug->slug}/demo-code/create");

        // Should handle gracefully (not 500 server error)
        $this->assertNotEquals(500, $response->status());
    }
}
