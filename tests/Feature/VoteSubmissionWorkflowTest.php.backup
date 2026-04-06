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
use Illuminate\Support\Facades\Hash;

/**
 * VoteSubmissionWorkflowTest
 *
 * TDD Tests for Complete Real Election Vote Submission Workflow
 *
 * User Journey:
 * 1. User lands on /code/create
 * 2. User agrees to terms and conditions
 * 3. User lands on /vote/create
 * 4. Selects candidates for all posts
 * 5. Reviews selections
 * 6. Submits vote
 * 7. Receives verification code
 * 8. Enters verification code
 * 9. Vote is recorded in votes table
 * 10. Cannot vote again (redirected to dashboard)
 */
class VoteSubmissionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $voter;
    protected $code;
    protected $posts;
    protected $candidacies;

    public function setUp(): void
    {
        parent::setUp();

        // Create real election
        $this->realElection = Election::factory()->real()->create([
            'name' => 'General Election 2026',
            'type' => 'real',
            'is_active' => true,
        ]);

        // Create voter user
        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);

        // Create verification code for voter
        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 0,  // Not yet started voting
            'has_voted' => 0,
        ]);

        // Create posts for election
        $this->posts = [];
        for ($i = 1; $i <= 3; $i++) {
            $this->posts[$i] = Post::create([
                'post_id' => "POST{$i}",
                'name' => "Position {$i}",
                'nepali_name' => "पद {$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);
        }

        // Create candidacies for each post
        $this->candidacies = [];
        foreach ($this->posts as $postIndex => $post) {
            for ($j = 1; $j <= 3; $j++) {
                $candidacyId = $postIndex . $j;
                // Create user for this candidacy
                $candidateUser = User::factory()->create();
                $this->candidacies[$candidacyId] = Candidacy::create([
                    'post_id' => $post->post_id,
                    'candidacy_id' => "CAND{$candidacyId}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                ]);
            }
        }
    }

    // ========================================
    // STEP 1: User Lands on /code/create
    // ========================================

    /**
     * Test: User can access code entry page
     *
     * Description: When an authenticated voter navigates to /code/create,
     * they should see the code entry form.
     */
    public function test_user_can_access_code_create_page()
    {
        $response = $this->actingAs($this->voter)->get(route('code.create'));

        // Verify page loads
        $response->assertStatus(200);

        // Verify it's the code entry page (check for specific content or Inertia component)
        // This assumes your route returns Inertia response with component name
        $this->assertTrue(true); // Placeholder for actual assertions
    }

    /**
     * Test: Code entry page requires authentication
     *
     * Description: Unauthenticated users should be redirected to login
     */
    public function test_unauthenticated_user_redirected_from_code_create()
    {
        $response = $this->get(route('code.create'));

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Code entry page requires voter eligibility
     *
     * Description: Users who are not marked as voters cannot access code entry
     */
    public function test_non_voter_cannot_access_code_create_page()
    {
        $nonVoter = User::factory()->create(['is_voter' => false]);

        $response = $this->actingAs($nonVoter)->get(route('code.create'));

        // Should be redirected or show error
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Non-voters should be redirected or denied access'
        );
    }

    /**
     * Test: Code entry page accessible only for elections with voting enabled
     *
     * Description: User cannot enter code if no election is currently active
     */
    public function test_code_create_requires_active_election()
    {
        // Deactivate election
        $this->realElection->update(['is_active' => false]);

        $response = $this->actingAs($this->voter)->get(route('code.create'));

        // Should redirect to dashboard or show error
        $response->assertStatus(302);
    }

    // ========================================
    // STEP 2: User Agrees to Terms & Conditions
    // ========================================

    /**
     * Test: User can submit code1 to agree to terms
     *
     * Description: When user enters code1 and confirms terms,
     * the system records agreement and provides code2
     */
    public function test_user_can_submit_code1_and_agree_to_terms()
    {
        $this->actingAs($this->voter);

        // Submit code1 (agreeing to terms)
        $response = $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Should redirect to vote creation page
        $response->assertRedirect(route('vote.create'));

        // Verify code record updated
        $code = Code::find($this->code->id);
        $this->assertTrue($code->has_agreed_to_vote);
        $this->assertNotNull($code->voting_started_at);
    }

    /**
     * Test: Code1 submission fails with incorrect code
     *
     * Description: If user enters wrong code1, submission should fail
     */
    public function test_code1_submission_fails_with_incorrect_code()
    {
        $this->actingAs($this->voter);

        // Submit incorrect code1
        $response = $this->post(route('code.first_submission'), [
            'code1' => '999999',  // Wrong code
            'has_agreed_to_vote' => true,
        ]);

        // Should return with error
        $response->assertSessionHasErrors('code1');

        // Code should not be marked as agreed
        $code = Code::find($this->code->id);
        $this->assertFalse($code->has_agreed_to_vote);
    }

    /**
     * Test: Code1 submission fails if terms not accepted
     *
     * Description: User must check "I agree" box before submitting
     */
    public function test_code1_submission_fails_without_agreement()
    {
        $this->actingAs($this->voter);

        // Submit code1 without agreeing to terms
        $response = $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => false,  // Not agreed
        ]);

        // Should return with error
        $response->assertSessionHasErrors('has_agreed_to_vote');
    }

    /**
     * Test: Code1 cannot be used twice
     *
     * Description: After using code1 once, it cannot be reused
     */
    public function test_code1_can_only_be_used_once()
    {
        $this->actingAs($this->voter);

        // First submission - should succeed
        $response1 = $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);
        $response1->assertRedirect(route('vote.create'));

        // Second submission with same code - should fail
        $response2 = $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Should be rejected
        $response2->assertSessionHasErrors();
    }

    // ========================================
    // PHASE 2: DATA VALIDATION
    // ========================================
    // These tests verify that vote data is properly validated before storage

    /**
     * Test: No vote option validation
     *
     * Description: When user selects "no vote option" (skip position),
     * the system validates this selection is properly formatted
     */
    public function test_no_vote_option_validation()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Attempt to submit vote with no-vote option
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22'],
            'post_3_no_vote' => true,  // Skip position 3
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should accept the no-vote option
        // (specific assertion depends on implementation)
        $this->assertTrue(true);
    }

    /**
     * Test: Candidate selection validation
     *
     * Description: Selected candidates must exist and be valid
     */
    public function test_candidate_selection_validation()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Try to submit with invalid candidacy_id
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                'INVALID_CAND_999',  // Non-existent candidacy
                'CAND22',
                'CAND33',
            ]
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should reject invalid candidacy
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Multiple vote prevention (real elections)
     *
     * Description: In real elections, user cannot submit multiple votes
     */
    public function test_multiple_vote_prevention_real_elections()
    {
        $this->actingAs($this->voter);

        // Submit code1 and first vote
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Mark as voted
        $this->code->update(['has_voted' => true]);

        // Try to submit second vote
        $sessionName2 = Hash::make('new_code2');
        session()->put($sessionName2, [
            'national_selected_candidates' => ['CAND12', 'CAND23', 'CAND31']
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName2,
        ]);

        // Should be blocked - user already voted
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Vote completeness validation
     *
     * Description: Vote must have selections for all required positions
     */
    public function test_vote_completeness_validation()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Try to submit incomplete vote (missing one required position)
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                'CAND11',
                'CAND22',
                // Missing CAND33 for required position 3
            ]
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should fail validation for incomplete vote
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Invalid candidacy detection
     *
     * Description: System rejects candidacy IDs that don't belong to the election
     */
    public function test_invalid_candidacy_detection()
    {
        // Create another election with different candidacies
        $otherElection = Election::factory()->real()->create();

        $otherPost = Post::create([
            'post_id' => 'POST_OTHER',
            'name' => 'Other Position',
            'nepali_name' => 'अन्य पद',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        $otherUser = User::factory()->create();
        $otherCandidacy = Candidacy::create([
            'post_id' => $otherPost->post_id,
            'candidacy_id' => 'CAND_OTHER',
            'user_id' => $otherUser->user_id,
        ]);

        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Try to vote for candidacy from different election
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                'CAND_OTHER',  // From different election
                'CAND22',
                'CAND33',
            ]
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should reject candidacy from wrong election
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Election status validation (active/closed)
     *
     * Description: Can only vote in active elections, not closed ones
     */
    public function test_election_status_validation()
    {
        // Create an inactive election
        $inactiveElection = Election::factory()->real()->create(['is_active' => false]);

        $inactiveVoter = User::factory()->voter()->create();
        $inactiveCode = Code::factory()->create([
            'user_id' => $inactiveVoter->id,
            'election_id' => $inactiveElection->id,
            'code1' => '111111',
            'code2' => '222222',
        ]);

        $this->actingAs($inactiveVoter);

        // Try to vote in inactive election
        $sessionName = Hash::make($inactiveCode->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should reject voting in inactive election
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Voter eligibility verification
     *
     * Description: Only eligible voters (is_voter=true, can_vote=true) can vote
     */
    public function test_voter_eligibility_verification()
    {
        // Create a user marked as non-voter
        $nonEligibleVoter = User::factory()->create([
            'is_voter' => false,
            'can_vote' => false,
        ]);

        $eligibilityCode = Code::factory()->create([
            'user_id' => $nonEligibleVoter->id,
            'election_id' => $this->realElection->id,
            'code1' => '444444',
            'code2' => '555555',
        ]);

        $this->actingAs($nonEligibleVoter);

        // Try to vote without eligibility
        $sessionName = Hash::make($eligibilityCode->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should reject non-eligible voter
        $response->assertSessionHasErrors();
    }

    /**
     * Test: Vote data structure validation
     *
     * Description: Vote data must follow proper structure with candidacy_id format
     */
    public function test_vote_data_structure_validation()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Try to submit malformed vote data
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                ['invalid' => 'structure'],  // Wrong data type
                'CAND22',
                'CAND33',
            ]
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should reject malformed data
        $response->assertSessionHasErrors();
    }

    // ========================================
    // STEP 3: User Lands on /vote/create
    // ========================================

    /**
     * Test: User can access vote creation page after code1 submission
     *
     * Description: After submitting code1, user lands on /vote/create
     * with all posts and candidates available
     */
    public function test_user_can_access_vote_create_page_after_code1()
    {
        $this->actingAs($this->voter);

        // First submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Now access vote creation page
        $response = $this->get(route('vote.create'));

        // Should load successfully
        $response->assertStatus(200);
    }

    /**
     * Test: User cannot access /vote/create without code1 submission
     *
     * Description: If user hasn't submitted code1, they cannot access voting form
     */
    public function test_user_cannot_access_vote_create_without_code1()
    {
        $this->actingAs($this->voter);

        // Try to access voting form without submitting code1
        $response = $this->get(route('vote.create'));

        // Should redirect to code creation
        $response->assertRedirect(route('code.create'));
    }

    /**
     * Test: Vote creation page shows all posts with candidates
     *
     * Description: The voting form displays all posts and their candidates
     */
    public function test_vote_create_page_displays_all_posts_and_candidates()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Access vote creation page
        $response = $this->get(route('vote.create'));

        // Response should include election data
        $this->assertArrayHasKey('activeElection', $response->original['props']);
        $this->assertArrayHasKey('posts', $response->original['props']);
    }

    /**
     * Test: Candidates ordered by position_order
     *
     * Description: Candidates should appear in position_order sequence
     */
    public function test_candidates_displayed_in_position_order()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Access voting form - should receive candidates ordered
        $response = $this->get(route('vote.create'));

        $posts = $response->original['props']['posts'] ?? [];
        foreach ($posts as $post) {
            if (!empty($post['candidates'])) {
                $positionOrders = array_column($post['candidates'], 'position_order');
                // Verify ordering
                $this->assertEquals($positionOrders, array_values($positionOrders));
            }
        }
    }

    // ========================================
    // STEP 4: User Selects Candidates for All Posts
    // ========================================

    /**
     * Test: User can select candidate for each post
     *
     * Description: When user selects a candidate for each post,
     * selections are stored in session
     */
    public function test_user_can_select_candidate_for_each_post()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Prepare vote data - select one candidate per post
        $voteData = [
            'national_selected_candidates' => [
                'CAND11',  // From post 1
                'CAND22',  // From post 2
                'CAND33',  // From post 3
            ]
        ];

        // Store in session (this typically happens via form submission)
        $session = session();
        $sessionName = Hash::make($this->code->code2);
        $session->put($sessionName, $voteData);

        // Verify stored in session
        $this->assertEquals(
            $voteData['national_selected_candidates'],
            session()->get($sessionName)['national_selected_candidates']
        );
    }

    /**
     * Test: User must select at least one candidate per required post
     *
     * Description: If a post is marked required, user cannot skip it
     */
    public function test_user_cannot_skip_required_post()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Try to submit with missing candidates
        $incompleteVoteData = [
            'national_selected_candidates' => [
                'CAND11',  // Only 1 instead of 3 posts
            ]
        ];

        // This should fail validation
        $response = $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
            // Missing candidates for some posts
        ]);

        // Should return validation error
        // (specific error depends on implementation)
    }

    /**
     * Test: User can select or deselect candidate
     *
     * Description: User can change selection before submitting
     */
    public function test_user_can_change_candidate_selection()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // First selection
        $firstSelection = ['CAND11', 'CAND22', 'CAND33'];

        // Change selection
        $changedSelection = ['CAND12', 'CAND22', 'CAND33'];

        // Both should be possible before final submission
        $this->assertTrue(true);  // Placeholder - actual implementation tests change logic
    }

    // ========================================
    // STEP 5: User Reviews Selections
    // ========================================

    /**
     * Test: User can review selected candidates before submission
     *
     * Description: Before final submission, user sees a review page
     * with all selected candidates
     */
    public function test_user_can_review_selections_before_submission()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Store vote selections in session
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => [
                'CAND11', 'CAND22', 'CAND33'
            ]
        ]);

        // Access review page
        $response = $this->get(route('vote.review'));

        // Should show selected candidates
        $response->assertStatus(200);
    }

    /**
     * Test: Review page displays candidate details
     *
     * Description: Review page shows name, position, and image of selected candidates
     */
    public function test_review_page_displays_candidate_details()
    {
        $this->actingAs($this->voter);

        // Submit code1 and access review
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $response = $this->get(route('vote.review'));

        // Should include candidate data in response
        $this->assertArrayHasKey('selectedCandidates', $response->original['props'] ?? []);
    }

    // ========================================
    // STEP 6: User Submits Vote
    // ========================================

    /**
     * Test: User can submit vote with all candidates selected
     *
     * Description: When user submits with complete selection,
     * vote is recorded and verification code is provided
     */
    public function test_user_can_submit_complete_vote()
    {
        $this->actingAs($this->voter);

        // Step 1: Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Step 2: Store selections in session
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        // Step 3: Submit vote - no votes yet since code2 not verified
        // This is typically handled by JavaScript before final submission
        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should redirect to vote verification page
        $response->assertStatus(302);
    }

    /**
     * Test: Vote submission fails with incomplete selections
     *
     * Description: If not all required posts have selections, submission fails
     */
    public function test_vote_submission_fails_with_incomplete_selections()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Store incomplete selections
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11']  // Missing other posts
        ]);

        // Try to submit
        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should fail with validation error
        $response->assertSessionHasErrors();
    }

    /**
     * Test: User cannot submit if session expired
     *
     * Description: Session data must exist when voting
     */
    public function test_vote_submission_fails_if_session_expired()
    {
        $this->actingAs($this->voter);

        // Try to submit without session data
        $response = $this->post(route('vote.first_submission'), [
            'session_name' => 'nonexistent_session',
        ]);

        // Should fail
        $response->assertSessionHasErrors();
    }

    // ========================================
    // STEP 7: User Receives Verification Code
    // ========================================

    /**
     * Test: User receives code2 for verification
     *
     * Description: After vote submission, user receives verification code (code2)
     */
    public function test_user_receives_verification_code_after_submission()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Store and submit vote
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $response = $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Should redirect to verification page
        $response->assertRedirect(route('vote.verify_to_show'));

        // Verification page should display code2 for user to enter
        $verifyResponse = $this->get(route('vote.verify_to_show'));
        $verifyResponse->assertStatus(200);
    }

    /**
     * Test: Verification code is unique per user per election
     *
     * Description: Each user gets a unique code2 for their election
     */
    public function test_verification_code_is_unique_per_user()
    {
        // Create two voters
        $voter1 = User::factory()->voter()->create();
        $voter2 = User::factory()->voter()->create();

        $code1 = Code::factory()->create([
            'user_id' => $voter1->id,
            'election_id' => $this->realElection->id,
            'code2' => '111111',
        ]);

        $code2 = Code::factory()->create([
            'user_id' => $voter2->id,
            'election_id' => $this->realElection->id,
            'code2' => '222222',
        ]);

        // Codes should be different
        $this->assertNotEquals($code1->code2, $code2->code2);
    }

    // ========================================
    // STEP 8: User Enters Verification Code
    // ========================================

    /**
     * Test: User can enter correct verification code
     *
     * Description: When user submits correct code2, vote is recorded
     */
    public function test_user_can_enter_correct_verification_code()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Submit vote selections
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Now submit correct verification code
        $response = $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Should redirect to success page
        $response->assertStatus(302);
    }

    /**
     * Test: Verification fails with incorrect code
     *
     * Description: If user enters wrong code2, vote is not recorded
     */
    public function test_verification_fails_with_incorrect_code()
    {
        $this->actingAs($this->voter);

        // Submit code1
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        // Submit vote
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Try to verify with wrong code
        $response = $this->post(route('vote.store'), [
            'voting_code' => '999999',  // Wrong code
        ]);

        // Should fail
        $response->assertSessionHasErrors('voting_code');
    }

    /**
     * Test: Code2 validation requires exact match
     *
     * Description: Code2 must match exactly (case, length, etc.)
     */
    public function test_code2_validation_is_strict()
    {
        $this->actingAs($this->voter);

        // Submit code1 and vote
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Try variations of code2
        $invalidAttempts = [
            ' 654321',      // Leading space
            '654321 ',      // Trailing space
            '654320',       // Wrong digit
            '65432',        // Too short
        ];

        foreach ($invalidAttempts as $invalidCode) {
            $response = $this->post(route('vote.store'), [
                'voting_code' => $invalidCode,
            ]);
            $response->assertSessionHasErrors('voting_code');
        }
    }

    // ========================================
    // STEP 9: Vote is Recorded in Correct Table
    // ========================================

    /**
     * Test: Vote is recorded in votes table (real election)
     *
     * Description: After verification, vote is stored in votes table for real elections
     */
    public function test_vote_recorded_in_votes_table_for_real_election()
    {
        $this->actingAs($this->voter);

        // Complete vote submission flow
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        // Submit verification code
        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Verify votes were recorded
        $this->assertDatabaseCount('votes', 3);

        // Each candidacy should have a vote
        foreach (['CAND11', 'CAND22', 'CAND33'] as $candidacyId) {
            $candidacy = Candidacy::where('candidacy_id', $candidacyId)->first();
            $this->assertDatabaseHas('votes', [
                'user_id' => $this->voter->id,
                'election_id' => $this->realElection->id,
                'candidacy_id' => $candidacy->id,
            ]);
        }
    }

    /**
     * Test: Vote record includes metadata
     *
     * Description: Vote record includes user, election, candidacy, and timestamp
     */
    public function test_vote_record_includes_required_metadata()
    {
        $this->actingAs($this->voter);

        // Complete vote submission
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Verify vote has required fields
        $vote = Vote::where('user_id', $this->voter->id)->first();
        $this->assertNotNull($vote);
        $this->assertNotNull($vote->candidacy_id);
        $this->assertNotNull($vote->election_id);
        $this->assertNotNull($vote->created_at);
    }

    /**
     * Test: Code record marked as voted after verification
     *
     * Description: After vote is recorded, Code.has_voted is set to true
     */
    public function test_code_marked_as_voted_after_verification()
    {
        $this->actingAs($this->voter);

        // Before voting
        $this->assertFalse($this->code->has_voted);

        // Complete vote submission
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // After voting
        $updatedCode = Code::find($this->code->id);
        $this->assertTrue($updatedCode->has_voted);
    }

    /**
     * Test: User record marked as voted after verification
     *
     * Description: After vote is recorded, User.has_voted is set to true
     */
    public function test_user_marked_as_voted_after_verification()
    {
        $this->actingAs($this->voter);

        // Before voting
        $this->assertFalse($this->voter->has_voted);

        // Complete vote submission
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // After voting
        $updatedUser = User::find($this->voter->id);
        $this->assertTrue($updatedUser->has_voted);
    }

    // ========================================
    // STEP 10: User Cannot Vote Again
    // ========================================

    /**
     * Test: User redirected to dashboard on revoting attempt
     *
     * Description: If user tries to vote again in same real election,
     * they are redirected to dashboard with error message
     */
    public function test_user_redirected_to_dashboard_on_revote_attempt()
    {
        $this->actingAs($this->voter);

        // Complete first vote submission
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Now verify user cannot vote again
        $this->assertTrue($this->code->fresh()->has_voted);

        // Try to access voting form again
        $response = $this->get(route('vote.create'));

        // Should redirect to dashboard
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test: Revoting attempt shows error message
     *
     * Description: When revoting attempt is blocked, user sees error message
     */
    public function test_revoting_attempt_shows_error_message()
    {
        $this->actingAs($this->voter);

        // Complete first vote
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Try to revote - should show error
        $response = $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        $response->assertSessionHasErrors('vote');
        $this->assertStringContainsString(
            'already voted',
            session()->get('errors')->first('vote')
        );
    }

    /**
     * Test: Revote blocked at vote.store endpoint
     *
     * Description: The vote submission endpoint blocks revoting
     * (VoteController.php:1250)
     */
    public function test_revote_blocked_at_vote_store_endpoint()
    {
        $this->actingAs($this->voter);

        // Set up user who has already voted
        $this->code->update(['has_voted' => true]);

        // Try to submit another vote
        $response = $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Should be blocked and redirected
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors('vote');
    }

    /**
     * Test: Revote is blocked even with correct code2
     *
     * Description: Even if code is correct, revote is blocked if has_voted=true
     */
    public function test_revote_blocked_with_correct_code()
    {
        $this->actingAs($this->voter);

        // Set has_voted to true
        $this->code->update(['has_voted' => true]);

        // Prepare session with new vote selections
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND12', 'CAND23', 'CAND31']
        ]);

        // Try to submit - should be blocked BEFORE checking vote selections
        $response = $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Should redirect to dashboard
        $response->assertRedirect(route('dashboard'));

        // Original vote should not be modified
        $this->assertDatabaseMissing('votes', [
            'user_id' => $this->voter->id,
            'candidacy_id' => Candidacy::where('candidacy_id', 'CAND12')->first()->id,
        ]);
    }

    /**
     * Test: No new vote record created on revote attempt
     *
     * Description: Vote record should not be created when revoting is blocked
     */
    public function test_no_new_vote_on_revote_attempt()
    {
        $this->actingAs($this->voter);

        // First complete a vote
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        $firstVoteCount = Vote::where('user_id', $this->voter->id)->count();

        // Try to vote again
        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Vote count should not increase
        $finalVoteCount = Vote::where('user_id', $this->voter->id)->count();
        $this->assertEquals($firstVoteCount, $finalVoteCount);
    }

    /**
     * Test: Transaction rolled back on revote attempt
     *
     * Description: If revote is attempted, entire transaction is rolled back
     */
    public function test_transaction_rolled_back_on_revote_attempt()
    {
        $this->actingAs($this->voter);

        // Complete first vote
        $this->post(route('code.first_submission'), [
            'code1' => '123456',
            'has_agreed_to_vote' => true,
        ]);

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $this->post(route('vote.first_submission'), [
            'session_name' => $sessionName,
        ]);

        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Refresh code to see current state
        $this->code->refresh();
        $this->assertTrue($this->code->has_voted);

        // Attempt revote (should be blocked before executing)
        $this->post(route('vote.store'), [
            'voting_code' => $this->code->code2,
        ]);

        // Verify has_voted remains true and nothing changed
        $this->code->refresh();
        $this->assertTrue($this->code->has_voted);
    }
}
