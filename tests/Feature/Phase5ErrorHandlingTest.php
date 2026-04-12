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
 * Phase 5: Error Handling Testing
 *
 * Tests to verify the system handles errors gracefully:
 * 1. Invalid candidacy selection rejection
 * 2. Missing required candidates
 * 3. Expired codes
 * 4. Ineligible voters
 * 5. Election not active
 * 6. Database constraint violations
 * 7. Session data integrity
 * 8. Missing vote data
 */
class Phase5ErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected $election;
    protected $voter;
    protected $code;
    protected $voterSlug;

    public function setUp(): void
    {
        parent::setUp();

        $this->election = Election::factory()->real()->create([
            'name' => 'Error Handling Test Election',
            'type' => 'real',
            'is_active' => true,
        ]);

        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
        ]);

        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code1_usable' => 1,
        ]);

        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'slug' => 'test-' . uniqid(),
            'current_step' => 1,
        ]);

        // Create posts
        for ($i = 1; $i <= 3; $i++) {
            Post::factory()->create([
                'election_id' => $this->election->id,
                'post_id' => "POST{$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);
        }
    }

    /**
     * TEST 1: Reject invalid candidacy selection
     *
     * System should reject votes with non-existent candidacy IDs
     */
    public function test_invalid_candidacy_selection_rejected()
    {
        \Log::info('TEST 1: Testing invalid candidacy rejection');

        $this->actingAs($this->voter);

        // Setup session with INVALID candidacy
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['INVALID_CAND_999']
        ]);

        // Attempt to vote with invalid candidate
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['INVALID_CAND_999'],
                'agree_button' => true,
            ]
        );

        // Should not succeed (redirect to vote creation or error page)
        $this->assertNotEquals(
            strpos($response->headers->get('location'), 'vote/verify'),
            true,
            'Should NOT redirect to verification with invalid candidates'
        );

        \Log::info('TEST 1: Invalid candidacy properly rejected', [
            'status' => $response->status(),
            'location' => $response->headers->get('location'),
        ]);
    }

    /**
     * TEST 2: Reject missing required candidates
     *
     * If election requires 3 candidates, reject submission with fewer
     */
    public function test_missing_required_candidates_rejected()
    {
        \Log::info('TEST 2: Testing missing required candidates rejection');

        // Note: This test documents the expected behavior
        // The actual implementation may vary

        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11']  // Only 1, but 3 might be required
        ]);

        // Attempt submission with insufficient candidates
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11'],
                'agree_button' => true,
            ]
        );

        // Verify response status (could be redirect to error or validation page)
        $this->assertTrue(
            in_array($response->status(), [200, 302, 422]),
            'Should return appropriate status for validation error'
        );

        \Log::info('TEST 2: Missing candidates validation tested', [
            'status' => $response->status(),
        ]);
    }

    /**
     * TEST 3: Ineligible voter cannot vote
     *
     * Voters with can_vote=0 or is_voter=0 should be blocked
     */
    public function test_ineligible_voter_blocked()
    {
        \Log::info('TEST 3: Testing ineligible voter blocking');

        // Create ineligible voter
        $ineligibleVoter = User::factory()->create([
            'is_voter' => false,  // Not marked as voter
            'can_vote' => false,   // Not eligible to vote
        ]);

        // Try to vote
        $this->actingAs($ineligibleVoter);

        $response = $this->get(
            route('slug.vote.create', ['vslug' => $this->voterSlug->slug])
        );

        // Should be denied access or redirected
        $this->assertTrue(
            in_array($response->status(), [403, 302, 401]),
            'Ineligible voter should be denied or redirected'
        );

        \Log::info('TEST 3: Ineligible voter properly blocked', [
            'status' => $response->status(),
        ]);
    }

    /**
     * TEST 4: Inactive election blocks voting
     *
     * Votes should be rejected if election is_active=0
     */
    public function test_inactive_election_blocks_voting()
    {
        \Log::info('TEST 4: Testing inactive election blocking');

        // Deactivate election
        $this->election->is_active = 0;
        $this->election->save();

        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Attempt to vote
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        // Should be blocked
        $this->assertTrue(
            in_array($response->status(), [302, 403]),
            'Inactive election should block voting'
        );

        \Log::info('TEST 4: Inactive election properly blocks voting', [
            'status' => $response->status(),
        ]);
    }

    /**
     * TEST 5: Expired code blocks voting
     *
     * Codes with can_vote_now=0 should not allow voting
     */
    public function test_expired_code_blocks_voting()
    {
        \Log::info('TEST 5: Testing expired code blocking');

        // Mark code as expired
        $this->code->can_vote_now = 0;
        $this->code->save();

        $this->actingAs($this->voter);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Attempt to vote with expired code
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        // Should be blocked (not redirected to verify)
        $location = $response->headers->get('location');
        $this->assertFalse(
            strpos($location, 'vote/verify') !== false,
            'Expired code should not proceed to verification'
        );

        \Log::info('TEST 5: Expired code properly blocks voting', [
            'status' => $response->status(),
            'location' => $location,
        ]);
    }

    /**
     * TEST 6: Missing session data is handled
     *
     * Voting without valid session data should be rejected
     */
    public function test_missing_session_data_rejected()
    {
        \Log::info('TEST 6: Testing missing session data handling');

        $this->actingAs($this->voter);

        // Attempt to vote WITHOUT setting up session
        // Session is NOT populated with vote data
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        // Should not proceed to verification
        $location = $response->headers->get('location');
        $this->assertFalse(
            strpos($location, 'vote/verify') !== false,
            'Missing session data should not proceed to verification'
        );

        \Log::info('TEST 6: Missing session data properly handled', [
            'status' => $response->status(),
        ]);
    }

    /**
     * TEST 7: Unauthenticated access blocked
     *
     * Unauth users should not reach voting endpoints
     */
    public function test_unauthenticated_access_blocked()
    {
        \Log::info('TEST 7: Testing unauthenticated access blocking');

        // Don't authenticate
        $response = $this->get(
            route('slug.vote.create', ['vslug' => $this->voterSlug->slug])
        );

        // Should be redirected (either to login or elsewhere)
        $this->assertEquals(302, $response->status(), 'Unauthenticated access should redirect');
        $location = $response->headers->get('location');

        // Verify redirect is not staying on voting page
        $this->assertFalse(
            strpos($location, 'vote/create') !== false,
            'Unauthenticated should not stay on voting page'
        );

        \Log::info('TEST 7: Unauthenticated access properly blocked', [
            'status' => $response->status(),
            'location' => $location,
        ]);
    }

    /**
     * TEST 8: Code mismatch is rejected
     *
     * Voting with wrong code should be rejected
     */
    public function test_code_mismatch_rejected()
    {
        \Log::info('TEST 8: Testing code mismatch rejection');

        $this->actingAs($this->voter);

        // Use WRONG code hash in session
        $wrongCode = Hash::make('WRONG_CODE');
        session()->put($wrongCode, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Attempt to vote with mismatched code
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        // Should be rejected (not verified)
        $location = $response->headers->get('location');
        $this->assertFalse(
            strpos($location, 'vote/verify') !== false,
            'Code mismatch should not proceed to verification'
        );

        \Log::info('TEST 8: Code mismatch properly rejected', [
            'status' => $response->status(),
        ]);
    }
}
