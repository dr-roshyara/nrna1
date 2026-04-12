<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Code;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RealWorldVotingFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_voting_flow_from_dashboard_uses_slug_based_urls()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Step 1: User clicks "Vote Here" on dashboard
        $response = $this->actingAs($user)
            ->get('/voter/start');

        // Should generate a slug and redirect to slug-based code creation
        $response->assertStatus(302);
        $this->assertTrue(str_contains($response->headers->get('location'), '/v/'));
        $this->assertTrue(str_contains($response->headers->get('location'), '/code/create'));

        // Get the created slug
        $slug = VoterSlug::where('user_id', $user->id)->first();
        $this->assertNotNull($slug);

        // Step 2: User is now on /v/{slug}/code/create - let's access it
        $codeCreateResponse = $this->actingAs($user)
            ->get("/v/{$slug->slug}/code/create");

        $codeCreateResponse->assertStatus(200);

        // This test verifies that the slug-based system is working
        // The redirect issue in the original problem should now be fixed
        // because all CodeController redirects now check for $voterSlug
    }

    /** @test */
    public function manual_test_of_code_submission_behavior()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create a voting slug using the service (like the real flow)
        $slugService = new VoterSlugService();
        $slug = $slugService->generateSlugForUser($user);

        // Access the code creation page to trigger code generation
        $response = $this->actingAs($user)
            ->get("/v/{$slug->slug}/code/create");

        $response->assertStatus(200);

        // The user would receive an email with a code, but for testing
        // we'll check if a Code record was created
        $code = Code::where('user_id', $user->id)->first();
        $this->assertNotNull($code, 'Code should be created when accessing code/create');

        // If there's a code, it should have the basic required fields
        if ($code) {
            $this->assertNotNull($code->code1);
            $this->assertTrue($code->is_code1_usable);
            $this->assertTrue($code->has_code1_sent);
        }
    }
}