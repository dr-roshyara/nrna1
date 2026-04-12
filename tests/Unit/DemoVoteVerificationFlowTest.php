<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Election;
use App\Models\DemoVote;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * TDD Test: Demo Vote Verification Flow
 *
 * Verifies that users can successfully retrieve their votes using the code
 * they received via email after voting.
 *
 * The voting_code sent to email should be: private_key_vote_id
 * Example: "102b46c7eac757cc8ec4f56df8473fac_1"
 *
 * @group vote-verification
 * @group demo-voting
 * @group tdd
 */
class DemoVoteVerificationFlowTest extends TestCase
{
    use RefreshDatabase;

    private Election $demoElection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->demoElection = Election::factory()->create([
            'type' => 'demo',
            'name' => 'Demo Election'
        ]);
    }

    /** @test */
    public function demo_vote_voting_code_is_concatenated_with_vote_id()
    {
        // Arrange: Simulate vote data
        $input_data = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [],
                    'no_vote' => true,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $private_key = bin2hex(random_bytes(16)); // 32-char hex string
        $hashed_key = password_hash($private_key, PASSWORD_BCRYPT);

        // Act: Save the vote
        $controller = new \App\Http\Controllers\VoteController();
        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null, $private_key);

        // Get the saved vote
        $demoVote = DemoVote::orderBy('id', 'desc')->first();
        $this->assertNotNull($demoVote, 'Vote should be saved');

        // Assert: voting_code should contain both private_key and vote_id
        $expectedConcatenatedCode = $private_key . '_' . $demoVote->id;

        $this->assertEquals(
            $expectedConcatenatedCode,
            $demoVote->voting_code,
            'Demo voting_code should be plain concatenation of private_key_vote_id. ' .
            'Got: ' . $demoVote->voting_code . ' | Expected: ' . $expectedConcatenatedCode
        );
    }

    /** @test */
    public function demo_vote_can_be_found_by_voting_code()
    {
        // Arrange: Create and save a vote
        $input_data = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [
                        ['candidacy_id' => 1, 'name' => 'Alice']
                    ],
                    'no_vote' => false,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $private_key = bin2hex(random_bytes(16));
        $hashed_key = password_hash($private_key, PASSWORD_BCRYPT);

        $controller = new \App\Http\Controllers\VoteController();
        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null, $private_key);

        $savedVote = DemoVote::orderBy('id', 'desc')->first();
        $votingCode = $savedVote->voting_code;

        // Act: Query vote using the voting_code (as user would when verifying)
        $foundVote = DemoVote::where('voting_code', $votingCode)->first();

        // Assert: Vote should be found
        $this->assertNotNull($foundVote, 'Vote should be found by voting_code');
        $this->assertEquals($savedVote->id, $foundVote->id, 'Found vote should match the saved vote');
        $this->assertEquals($votingCode, $foundVote->voting_code, 'Voting code should match');
    }

    /** @test */
    public function different_demo_votes_have_different_voting_codes()
    {
        // Arrange: Create two votes
        $input_data1 = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [],
                    'no_vote' => true,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $input_data2 = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [
                        ['candidacy_id' => 2, 'name' => 'Bob']
                    ],
                    'no_vote' => false,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $private_key1 = bin2hex(random_bytes(16));
        $private_key2 = bin2hex(random_bytes(16));
        $hashed_key1 = password_hash($private_key1, PASSWORD_BCRYPT);
        $hashed_key2 = password_hash($private_key2, PASSWORD_BCRYPT);

        $controller = new \App\Http\Controllers\VoteController();

        // Act: Save both votes
        $controller->save_vote($input_data1, $hashed_key1, $this->demoElection, null, $private_key1);
        $vote1 = DemoVote::orderBy('id', 'desc')->first();

        $controller->save_vote($input_data2, $hashed_key2, $this->demoElection, null, $private_key2);
        $vote2 = DemoVote::orderBy('id', 'desc')->first();

        // Assert: Voting codes should be different
        $this->assertNotEquals(
            $vote1->voting_code,
            $vote2->voting_code,
            'Different votes should have different voting codes (uniqueness preserved by vote_id)'
        );

        // Verify vote_ids are different
        $this->assertNotEquals($vote1->id, $vote2->id, 'Votes should have different IDs');
    }

    /** @test */
    public function demo_vote_with_no_candidates_can_be_retrieved_by_voting_code()
    {
        // Arrange: Create a vote with empty candidates
        $input_data = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [],
                    'no_vote' => true,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $private_key = bin2hex(random_bytes(16));
        $hashed_key = password_hash($private_key, PASSWORD_BCRYPT);

        $controller = new \App\Http\Controllers\VoteController();
        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null, $private_key);

        $savedVote = DemoVote::orderBy('id', 'desc')->first();

        // Act: Retrieve vote using voting_code (simulating user verification)
        $foundVote = DemoVote::where('voting_code', $savedVote->voting_code)->first();

        // Assert: Vote should be retrievable despite having empty candidates
        $this->assertNotNull($foundVote, 'Vote with empty candidates should be retrievable');
        $this->assertEquals($savedVote->id, $foundVote->id);

        // Verify the vote data was stored correctly
        $candidate_01 = json_decode($foundVote->candidate_01, true);
        $this->assertTrue(
            (bool)$candidate_01['no_vote'],
            'Vote with empty candidates should have no_vote=true'
        );
    }
}
