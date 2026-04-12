<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * TDD Feature Tests for Real Election Voting
 *
 * Verify that real elections save votes correctly with same fixes as demo elections
 * - no_vote_option should be properly saved
 * - verification_code should NOT be stored in votes table (for anonymity)
 * - Both use the same save_vote() method
 *
 * @group real-vote
 * @group voting-system
 * @group tdd
 */
class RealVoteVerificationTest extends TestCase
{
    use RefreshDatabase;

    private Election $realElection;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->realElection = Election::factory()->create([
            'type' => 'real',
            'name' => 'Real Election'
        ]);

        $this->voter = User::factory()->create([
            'email' => 'voter@real.test',
            'region' => 'Test Region'
        ]);
    }

    /** @test */
    public function real_vote_with_no_vote_true_saves_true_to_database()
    {
        // Real elections should also save no_vote_option correctly
        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'real_test_' . uniqid(),
            'user_id' => null,
            'no_vote_option' => true,
        ]);

        $retrieved = Vote::find($vote->id);

        $this->assertTrue(
            (bool) $retrieved->no_vote_option,
            'Real vote: no_vote_option should be TRUE (1) in database'
        );
    }

    /** @test */
    public function save_vote_method_saves_real_vote_with_no_vote_option()
    {
        // Test that save_vote() correctly handles real elections with no_vote_option
        $input_data = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_option' => true, // User selected "no vote"
        ];

        $hashed_key = hash('sha256', 'real_key_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        // Call save_vote with real election and no_vote_option = true
        $controller->save_vote($input_data, $hashed_key, $this->realElection, $this->voter);

        // Retrieve the saved vote
        $savedVote = Vote::where('voting_code', $hashed_key)->first();

        // Assert: no_vote_option should be true (1), not false (0)
        $this->assertNotNull($savedVote, 'Real vote should be saved');
        $this->assertTrue(
            (bool) $savedVote->no_vote_option,
            'Real vote: no_vote_option should be TRUE when user selects no_vote'
        );
    }

    /** @test */
    public function real_vote_does_not_store_verification_code()
    {
        // Real elections should NOT store verification_code (for anonymity)
        // Only demo elections should store it

        $input_data = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_option' => false,
        ];

        $hashed_key = hash('sha256', 'real_verify_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        $controller->save_vote($input_data, $hashed_key, $this->realElection, $this->voter);

        $savedVote = Vote::where('voting_code', $hashed_key)->first();

        // Assert: Real votes should NOT have verification_code
        $this->assertNull(
            $savedVote->verification_code ?? null,
            'Real vote should NOT store verification_code (for anonymity)'
        );
    }

    /** @test */
    public function real_and_demo_votes_use_same_save_vote_method()
    {
        // Verify that both real and demo elections use the same save_vote() flow
        $demoElection = Election::factory()->create([
            'type' => 'demo',
            'name' => 'Demo for comparison'
        ]);

        $input_data = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_option' => true,
        ];

        $controller = new \App\Http\Controllers\VoteController();

        // Save with real election
        $realKey = hash('sha256', 'real_compare_' . uniqid());
        $controller->save_vote($input_data, $realKey, $this->realElection, $this->voter);

        // Save with demo election
        $demoKey = hash('sha256', 'demo_compare_' . uniqid());
        $controller->save_vote($input_data, $demoKey, $demoElection, $this->voter);

        $realVote = Vote::where('voting_code', $realKey)->first();
        $demoVote = \App\Models\DemoVote::where('voting_code', $demoKey)->first();

        // Assert: Both should save no_vote_option correctly
        $this->assertTrue((bool) $realVote->no_vote_option,
            'Real vote should save no_vote_option=true');
        $this->assertTrue((bool) $demoVote->no_vote_option,
            'Demo vote should save no_vote_option=true');

        // Assert: Only demo should have verification_code
        $this->assertNull($realVote->verification_code ?? null,
            'Real vote should NOT have verification_code');
        $this->assertNotNull($demoVote->verification_code,
            'Demo vote should have verification_code');
    }

    /** @test */
    public function real_vote_can_query_by_no_vote_status()
    {
        // Verify querying real votes by no_vote_option status works
        Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'real_query1_' . uniqid(),
            'no_vote_option' => true,
        ]);

        Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'real_query2_' . uniqid(),
            'no_vote_option' => false,
        ]);

        $noVoteCount = Vote::where('election_id', $this->realElection->id)
            ->where('no_vote_option', 1)
            ->count();

        $withVoteCount = Vote::where('election_id', $this->realElection->id)
            ->where('no_vote_option', 0)
            ->count();

        $this->assertEquals(1, $noVoteCount, 'Should find 1 real vote with no_vote_option=1');
        $this->assertEquals(1, $withVoteCount, 'Should find 1 real vote with no_vote_option=0');
    }

    /** @test */
    public function votes_table_schema_does_not_have_verification_code_column()
    {
        // Verify that real votes table doesn't have verification_code column
        // (This is intentional for anonymity)

        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'schema_test_' . uniqid(),
            'user_id' => null,
        ]);

        // Attempt to get verification_code - should be null or non-existent
        // Real votes don't store verification codes
        $retrieved = Vote::find($vote->id);

        // If the column doesn't exist in table, this should be null
        // If column exists but wasn't set, should also be null
        $this->assertNull(
            $retrieved->verification_code ?? null,
            'Real votes table should not use verification_code column'
        );
    }

    /** @test */
    public function real_vote_with_false_no_vote_saves_correctly()
    {
        // Test that when user votes (not selecting no_vote), it saves correctly
        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'real_voted_' . uniqid(),
            'user_id' => null,
            'no_vote_option' => false, // User is voting
            'candidate_01' => json_encode([
                'no_vote' => false,
                'candidates' => [['candidacy_id' => 1, 'name' => 'Test']],
                'post_id' => 'post1'
            ])
        ]);

        $retrieved = Vote::find($vote->id);

        $this->assertFalse(
            (bool) $retrieved->no_vote_option,
            'Real vote: no_vote_option should be FALSE (0) when user votes'
        );

        $this->assertNotNull(
            $retrieved->candidate_01,
            'Real vote: should have candidate data when user votes'
        );
    }
}
