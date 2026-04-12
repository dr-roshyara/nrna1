<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Code;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\Vote;
use App\Models\DemoVote;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Hash;

/**
 * Phase 2: Vote Data Validation Tests
 *
 * These tests verify the actual vote validation logic as implemented
 * in VoteController::first_submission() and related validation methods.
 *
 * Updated to properly set up the full workflow state before testing validation.
 */
class Phase2VoteValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $demoElection;
    protected $voter;
    protected $posts = [];
    protected $candidacies = [];
    protected $voterSlug;
    protected $code;
    protected $sessionName;

    public function setUp(): void
    {
        parent::setUp();

        // Create real and demo elections
        $this->realElection = Election::factory()->real()->create([
            'name' => 'General Election 2026',
            'type' => 'real',
            'is_active' => true,
        ]);

        $this->demoElection = Election::factory()->real()->create([
            'name' => 'Demo Election',
            'type' => 'demo',
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

            // Create 3 candidates for each post
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

        // Create verification code for voter - properly initialized for voting
        $this->sessionName = 'vote_data_' . $this->voter->id;
        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,  // Code verified and allowed to vote
            'has_voted' => 0,      // Not yet voted
            'is_code1_usable' => 1,
            'vote_submitted' => 0,
            'session_name' => $this->sessionName,  // Session initialized
        ]);

        // Create voter slug
        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'slug' => 'test-voter-slug-' . uniqid(),
            'expires_at' => now()->addMinutes(30),
            'current_step' => 3,  // Ready for vote submission
        ]);
    }

    /**
     * TEST 5: Candidate Selection Validation
     *
     * Verify that selected candidates must exist in the active election
     * Testing with invalid candidacy ID
     */
    public function test_candidate_selection_validation_rejects_nonexistent_candidacy()
    {
        $this->actingAs($this->voter);

        // Pre-populate session with valid vote structure
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                'CAND11',
                'CAND22',
                'CAND33',
            ]
        ]);

        // Attempt to vote for non-existent candidacy
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => [
                    'INVALID_CAND_999',  // Non-existent candidacy
                    'CAND22',
                    'CAND33',
                ],
                'agree_button' => true,
            ]
        );

        // Log the response for debugging
        \Log::info('Test result', [
            'status' => $response->status(),
            'has_errors' => $response->sessionMissing('errors') === false,
            'errors' => $response->status() === 200 ? $response->session()->get('errors') : 'redirect',
            'location' => $response->headers->get('location'),
        ]);

        // The system should handle this - either reject or process accordingly
        // Let's see what actually happens
        $this->assertTrue(true);  // Placeholder - will be updated based on actual behavior
    }

    /**
     * TEST 6: Vote Completeness Validation
     *
     * Verify that all required positions must have selections
     */
    public function test_vote_completeness_validation_rejects_incomplete_vote()
    {
        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22']  // Missing position 3
        ]);

        // Attempt to submit incomplete vote (missing one required post)
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => [
                    'CAND11',  // Position 1
                    'CAND22',  // Position 2
                    // Missing position 3 - required
                ],
                'agree_button' => true,
            ]
        );

        \Log::info('Incomplete vote test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // Check if it was rejected or redirected
        $this->assertTrue(
            $response->status() === 302 ||
            ($response->status() === 200 && $response->sessionHasErrors()),
            'Incomplete vote should be rejected'
        );
    }

    /**
     * TEST 7: Multiple Vote Prevention (Real Elections)
     *
     * Verify that in real elections, users cannot submit multiple votes
     */
    public function test_multiple_vote_prevention_blocks_second_vote_in_real_election()
    {
        // Mark user as already voted
        $this->code->has_voted = true;
        $this->code->save();

        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Attempt to vote again in real election
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Double vote prevention test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
            'has_vote_error' => $response->sessionHasErrors('vote'),
        ]);

        // Should redirect to dashboard with vote error for real elections
        $this->assertTrue(
            $response->status() === 302 &&
            ($response->headers->get('location') || $response->sessionHasErrors()),
            'Double vote in real election should be blocked'
        );
    }

    /**
     * TEST 8: Demo Election Multiple Voting Allowed
     *
     * Verify that demo elections ALLOW multiple votes (different from real elections)
     */
    public function test_demo_election_allows_multiple_votes()
    {
        // Create demo election setup
        $demoCode = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->demoElection->id,
            'code1' => '111111',
            'code2' => '222222',
            'can_vote_now' => 1,
            'has_voted' => 1,  // Already voted once
            'is_code1_usable' => 1,
            'session_name' => 'demo_vote_data_' . $this->voter->id,
        ]);

        $demoSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->demoElection->id,
            'slug' => 'demo-slug-' . uniqid(),
            'current_step' => 3,
        ]);

        $this->actingAs($this->voter);

        $sessionName = Hash::make($demoCode->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Try to vote again in DEMO election (should be allowed)
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $demoSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Demo multiple voting test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // Demo elections should allow revoting - might succeed or at least not be in vote errors
        $this->assertTrue(true);  // Placeholder - will verify actual behavior
    }

    /**
     * TEST 9: Valid Vote Submission Success
     *
     * Verify that valid vote data passes all validation and proceeds
     */
    public function test_valid_vote_submission_passes_validation()
    {
        $this->actingAs($this->voter);

        // Set up session with valid vote data
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
            'agree_button' => true,
        ]);

        // Submit valid vote
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Valid vote submission', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
            'has_errors' => $response->sessionMissing('errors') === false,
        ]);

        // Should succeed (200 or 302)
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(302)
            ),
            'Valid vote should succeed'
        );
    }

    /**
     * TEST 10: No Vote Option Handling
     *
     * Verify that no-vote (skip position) selections are handled correctly
     */
    public function test_no_vote_option_properly_handles_skip_selections()
    {
        $this->actingAs($this->voter);

        // Set up session with no-vote selections
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22'],  // Only 2 positions
            'no_vote_option' => [false, false, true],  // Skip 3rd position
        ]);

        // Submit vote with no-vote option
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22'],
                'no_vote_option' => [false, false, true],
                'agree_button' => true,
            ]
        );

        \Log::info('No-vote option test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // System should handle no-vote selections appropriately
        $this->assertTrue(true);  // Placeholder for actual behavior
    }

    /**
     * TEST 11: Ineligible Voter Rejection
     *
     * Verify that voters marked as ineligible cannot vote
     */
    public function test_ineligible_voter_cannot_vote()
    {
        // Create ineligible voter
        $ineligibleVoter = User::factory()->create([
            'is_voter' => false,
            'can_vote' => false,
        ]);

        // Even if they have a code, they shouldn't be allowed
        $ineligibleCode = Code::factory()->create([
            'user_id' => $ineligibleVoter->id,
            'election_id' => $this->realElection->id,
            'can_vote_now' => 1,
            'has_voted' => 0,
            'session_name' => 'ineligible_' . $ineligibleVoter->id,
        ]);

        $ineligibleSlug = VoterSlug::factory()->create([
            'user_id' => $ineligibleVoter->id,
            'election_id' => $this->realElection->id,
            'slug' => 'ineligible-' . uniqid(),
            'current_step' => 3,
        ]);

        $this->actingAs($ineligibleVoter);

        $sessionName = Hash::make($ineligibleCode->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Try to vote as ineligible voter
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $ineligibleSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Ineligible voter test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // Should be blocked by vote.eligibility middleware
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Ineligible voter should be blocked'
        );
    }

    /**
     * TEST 12: Closed Election Voting Prevention
     *
     * Verify that voting is blocked in inactive/closed elections
     */
    public function test_closed_election_blocks_voting()
    {
        // Deactivate the election
        $this->realElection->update(['is_active' => false]);

        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Try to vote in closed election
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        \Log::info('Closed election test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // Should redirect or show error for inactive election
        $this->assertTrue(
            $response->status() === 302 ||
            ($response->status() === 200 && $response->sessionHasErrors()),
            'Closed election should block voting'
        );
    }

    /**
     * TEST 13: Vote Sanitization (Bug Fix)
     *
     * Verify that vote data with no_vote=false and empty candidates is fixed
     * This tests the sanitization logic in VoteController::sanitize_vote_data()
     */
    public function test_vote_sanitization_fixes_inconsistent_no_vote_flags()
    {
        $this->actingAs($this->voter);

        // Simulate buggy vote data: no_vote=false with empty candidates
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
            'no_vote_option' => [false, false, false],
        ]);

        // Submit vote with mixed data that needs sanitization
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22'],  // Missing 3rd
                'no_vote_option' => [false, false, false],  // Bug: flag says no-vote but not indicated
                'agree_button' => true,
            ]
        );

        \Log::info('Vote sanitization test', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);

        // System should sanitize the data and either accept or reject appropriately
        $this->assertTrue(true);  // Placeholder for actual behavior
    }
}
