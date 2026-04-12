<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Vote;
use App\Models\DemoVote;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * TDD Unit Tests for No-Vote Database Storage Bug
 *
 * Bug: When user selects "no_vote" in voting form:
 * 1. no_vote_option is saved as FALSE (0) instead of TRUE (1)
 * 2. When no_vote=true, candidates are still being stored in database
 *
 * @group vote-no-vote
 * @group database-storage
 * @group tdd
 */
class NoVoteOptionDatabaseStorageTest extends TestCase
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
    public function demo_vote_with_no_vote_true_saves_true_to_database()
    {
        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'demo_test_' . uniqid(),
            'verification_code' => bin2hex(random_bytes(16)),
            'user_id' => null,
            'no_vote_option' => true,
        ]);

        $retrieved = DemoVote::find($demoVote->id);

        $this->assertTrue(
            (bool) $retrieved->no_vote_option,
            'no_vote_option should be TRUE (1) in database, but got: ' . $retrieved->no_vote_option
        );
    }

    /** @test */
    public function can_query_votes_by_no_vote_status()
    {
        DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'query1_' . uniqid(),
            'verification_code' => bin2hex(random_bytes(16)),
            'no_vote_option' => true,
        ]);

        DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'query2_' . uniqid(),
            'verification_code' => bin2hex(random_bytes(16)),
            'no_vote_option' => false,
        ]);

        $noVoteCount = DemoVote::where('no_vote_option', 1)->count();
        $withVoteCount = DemoVote::where('no_vote_option', 0)->count();

        $this->assertEquals(1, $noVoteCount, 'Should find 1 vote with no_vote_option=1');
        $this->assertEquals(1, $withVoteCount, 'Should find 1 vote with no_vote_option=0');
    }

    /** @test */
    public function save_vote_method_respects_no_vote_option_from_input()
    {
        // This tests the actual save_vote() controller method
        // which should extract and set no_vote_option from input data

        $input_data = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
            'no_vote_option' => true, // User selected "no vote"
        ];

        $hashed_key = hash('sha256', 'test_key_' . uniqid());
        $controller = new \App\Http\Controllers\VoteController();

        // Call save_vote with no_vote_option = true
        $controller->save_vote($input_data, $hashed_key, $this->demoElection);

        // Retrieve the saved vote
        $savedVote = DemoVote::where('voting_code', $hashed_key)->first();

        // Assert: no_vote_option should be true (1), not false (0)
        $this->assertNotNull($savedVote, 'Vote should be saved');
        $this->assertTrue(
            (bool) $savedVote->no_vote_option,
            'no_vote_option should be TRUE (1) when user selects no_vote, but got: ' . $savedVote->no_vote_option
        );
    }
}
