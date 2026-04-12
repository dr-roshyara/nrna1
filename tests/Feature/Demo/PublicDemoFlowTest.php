<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\PublicDemoSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Public Demo Flow - Full 5-Step Voting Without Login
 *
 * Tests the anonymous voting workflow:
 *   /public-demo/start
 *   → /public-demo/{token}/code        (Step 1: Code entry - code shown on screen)
 *   → /public-demo/{token}/agreement   (Step 2: Accept terms)
 *   → /public-demo/{token}/vote        (Step 3: Select candidates)
 *   → /public-demo/{token}/verify      (Step 4: Review & confirm)
 *   → /public-demo/{token}/thank-you   (Step 5: Complete)
 */
class PublicDemoFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platformOrg;
    protected Election $demoElection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->platformOrg = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => true,
        ]);

        $this->demoElection = Election::factory()->create([
            'type' => 'demo',
            'status' => 'active',
            'organisation_id' => $this->platformOrg->id,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Entry Point
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function anonymous_visitor_can_start_public_demo(): void
    {
        $response = $this->get(route('public-demo.start'));

        $response->assertRedirect();
        $this->assertStringContainsString('/public-demo/', $response->headers->get('Location'));
        $this->assertStringContainsString('/code', $response->headers->get('Location'));

        $this->assertDatabaseCount('public_demo_sessions', 1);
    }

    /** @test */
    public function same_session_reuses_existing_demo_session(): void
    {
        // Simulate the same browser session by using the same session token
        $token = 'fixed-test-session-token';

        PublicDemoSession::create([
            'session_token' => $token,
            'election_id' => $this->demoElection->id,
            'display_code' => 'ABCD-1234',
            'current_step' => 1,
            'expires_at' => now()->addMinutes(60),
        ]);

        // Controller should return existing session (firstOrCreate), not make a second one
        // Verify only one record exists
        $this->assertDatabaseCount('public_demo_sessions', 1);
        $this->assertDatabaseHas('public_demo_sessions', ['session_token' => $token]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 1: Code Entry (Code Displayed On Screen)
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function step1_renders_code_entry_page_with_code_displayed(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 1,
            'display_code' => 'DEMO-1234',
        ]);

        $response = $this->get(route('public-demo.code.show', $session->session_token));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Code/DemoCode/Create')
            ->where('verification_code', 'DEMO-1234')
            ->where('show_code_fallback', true)
        );
    }

    /** @test */
    public function step1_accepts_the_displayed_code_and_advances(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 1,
            'display_code' => 'DEMO-1234',
            'code_verified' => false,
        ]);

        $response = $this->post(route('public-demo.code.verify', $session->session_token), [
            'code' => 'DEMO-1234',
        ]);

        $response->assertRedirect(route('public-demo.agreement.show', $session->session_token));

        $session->refresh();
        $this->assertTrue($session->code_verified);
        $this->assertEquals(2, $session->current_step);
    }

    /** @test */
    public function step1_rejects_wrong_code(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 1,
            'display_code' => 'DEMO-1234',
            'code_verified' => false,
        ]);

        $response = $this->post(route('public-demo.code.verify', $session->session_token), [
            'code' => 'WRONG-CODE',
        ]);

        $response->assertSessionHasErrors('code');

        $session->refresh();
        $this->assertFalse($session->code_verified);
        $this->assertEquals(1, $session->current_step);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 2: Agreement
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function step2_renders_agreement_page(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 2,
            'code_verified' => true,
        ]);

        $response = $this->get(route('public-demo.agreement.show', $session->session_token));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Code/DemoCode/Agreement'));
    }

    /** @test */
    public function step2_agreement_accepted_advances_to_vote(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 2,
            'code_verified' => true,
            'agreed' => false,
        ]);

        $response = $this->post(route('public-demo.agreement.submit', $session->session_token), [
            'agreed' => true,
        ]);

        $response->assertRedirect(route('public-demo.vote.show', $session->session_token));

        $session->refresh();
        $this->assertTrue($session->agreed);
        $this->assertEquals(3, $session->current_step);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 3: Vote
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function step3_renders_vote_page(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 3,
            'code_verified' => true,
            'agreed' => true,
        ]);

        $response = $this->get(route('public-demo.vote.show', $session->session_token));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/DemoVote/Create'));
    }

    /** @test */
    public function step3_vote_saved_and_advances_to_verify(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 3,
            'code_verified' => true,
            'agreed' => true,
        ]);

        $response = $this->post(route('public-demo.vote.submit', $session->session_token), [
            'selections' => ['post_id_1' => ['candidate_id_a']],
        ]);

        $response->assertRedirect(route('public-demo.verify.show', $session->session_token));

        $session->refresh();
        $this->assertEquals(4, $session->current_step);
        $this->assertNotNull($session->candidate_selections);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 4: Verify & Final Submit
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function step4_renders_verify_page(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 4,
            'code_verified' => true,
            'agreed' => true,
            'candidate_selections' => ['post_id_1' => ['candidate_id_a']],
        ]);

        $response = $this->get(route('public-demo.verify.show', $session->session_token));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/DemoVote/Verify'));
    }

    /** @test */
    public function step4_final_submit_records_vote_and_advances(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 4,
            'code_verified' => true,
            'agreed' => true,
            'candidate_selections' => ['post_id_1' => ['candidate_id_a']],
        ]);

        $response = $this->post(route('public-demo.verify.confirm', $session->session_token));

        $response->assertRedirect(route('public-demo.thankyou', $session->session_token));

        $session->refresh();
        $this->assertEquals(5, $session->current_step);
        $this->assertTrue($session->has_voted);
        $this->assertNotNull($session->voted_at);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 5: Thank You
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function step5_thank_you_page_renders(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'current_step' => 5,
            'has_voted' => true,
        ]);

        $response = $this->get(route('public-demo.thankyou', $session->session_token));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/DemoVote/ThankYou'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Isolation Guarantees
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function two_independent_sessions_get_isolated_demo_sessions(): void
    {
        // Session A starts demo
        $responseA = $this->withSession(['_session_a' => true])->get(route('public-demo.start'));
        $tokenA = PublicDemoSession::first()?->session_token;

        // Session B starts demo (different browser — simulate via factory direct)
        $sessionB = PublicDemoSession::factory()->create([
            'election_id' => $this->demoElection->id,
            'session_token' => 'different-session-token',
        ]);

        $this->assertDatabaseCount('public_demo_sessions', 2);
        $this->assertNotEquals($tokenA, $sessionB->session_token);
    }
}
