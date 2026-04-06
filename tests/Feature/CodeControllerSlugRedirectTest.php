<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Code;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodeControllerSlugRedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function code_submission_redirects_to_slug_based_agreement_url()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create a voting slug
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-redirect-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Create a code record for the user
        $code = Code::create([
            'user_id' => $user->id,
            'code1' => '123456',
            'is_code1_usable' => true,
            'has_code1_sent' => true,
            'code1_sent_at' => now()->subMinutes(5),
            'client_ip' => '127.0.0.1',
        ]);

        // Submit the code through the slug-based route
        $response = $this->actingAs($user)
            ->post("/v/{$slug->slug}/code", [
                'voting_code' => '123456'
            ]);

        // Should redirect to slug-based agreement URL
        $response->assertStatus(302);
        $response->assertRedirect("/v/{$slug->slug}/vote/agreement");
        $response->assertSessionHas('success');
    }

    /** @test */
    public function code_submission_without_slug_redirects_to_regular_agreement_url()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create a code record for the user
        $code = Code::create([
            'user_id' => $user->id,
            'code1' => '123456',
            'is_code1_usable' => true,
            'has_code1_sent' => true,
            'code1_sent_at' => now()->subMinutes(5),
            'client_ip' => '127.0.0.1',
        ]);

        // Submit the code through the regular route
        $response = $this->actingAs($user)
            ->post("/codes", [
                'voting_code' => '123456'
            ]);

        // Should redirect to regular agreement URL
        $response->assertStatus(302);
        $response->assertRedirect("/vote/agreement");
        $response->assertSessionHas('success');
    }

    /** @test */
    public function invalid_code_redirects_to_slug_based_create_url()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create a voting slug
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-invalid-code-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Create a code record with used code
        $code = Code::create([
            'user_id' => $user->id,
            'code1' => '123456',
            'is_code1_usable' => false, // Not usable
            'has_code1_sent' => true,
            'code1_sent_at' => now()->subMinutes(5),
            'client_ip' => '127.0.0.1',
        ]);

        // Submit an invalid code through the slug-based route
        $response = $this->actingAs($user)
            ->post("/v/{$slug->slug}/code", [
                'voting_code' => '123456'
            ]);

        // Should redirect back to slug-based code creation
        $response->assertStatus(302);
        $response->assertRedirect("/v/{$slug->slug}/code/create");
        $response->assertSessionHas('error');
    }

    /** @test */
    public function user_with_existing_voting_session_redirects_to_slug_based_agreement()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false
        ]);

        // Create a voting slug
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-existing-session-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2, // Already at agreement step
        ]);

        // Create a code record with active voting session
        $code = Code::create([
            'user_id' => $user->id,
            'code1' => '123456',
            'is_code1_usable' => true,
            'has_code1_sent' => true,
            'code1_sent_at' => now()->subMinutes(5),
            'can_vote_now' => 1, // Has active session
            'client_ip' => '127.0.0.1',
        ]);

        // Submit the code through the slug-based route
        $response = $this->actingAs($user)
            ->post("/v/{$slug->slug}/code", [
                'voting_code' => '123456'
            ]);

        // Should redirect to slug-based agreement URL
        $response->assertStatus(302);
        $response->assertRedirect("/v/{$slug->slug}/vote/agreement");
        $response->assertSessionHas('info');
    }
}