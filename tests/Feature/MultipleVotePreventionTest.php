<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Code;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Hash;

/**
 * Multiple Vote Prevention Test - Real Elections
 *
 * This test verifies that the vote prevention logic in VoteController::first_submission()
 * at lines 410-424 is working correctly.
 *
 * Real elections MUST prevent users from voting twice.
 */
class MultipleVotePreventionTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $voter;
    protected $posts = [];
    protected $candidacies = [];
    protected $voterSlug;
    protected $code;

    public function setUp(): void
    {
        parent::setUp();

        // Create real election
        $this->realElection = Election::factory()->real()->create([
            'name' => 'General Election 2026',
            'type' => 'real',
            'is_active' => true,
        ]);

        // Create eligible voter
        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);

        // Create posts with candidates
        for ($i = 1; $i <= 3; $i++) {
            $this->posts[$i] = Post::factory()->create([
                'election_id' => $this->realElection->id,
                'post_id' => "POST{$i}",
                'name' => "Position {$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $candidateUser = User::factory()->create();
                $this->candidacies[$i][$j] = Candidacy::factory()->create([
                    'election_id' => $this->realElection->id,
                    'post_id' => $this->posts[$i]->post_id,
                    'candidacy_id' => "CAND{$i}{$j}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                    'proposer_id' => "PROPOSER{$i}{$j}",
                    'supporter_id' => "SUPPORTER{$i}{$j}",
                ]);
            }
        }

        // Create verification code for voter
        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code1_usable' => 1,
            'vote_submitted' => 0,
            'session_name' => 'vote_data_' . $this->voter->id,
        ]);

        // Create voter slug
        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'slug' => 'test-voter-slug-' . uniqid(),
            'expires_at' => now()->addMinutes(30),
            'current_step' => 3,
        ]);
    }

    /**
     * TEST: First vote submission succeeds (baseline test)
     *
     * Verify that a user's FIRST vote submission works correctly
     */
    public function test_first_vote_submission_succeeds()
    {
        $this->actingAs($this->voter);

        // Verify user hasn't voted yet
        $this->assertEquals(0, $this->code->has_voted, 'User should not have voted yet');

        // Set up session with valid vote data
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Submit first vote
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('First vote submission result', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
            'is_redirect' => $response->status() === 302,
        ]);

        // First submission should succeed (302 redirect to verification page)
        $this->assertEquals(302, $response->status(), 'First vote should be accepted (redirect)');

        // Should redirect to vote verification page
        $location = $response->headers->get('location');
        $this->assertStringContainsString('vote/verify', $location, 'Should redirect to vote verification page');
    }

    /**
     * TEST: Second vote submission is BLOCKED when has_voted=true
     *
     * This is the critical test: Real elections MUST prevent double voting
     */
    public function test_second_vote_submission_blocked_in_real_election()
    {
        $this->actingAs($this->voter);

        // SIMULATE: User already voted in this real election
        // Set has_voted flag to true (simulating completed first vote)
        $this->code->has_voted = true;
        $this->code->save();

        // Verify the flag is set
        $this->assertEquals(1, $this->code->has_voted, 'User should be marked as voted');

        // Set up session with NEW vote data (different candidates)
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND12', 'CAND23', 'CAND31']  // Different from first
        ]);

        // Attempt to vote again with different selections
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND12', 'CAND23', 'CAND31'],
                'agree_button' => true,
            ]
        );

        \Log::info('Second vote attempt (should be blocked)', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
            'has_voted_flag' => $this->code->fresh()->has_voted,
        ]);

        // The system should REJECT this submission
        // Expected: 302 redirect away from voting (to dashboard)
        $this->assertEquals(302, $response->status(), 'Second vote attempt should redirect');

        // Should redirect AWAY from the voting flow (to dashboard, not to verification)
        $location = $response->headers->get('location');
        $this->assertStringNotContainsString('vote/verify', $location,
            'Should NOT redirect to verification page (vote blocked)');

        $this->assertTrue(
            strpos($location, 'dashboard') !== false ||
            strpos($location, 'code/create') !== false,
            'Should redirect to dashboard or code creation, not vote verification'
        );
    }

    /**
     * TEST: Demo election allows second vote (different from real)
     *
     * Verify that demo elections explicitly ALLOW multiple votes
     * (This should NOT be blocked, even with has_voted=true)
     */
    public function test_demo_election_allows_second_vote()
    {
        // Create a demo election (different from real)
        $demoElection = Election::factory()->real()->create([
            'name' => 'Demo Election',
            'type' => 'demo',  // DEMO election
            'is_active' => true,
        ]);

        // Create posts for demo election
        for ($i = 1; $i <= 3; $i++) {
            $demoPost = Post::factory()->create([
                'election_id' => $demoElection->id,
                'post_id' => "DEMO_POST{$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $candidateUser = User::factory()->create();
                Candidacy::factory()->create([
                    'election_id' => $demoElection->id,
                    'post_id' => $demoPost->post_id,
                    'candidacy_id' => "DEMO_CAND{$i}{$j}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                    'proposer_id' => "DEMO_PROPOSER{$i}{$j}",
                    'supporter_id' => "DEMO_SUPPORTER{$i}{$j}",
                ]);
            }
        }

        // Create code for demo election with has_voted=true (already voted once)
        $demoCode = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $demoElection->id,
            'code1' => '111111',
            'code2' => '222222',
            'can_vote_now' => 1,
            'has_voted' => 1,  // Already voted once in demo
            'is_code1_usable' => 1,
            'session_name' => 'demo_vote_' . $this->voter->id,
        ]);

        // Create slug for demo election
        $demoSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $demoElection->id,
            'slug' => 'demo-slug-' . uniqid(),
            'current_step' => 3,
        ]);

        $this->actingAs($this->voter);

        // Set up session for second vote in DEMO
        $sessionName = Hash::make($demoCode->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['DEMO_CAND11', 'DEMO_CAND22', 'DEMO_CAND33']
        ]);

        // Attempt to vote again in DEMO election (should be allowed)
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $demoSlug->slug]),
            [
                'national_selected_candidates' => ['DEMO_CAND11', 'DEMO_CAND22', 'DEMO_CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Demo second vote attempt (should be allowed)', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
            'election_type' => $demoElection->type,
        ]);

        // Demo elections SHOULD allow voting even with has_voted=true
        // Should either succeed (200/302 to verification) or not be blocked with error
        $this->assertTrue(
            $response->status() === 302 &&
            strpos($response->headers->get('location'), 'vote/verify') !== false,
            'Demo election should allow second vote (redirect to verification)'
        );
    }

    /**
     * TEST: Verify the has_voted flag check is at the right place
     *
     * Confirm that the check happens in first_submission (line 410-424)
     * BEFORE any other processing
     */
    public function test_double_vote_check_happens_early()
    {
        $this->actingAs($this->voter);

        // Mark as voted
        $this->code->has_voted = true;
        $this->code->save();

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Try to vote with INVALID candidacy (should be blocked before validation)
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['INVALID_CAND_999', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Double vote with invalid candidacy', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // Should be blocked with "already voted" error, NOT "invalid candidacy" error
        // This proves the has_voted check happens FIRST
        $location = $response->headers->get('location');
        $this->assertStringNotContainsString('vote', $location,
            'Should block due to double vote, not validation error');
    }

    /**
     * TEST: Real election vs Demo election differentiation
     *
     * Verify the system properly distinguishes between election types
     */
    public function test_election_type_determines_voting_restrictions()
    {
        // Real elections should have type='real'
        $this->assertEquals('real', $this->realElection->type);

        // In real elections with has_voted=true
        $this->code->has_voted = true;
        $this->code->save();

        $this->actingAs($this->voter);
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Attempt submission in real election with has_voted=true
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Real election double vote check', [
            'status' => $response->status(),
            'election_type' => $this->realElection->type,
            'location' => $response->headers->get('location'),
        ]);

        // Real election should block
        $this->assertNotEquals(
            strpos($response->headers->get('location'), 'vote/verify') !== false ? true : false,
            true,
            'Real election should NOT allow verification redirect with has_voted=true'
        );
    }
}
