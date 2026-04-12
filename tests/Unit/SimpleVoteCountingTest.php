<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleVoteCountingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_simple_vote_counting_with_no_votes()
    {
        $this->artisan('migrate');

        // Create a simple post
        $post = new Post();
        $post->post_id = 'test_post';
        $post->name = 'Test Position';
        $post->nepali_name = 'परीक्षण पद';
        $post->state_name = 'Test State';
        $post->required_number = 1;
        $post->save();

        // Create a simple user and candidate
        $user = User::create([
            'name' => 'Test Candidate',
            'email' => 'test@example.com',
            'user_id' => 'test_user_1',
            'password' => bcrypt('password')
        ]);

        $candidate = new Candidacy();
        $candidate->candidacy_id = 'test_candidate_1';
        $candidate->user_id = $user->user_id;
        $candidate->post_id = 'test_post';
        $candidate->name = $user->name;
        $candidate->user_name = $user->name;
        $candidate->proposer_name = 'Test Proposer';
        $candidate->supporter_name = 'Test Supporter';
        $candidate->save();

        // Create 10 votes for the candidate
        for ($i = 1; $i <= 10; $i++) {
            Vote::create([
                'voting_code' => "test_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'test_post',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 'test_candidate_1']
                    ]
                ])
            ]);
        }

        // Create 5 "no votes"
        for ($i = 11; $i <= 15; $i++) {
            Vote::create([
                'voting_code' => "test_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'test_post',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }

        // Test the vote counting
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $posts = collect([$post]);
        $results = $method->invoke($resultController, $posts);

        // Verify results
        $this->assertNotEmpty($results['posts']);
        $postResult = $results['posts'][0];

        $this->assertEquals('test_post', $postResult['post_id']);
        $this->assertEquals('Test Position', $postResult['post_name']);
        $this->assertEquals(15, $postResult['total_votes_for_post']); // 10 candidate votes + 5 no votes
        $this->assertEquals(5, $postResult['no_vote_count']);

        // Check candidate results
        $this->assertNotEmpty($postResult['candidates']);
        $candidateResult = $postResult['candidates'][0];
        $this->assertEquals('test_candidate_1', $candidateResult['candidacy_id']);
        $this->assertEquals(10, $candidateResult['vote_count']);
        $this->assertEquals(66.67, $candidateResult['vote_percent']); // 10/15 * 100 = 66.67
    }
}