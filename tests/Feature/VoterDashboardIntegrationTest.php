<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterDashboardIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function voter_can_start_voting_from_dashboard()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Simulate clicking "Vote Here" from dashboard
        $response = $this->actingAs($user)
            ->get('/voter/start');

        // Should redirect to slug-based code creation
        $response->assertStatus(302);

        // Should have created a voting slug
        $this->assertDatabaseHas('voter_slugs', [
            'user_id' => $user->id,
            'is_active' => true,
            'current_step' => 1
        ]);

        // Get the created slug
        $slug = VoterSlug::where('user_id', $user->id)->first();
        $this->assertNotNull($slug);

        // Should redirect to the slug-based code creation URL
        $response->assertRedirect("/v/{$slug->slug}/code/create");
    }

    /** @test */
    public function voter_with_existing_slug_redirects_to_current_step()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create an existing slug at step 2
        $existingSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'existing-test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2,
        ]);

        // Try to start voting again
        $response = $this->actingAs($user)
            ->get('/voter/start');

        // Should redirect to step 2 (agreement) - note: URL is vote/agreement not code/agreement
        $response->assertRedirect("/v/{$existingSlug->slug}/vote/agreement");

        // Should not create a new slug
        $this->assertEquals(1, VoterSlug::where('user_id', $user->id)->count());
    }

    /** @test */
    public function ineligible_voter_cannot_start_voting()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => false, // Not allowed to vote
            'has_voted' => false
        ]);

        $response = $this->actingAs($user)
            ->get('/voter/start');

        // Should redirect back to dashboard with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Should not create any slug
        $this->assertDatabaseMissing('voter_slugs', [
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function voter_who_has_already_voted_cannot_start_new_voting()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => true // Already completed voting
        ]);

        $response = $this->actingAs($user)
            ->get('/voter/start');

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Should not create any slug
        $this->assertDatabaseMissing('voter_slugs', [
            'user_id' => $user->id
        ]);
    }
}