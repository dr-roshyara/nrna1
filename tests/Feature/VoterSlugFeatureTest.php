<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class VoterSlugFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function user_can_generate_voting_slug()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_voter' => true,
            'can_vote' => true
        ]);

        $response = $this->actingAs($user)->get('/test/generate-slug');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'slug',
                    'expires_at',
                    'test_link'
                ]);

        $this->assertDatabaseHas('voter_slugs', [
            'user_id' => $user->id,
            'is_active' => true
        ]);
    }

    /** @test */
    public function valid_slug_allows_access_to_protected_route()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'is_voter' => true,
            'can_vote' => true
        ]);
        $slugService = new VoterSlugService();
        $voterSlug = $slugService->generateSlugForUser($user);

        $response = $this->get("/v/{$voterSlug->slug}/test");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Voter slug validation working!',
                    'voter_id' => $user->id,
                    'voter_name' => $user->name
                ]);
    }

    /** @test */
    public function invalid_slug_returns_403()
    {
        $response = $this->get("/v/invalid-slug/test");

        $response->assertStatus(403);
    }

    /** @test */
    public function expired_slug_returns_403()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test3@example.com'
        ]);

        $expiredSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'expired-slug',
            'expires_at' => now()->subMinutes(10), // Expired 10 minutes ago
            'is_active' => true
        ]);

        $response = $this->get("/v/{$expiredSlug->slug}/test");

        $response->assertStatus(403);
    }

    /** @test */
    public function inactive_slug_returns_403()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test4@example.com'
        ]);

        $inactiveSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'inactive-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => false // Inactive
        ]);

        $response = $this->get("/v/{$inactiveSlug->slug}/test");

        $response->assertStatus(403);
    }

    /** @test */
    public function generating_new_slug_revokes_previous_active_slugs()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test5@example.com'
        ]);
        $slugService = new VoterSlugService();

        // Generate first slug
        $firstSlug = $slugService->generateSlugForUser($user);
        $this->assertTrue($firstSlug->fresh()->is_active);

        // Generate second slug
        $secondSlug = $slugService->generateSlugForUser($user);

        $this->assertTrue($secondSlug->is_active);
        $this->assertFalse($firstSlug->fresh()->is_active);
    }

    /** @test */
    public function middleware_makes_voter_available_in_request()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test6@example.com'
        ]);
        $slugService = new VoterSlugService();
        $voterSlug = $slugService->generateSlugForUser($user);

        $response = $this->get("/v/{$voterSlug->slug}/test");

        $responseData = $response->json();
        $this->assertEquals($user->id, $responseData['voter_id']);
        $this->assertEquals($user->name, $responseData['voter_name']);
    }

    /** @test */
    public function unauthenticated_user_cannot_generate_slug()
    {
        $response = $this->get('/test/generate-slug');

        $response->assertRedirect('/login');
    }
}
