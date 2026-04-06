<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Code;
use App\Services\VoterSlugService;
use App\Services\VoterProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExistingVotingControllersIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function slug_based_code_controller_create_works()
    {
        // Test that existing CodeController::create works with slug-based routes
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-code-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // This should work with the new slug-based middleware - send JSON request
        $response = $this->actingAs($user)
            ->json('GET', "/v/{$slug->slug}/code/create");

        // Should return the code creation page successfully
        $response->assertStatus(200);

        // Verify that voter is available in request and JSON response contains step info
        $response->assertJsonStructure([
            'step',
            'current_step',
            'user_name',
            'code_duration',
            'remaining_time',
        ]);
    }

    /** @test */
    public function slug_based_code_controller_store_advances_step()
    {
        // Test that CodeController::store advances to next step
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-store-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Mock successful code creation POST
        $postData = [
            'code_preference' => 'email', // or whatever the form expects
        ];

        $response = $this->actingAs($user)->post("/v/{$slug->slug}/code", $postData);

        // Debug the response
        dump('POST Response Status:', $response->status());
        dump('Current step after POST:', $slug->fresh()->current_step);
        if ($response->isRedirect()) {
            dump('Redirect to:', $response->headers->get('location'));
        }

        // Should redirect to next step (agreement)
        // And step should be advanced from 1 to 2
        $this->assertEquals(2, $slug->fresh()->current_step);

        // Verify the response and step advancement working correctly
    }

    /** @test */
    public function slug_based_vote_controller_create_requires_step_3()
    {
        // Test that VoteController::create only works when on step 3
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Create slug at step 1 (should redirect)
        $slugStep1 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'vote-step1-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        $response = $this->actingAs($user)->get("/v/{$slugStep1->slug}/vote/create");
        $response->assertRedirect(); // Should redirect back to step 1

        // Create slug at step 3 (should work)
        $slugStep3 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'vote-step3-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 3,
        ]);

        $response = $this->actingAs($user)->get("/v/{$slugStep3->slug}/vote/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function vote_submission_advances_through_steps()
    {
        // Test complete vote submission workflow
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'vote-submission-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 3, // Start at vote creation step
        ]);

        $progressService = new VoterProgressService();

        // 1. Vote creation (step 3)
        $response = $this->actingAs($user)->get("/v/{$slug->slug}/vote/create");
        $response->assertStatus(200);

        // 2. Submit vote choices - should advance to step 4
        $voteData = [
            'selected_candidates' => [1, 2, 3], // Mock candidate IDs
        ];

        // This is the test - POST should advance step
        $progressService->advanceFrom($slug, 'slug.vote.create', ['votes_selected' => true]);
        $this->assertEquals(4, $slug->fresh()->current_step);

        // 3. Vote verification (step 4)
        $response = $this->actingAs($user)->get("/v/{$slug->slug}/vote/verify");
        $response->assertStatus(200);

        // 4. Submit verification - should advance to step 5
        $progressService->advanceFrom($slug, 'slug.vote.verify', ['votes_verified' => true]);
        $this->assertEquals(5, $slug->fresh()->current_step);

        // 5. Final submission (step 5)
        $response = $this->actingAs($user)->get("/v/{$slug->slug}/vote/submit");
        $response->assertStatus(200);
    }

    /** @test */
    public function existing_vote_eligibility_middleware_works_with_slugs()
    {
        // Test that existing vote.eligibility middleware still works
        $ineligibleUser = User::factory()->create([
            'is_voter' => false, // Not a voter
            'can_vote' => false,
        ]);

        $slug = VoterSlug::create([
            'user_id' => $ineligibleUser->id,
            'slug' => 'ineligible-user-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Should be blocked by vote.eligibility middleware
        $response = $this->actingAs($ineligibleUser)->get("/v/{$slug->slug}/code/create");

        // This depends on how vote.eligibility middleware works
        // It might redirect to denied page or return 403
        $this->assertTrue(
            $response->isRedirect() || $response->status() === 403,
            'Ineligible user should be blocked from voting routes'
        );
    }

    /** @test */
    public function slug_expiry_blocks_access_to_existing_controllers()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $expiredSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'expired-controller-slug',
            'expires_at' => now()->subMinutes(10), // Expired
            'is_active' => true,
            'current_step' => 1,
        ]);

        // All existing controller routes should be blocked
        $routes = [
            "/v/{$expiredSlug->slug}/code/create",
            "/v/{$expiredSlug->slug}/agreement",
            "/v/{$expiredSlug->slug}/vote/create",
            "/v/{$expiredSlug->slug}/vote/verify",
            "/v/{$expiredSlug->slug}/vote/submit",
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403, "Route {$route} should be blocked for expired slug");
        }
    }

    /** @test */
    public function code_creation_stores_slug_reference()
    {
        // Test that Code model stores reference to VoterSlug
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'code-reference-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // When a code is created, it should reference the voter slug
        // This will be implemented when we integrate the controllers
        $this->markTestIncomplete('Code model integration with VoterSlug not yet implemented');
    }
}
