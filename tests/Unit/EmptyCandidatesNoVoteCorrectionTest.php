<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Election;
use App\Models\DemoVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * TDD Test: Empty candidates array with no_vote=false should be corrected to no_vote=true
 *
 * This tests the specific bug where:
 * - User selects no candidates for a position (empty array)
 * - System stores no_vote=false in JSON
 * - EXPECTED: Should be corrected to no_vote=true
 *
 * @group vote-no-vote
 * @group json-correction
 * @group tdd
 */
class EmptyCandidatesNoVoteCorrectionTest extends TestCase
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
    public function candidate_json_with_empty_candidates_array_should_have_no_vote_true()
    {
        // Arrange: Simulate vote data with empty candidates array but no_vote=false
        // This is the bug - candidates is empty [] but no_vote says false
        $input_data = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [],  // Empty! User selected no one
                    'no_vote' => false,  // BUG: Should be true
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [
                [
                    'post_id' => 'secretary-9',
                    'post_name' => 'Secretary',
                    'candidates' => [
                        ['candidacy_id' => 2, 'name' => 'Grace Lee']  // Has candidate
                    ],
                    'no_vote' => false,
                    'required_number' => 1
                ]
            ],
            'no_vote_option' => false
        ];

        $hashed_key = hash('sha256', 'empty_cand_test_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        // Act: Save the vote
        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null);

        // Get the saved vote
        $savedVote = DemoVote::where('voting_code', $hashed_key)->first();
        $this->assertNotNull($savedVote, 'Vote should be saved');

        // Decode the JSON stored in candidate_01 (President with empty candidates)
        $candidate_01 = json_decode($savedVote->candidate_01, true);

        // Assert: no_vote should be corrected to TRUE (1)
        $this->assertTrue(
            (bool) $candidate_01['no_vote'],
            'When candidates array is empty, no_vote should be TRUE. ' .
            'But got: ' . json_encode($candidate_01)
        );

        // Assert: candidates should still be empty array
        $this->assertEmpty(
            $candidate_01['candidates'],
            'Candidates array should remain empty'
        );
    }

    /** @test */
    public function candidate_json_with_candidates_should_have_no_vote_false()
    {
        // Test the opposite: when candidates ARE selected, no_vote should be false
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

        $hashed_key = hash('sha256', 'with_cand_test_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null);

        $savedVote = DemoVote::where('voting_code', $hashed_key)->first();
        $candidate_01 = json_decode($savedVote->candidate_01, true);

        // Assert: no_vote should be FALSE when candidates exist
        $this->assertFalse(
            (bool) $candidate_01['no_vote'],
            'When candidates array has items, no_vote should be FALSE'
        );

        // Assert: candidates should not be empty
        $this->assertNotEmpty(
            $candidate_01['candidates'],
            'Candidates array should not be empty'
        );
    }

    /** @test */
    public function multiple_positions_each_corrected_independently()
    {
        // Test that each position is corrected independently
        // Position 1: Empty candidates → should correct to no_vote=true
        // Position 2: Has candidate → should be no_vote=false
        // Position 3: Empty candidates → should correct to no_vote=true

        $input_data = [
            'national_selected_candidates' => [
                [
                    'post_id' => 'president-9',
                    'post_name' => 'President',
                    'candidates' => [],  // Empty
                    'no_vote' => false,
                    'required_number' => 1
                ],
                [
                    'post_id' => 'vice-president-9',
                    'post_name' => 'Vice President',
                    'candidates' => [['candidacy_id' => 2, 'name' => 'Bob']],  // Has candidate
                    'no_vote' => false,
                    'required_number' => 1
                ],
                [
                    'post_id' => 'secretary-9',
                    'post_name' => 'Secretary',
                    'candidates' => [],  // Empty
                    'no_vote' => false,
                    'required_number' => 1
                ]
            ],
            'regional_selected_candidates' => [],
            'no_vote_option' => false
        ];

        $hashed_key = hash('sha256', 'multi_pos_test_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        $controller->save_vote($input_data, $hashed_key, $this->demoElection, null);

        $savedVote = DemoVote::where('voting_code', $hashed_key)->first();

        // Position 1: President (empty) should have no_vote=true
        $candidate_01 = json_decode($savedVote->candidate_01, true);
        $this->assertTrue(
            (bool) $candidate_01['no_vote'],
            'Position 1 (President, empty candidates) should have no_vote=true'
        );

        // Position 2: Vice President (has candidate) should have no_vote=false
        $candidate_02 = json_decode($savedVote->candidate_02, true);
        $this->assertFalse(
            (bool) $candidate_02['no_vote'],
            'Position 2 (Vice President, has candidate) should have no_vote=false'
        );

        // Position 3: Secretary (empty) should have no_vote=true
        $candidate_03 = json_decode($savedVote->candidate_03, true);
        $this->assertTrue(
            (bool) $candidate_03['no_vote'],
            'Position 3 (Secretary, empty candidates) should have no_vote=true'
        );
    }
}
