<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoCode;
use App\Models\DemoVote;
use App\Models\DemoResult;
use App\Models\DemoCandidacy;
use App\Models\DemoPost;

class DemoMirrorSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test demo code flow mirrors real code flow
     * Verifies: CREATE -> VERIFY -> AGREEMENT -> VOTE flow
     */
    public function test_demo_code_creation_page_accessible()
    {
        $user = User::factory()->create(['can_vote' => 0]); // Note: can_vote=0
        $election = Election::factory()->create(['type' => 'demo']);

        $response = $this->actingAs($user)->get('/demo/code/create');

        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Code/DemoCode/Create')
                ->where('election_type', 'demo')
            );
    }

    /**
     * Test demo code is created on first access
     */
    public function test_demo_code_created_on_first_access()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        $this->assertDatabaseHas('demo_codes', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'can_vote_now' => 0,
        ]);
    }

    /**
     * Test demo code verification succeeds with correct code
     */
    public function test_demo_code_verification_succeeds()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        $response = $this->actingAs($user)
            ->post('/demo/codes', ['voting_code' => $code->code_to_open_voting_form]);

        $response->assertRedirect(route('demo-code.agreement'));

        $freshCode = $code->fresh();
        $this->assertTrue($freshCode->can_vote_now);
        $this->assertFalse($freshCode->is_code_to_open_voting_form_usable);
    }

    /**
     * Test demo code verification fails with wrong code
     */
    public function test_demo_code_verification_fails_with_wrong_code()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        $response = $this->actingAs($user)
            ->post('/demo/codes', ['voting_code' => 'WRONG1']);

        $response->assertRedirect()
            ->assertSessionHasErrors('voting_code');
    }

    /**
     * Test demo agreement acceptance
     */
    public function test_demo_agreement_acceptance()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        // First complete code verification
        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');
        $code = DemoCode::where('user_id', $user->id)->first();
        $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code_to_open_voting_form]);

        // Now test agreement
        $response = $this->actingAs($user)
            ->post('/demo/code/agreement', ['agreement' => true]);

        $response->assertRedirect(route('demo-vote.create'));

        $freshCode = $code->fresh();
        $this->assertEquals(1, $freshCode->has_agreed_to_vote);
    }

    /**
     * Test demo allows re-voting (key difference from real elections)
     */
    public function test_demo_allows_revoting()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        // First complete full voting flow
        session(['selected_election_type' => 'demo']);

        // Create code
        $this->actingAs($user)->get('/demo/code/create');
        $code = DemoCode::where('user_id', $user->id)->first();
        $this->assertNotNull($code);

        // Verify code
        $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code_to_open_voting_form]);

        // Accept agreement
        $this->actingAs($user)->post('/demo/code/agreement', ['agreement' => true]);

        // Now mark as voted
        $code->update(['has_voted' => true]);

        // Second attempt - accessing code page again should reset
        $response = $this->actingAs($user)->get('/demo/code/create');

        $freshCode = $code->fresh();
        $this->assertFalse($freshCode->can_vote_now, 'Code should be reset for re-voting');
        $this->assertFalse($freshCode->has_voted, 'has_voted should be reset');
    }

    /**
     * Test demo allows users without can_vote permission
     * Key difference: Real elections require can_vote=1, demo doesn't
     */
    public function test_demo_allows_users_without_can_vote_permission()
    {
        $user = User::factory()->create(['can_vote' => 0]);
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);

        // Demo should allow access
        $response = $this->actingAs($user)->get('/demo/code/create');
        $response->assertStatus(200);

        // Verify code is created
        $this->assertDatabaseHas('demo_codes', ['user_id' => $user->id]);
    }

    /**
     * Test demo uses demo models, not real models
     */
    public function test_demo_uses_demo_models_not_real_models()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        // Should have DemoCode, NOT Code
        $this->assertDatabaseHas('demo_codes', ['user_id' => $user->id]);

        // Verify Code table is empty for this user
        $this->assertDatabaseMissing('codes', ['user_id' => $user->id]);
    }

    /**
     * Test demo mode indicator in UI
     */
    public function test_demo_mode_indicator_in_create_page()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $response = $this->actingAs($user)->get('/demo/code/create');

        $response->assertInertia(fn ($page) => $page
            ->component('Code/DemoCode/Create')
            ->where('election_type', 'demo')
        );
    }

    /**
     * Test demo code expiration and resend
     */
    public function test_demo_code_expiration_resends_new_code()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        $code = DemoCode::where('user_id', $user->id)->first();
        $originalCode = $code->code_to_open_voting_form;

        // Simulate code expiration
        $code->update(['code_to_open_voting_form_sent_at' => now()->subMinutes(31)]);

        // Access code page again
        $this->actingAs($user)->get('/demo/code/create');

        $freshCode = $code->fresh();
        $this->assertNotEquals($originalCode, $freshCode->code_to_open_voting_form, 'Code should be regenerated');
        $this->assertFalse($freshCode->can_vote_now, 'Code should be reset for re-entry');
    }

    /**
     * Test demo prevents double-submission of same code
     */
    public function test_demo_prevents_code_reuse_after_verification()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);
        $this->actingAs($user)->get('/demo/code/create');

        $code = DemoCode::where('user_id', $user->id)->first();
        $codeValue = $code->code_to_open_voting_form;

        // First submission
        $this->actingAs($user)->post('/demo/codes', ['voting_code' => $codeValue]);

        // Attempt second submission of same code
        $response = $this->actingAs($user)
            ->post('/demo/codes', ['voting_code' => $codeValue]);

        $response->assertRedirect(route('demo-code.agreement'));

        $freshCode = $code->fresh();
        $this->assertFalse($freshCode->is_code_to_open_voting_form_usable, 'Code should not be reusable');
    }

    /**
     * Test demo has no IP rate limiting (unlike real elections)
     */
    public function test_demo_has_no_ip_rate_limiting()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);

        // Create multiple demo codes/votes from same IP
        // This should succeed (no IP limiting)
        for ($i = 0; $i < 8; $i++) {
            $this->actingAs($user)->get('/demo/code/create');
            $code = DemoCode::where('user_id', $user->id)->first();

            $response = $this->actingAs($user)
                ->post('/demo/codes', ['voting_code' => $code->code_to_open_voting_form]);

            $this->assertTrue(
                $response->status() === 302 || $response->status() === 200,
                "Attempt $i should succeed (no IP limiting)"
            );
        }
    }

    /**
     * Test demo code and vote are in separate demo tables
     */
    public function test_demo_data_isolated_to_demo_tables()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);

        // Create code
        $this->actingAs($user)->get('/demo/code/create');

        // Verify it's in demo_codes, not codes
        $this->assertDatabaseHas('demo_codes', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('codes', ['user_id' => $user->id]);
    }

    /**
     * Test real elections still enforce can_vote check
     * Verification that real elections are NOT affected by demo changes
     */
    public function test_real_elections_still_enforce_can_vote()
    {
        $user = User::factory()->create(['can_vote' => 0]); // No voting permission
        $election = Election::factory()->create(['type' => 'real']);

        // Real election should block access
        $response = $this->actingAs($user)->get('/code/create');

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test demo and real elections use separate code tables
     */
    public function test_demo_and_real_elections_use_separate_tables()
    {
        $user = User::factory()->create(['can_vote' => 1]);
        $demoElection = Election::factory()->create(['type' => 'demo']);
        $realElection = Election::factory()->create(['type' => 'real']);

        session(['selected_election_type' => 'demo']);

        // Access demo code
        $this->actingAs($user)->get('/demo/code/create');

        // Should create demo_code, not real code
        $this->assertDatabaseHas('demo_codes', [
            'user_id' => $user->id,
            'election_id' => $demoElection->id,
        ]);

        $this->assertDatabaseMissing('codes', [
            'user_id' => $user->id,
            'election_id' => $demoElection->id,
        ]);
    }

    /**
     * Test demo code agreement page is accessible
     */
    public function test_demo_agreement_page_accessible()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);

        // Complete code verification first
        $this->actingAs($user)->get('/demo/code/create');
        $code = DemoCode::where('user_id', $user->id)->first();
        $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code_to_open_voting_form]);

        // Access agreement page
        $response = $this->actingAs($user)->get('/demo/code/agreement');

        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Code/DemoCode/Agreement')
                ->where('is_demo', true)
            );
    }

    /**
     * Test demo agreement page blocks unverified users
     */
    public function test_demo_agreement_page_blocks_unverified()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        session(['selected_election_type' => 'demo']);

        // Try to access agreement without verifying code
        $response = $this->actingAs($user)->get('/demo/code/agreement');

        $response->assertRedirect(route('demo-code.create'));
    }
}
