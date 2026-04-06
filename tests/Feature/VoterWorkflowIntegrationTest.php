<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use App\Services\VoterProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterWorkflowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_voter_workflow_with_step_enforcement()
    {
        // 1. Create a voter and generate slug
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slugService = new VoterSlugService();
        $progressService = new VoterProgressService();

        $slug = $slugService->generateSlugForUser($user);
        $this->assertEquals(1, $slug->current_step);

        // 2. Access step 1 - should work
        $response = $this->get("/v/{$slug->slug}/code/create");
        $response->assertStatus(200)
                ->assertJson(['step' => 1, 'current_step' => 1]);

        // 3. Try to skip to step 3 - should redirect to step 1
        $response = $this->get("/v/{$slug->slug}/vote/create");
        $response->assertRedirect();

        // 4. Complete step 1 and advance
        $progressService->advanceFrom($slug, 'voter.code.create', ['code_completed' => true]);
        $this->assertEquals(2, $slug->fresh()->current_step);

        // 5. Now can access step 2
        $response = $this->get("/v/{$slug->slug}/agreement");
        $response->assertStatus(200)
                ->assertJson(['step' => 2, 'current_step' => 2]);

        // 6. Complete step 2 and advance
        $progressService->advanceFrom($slug, 'voter.agreement', ['agreement_accepted' => true]);
        $this->assertEquals(3, $slug->fresh()->current_step);

        // 7. Continue through all steps
        $response = $this->get("/v/{$slug->slug}/vote/create");
        $response->assertStatus(200)
                ->assertJson(['step' => 3, 'current_step' => 3]);

        $progressService->advanceFrom($slug, 'voter.vote.create', ['votes_selected' => true]);
        $this->assertEquals(4, $slug->fresh()->current_step);

        $response = $this->get("/v/{$slug->slug}/vote/verify");
        $response->assertStatus(200);

        $progressService->advanceFrom($slug, 'voter.vote.verify', ['votes_verified' => true]);
        $this->assertEquals(5, $slug->fresh()->current_step);

        // 8. Final step
        $response = $this->get("/v/{$slug->slug}/vote/submit");
        $response->assertStatus(200)
                ->assertJson(['step' => 5, 'current_step' => 5]);
    }

    /** @test */
    public function voter_can_navigate_backwards()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 3,
        ]);

        // Can access step 1 when on step 3
        $response = $this->get("/v/{$slug->slug}/code/create");
        $response->assertStatus(200);

        // Can access step 2 when on step 3
        $response = $this->get("/v/{$slug->slug}/agreement");
        $response->assertStatus(200);

        // Can access current step 3
        $response = $this->get("/v/{$slug->slug}/vote/create");
        $response->assertStatus(200);

        // Cannot access future step 4
        $response = $this->get("/v/{$slug->slug}/vote/verify");
        $response->assertRedirect();
    }

    /** @test */
    public function expired_slug_blocks_access()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $expiredSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'expired-slug',
            'expires_at' => now()->subMinutes(10), // Expired
            'is_active' => true,
            'current_step' => 1,
        ]);

        $response = $this->get("/v/{$expiredSlug->slug}/code/create");
        $response->assertStatus(403);
    }

    /** @test */
    public function inactive_slug_blocks_access()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $inactiveSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'inactive-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => false, // Inactive
            'current_step' => 1,
        ]);

        $response = $this->get("/v/{$inactiveSlug->slug}/code/create");
        $response->assertStatus(403);
    }

    /** @test */
    public function non_step_routes_pass_through_middleware()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // The test route doesn't have step enforcement
        $response = $this->get("/v/{$slug->slug}/test");
        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function step_meta_persists_throughout_workflow()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'meta-test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        $progressService = new VoterProgressService();

        // Add meta at step 1
        $progressService->advanceFrom($slug, 'voter.code.create', ['code' => 'ABC123']);

        // Add more meta at step 2
        $progressService->advanceFrom($slug, 'voter.agreement', ['agreement_timestamp' => now()->toISOString()]);

        // Check that both pieces of meta persist
        $refreshedSlug = $slug->fresh();
        $this->assertEquals('ABC123', $refreshedSlug->step_meta['code']);
        $this->assertArrayHasKey('agreement_timestamp', $refreshedSlug->step_meta);
        $this->assertEquals(3, $refreshedSlug->current_step);
    }
}
