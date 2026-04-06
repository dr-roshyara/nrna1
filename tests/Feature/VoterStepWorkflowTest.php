<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterStepWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function createTestUser(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User',
            'email' => 'test' . rand(1000, 9999) . '@example.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ], $attributes));
    }

    private function createVoterSlugWithStep(User $user, int $step = 1): VoterSlug
    {
        return VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug-' . rand(1000, 9999),
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => $step,
        ]);
    }

    /** @test */
    public function voter_slug_has_default_step_1()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slugService = new VoterSlugService();

        $slug = $slugService->generateSlugForUser($user);

        $this->assertEquals(1, $slug->current_step);
    }

    /** @test */
    public function voter_can_access_current_step_route()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = $this->createVoterSlugWithStep($user, 1);

        // Should be able to access step 1 route
        $response = $this->get("/v/{$slug->slug}/code/create");
        $response->assertStatus(200)
                ->assertJson(['step' => 1, 'current_step' => 1]);
    }

    /** @test */
    public function voter_cannot_access_future_step_route()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = $this->createVoterSlugWithStep($user, 1);

        // Should NOT be able to access step 3 route when on step 1
        $response = $this->get("/v/{$slug->slug}/vote/create");
        $response->assertRedirect(); // Should redirect back to step 1

        // Follow redirect to verify it goes to correct step
        $followResponse = $this->get($response->headers->get('Location'));
        $followResponse->assertStatus(200)
                      ->assertJson(['step' => 1, 'current_step' => 1]);
    }

    /** @test */
    public function voter_can_access_previous_step_route()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = $this->createVoterSlugWithStep($user, 3);

        // Should be able to access step 1 route when on step 3
        $response = $this->get("/v/{$slug->slug}/code/create");
        $response->assertStatus(200)
                ->assertJson(['step' => 1, 'current_step' => 3]);
    }

    /** @test */
    public function step_progression_works_correctly()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = $this->createVoterSlugWithStep($user, 1);
        $progressService = new \App\Services\VoterProgressService();

        // Currently on step 1
        $this->assertEquals(1, $slug->current_step);

        // Complete step 1 (advance from voter.code.create)
        $progressService->advanceFrom($slug, 'voter.code.create', ['code_verified' => true]);

        // Should now be on step 2
        $refreshedSlug = $slug->fresh();
        $this->assertEquals(2, $refreshedSlug->current_step);
        $this->assertTrue($refreshedSlug->step_meta['code_verified']);
    }

    /** @test */
    public function step_meta_can_store_additional_data()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug-meta',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
            'step_meta' => ['code_verified' => true, 'agreement_accepted' => false],
        ]);

        $this->assertTrue($slug->step_meta['code_verified']);
        $this->assertFalse($slug->step_meta['agreement_accepted']);
    }

    /** @test */
    public function election_steps_config_is_accessible()
    {
        $steps = config('election_steps');

        $this->assertIsArray($steps);
        $this->assertEquals('voter.code.create', $steps[1]);
        $this->assertEquals('voter.vote.submit', $steps[5]);
    }
}
