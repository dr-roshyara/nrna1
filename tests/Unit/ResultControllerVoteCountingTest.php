<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ResultControllerVoteCountingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $resultController;
    protected $posts;
    protected $candidates;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->resultController = new ResultController();
        $this->setupTestData();
    }

    /**
     * Setup test data with posts and candidates
     */
    protected function setupTestData()
    {
        // Create test posts
        $this->posts = collect([
            Post::create([
                'post_id' => 'president',
                'name' => 'President',
                'nepali_name' => 'अध्यक्ष',
                'state_name' => 'National',
                'required_number' => 1
            ]),
            Post::create([
                'post_id' => 'vice_president',
                'name' => 'Vice President',
                'nepali_name' => 'उपाध्यक्ष',
                'state_name' => 'National',
                'required_number' => 1
            ]),
            Post::create([
                'post_id' => 'secretary',
                'name' => 'Secretary',
                'nepali_name' => 'सचिव',
                'state_name' => 'Regional',
                'required_number' => 1
            ])
        ]);

        // Create test users and candidates
        $this->candidates = [];

        foreach ($this->posts as $post) {
            for ($i = 1; $i <= 4; $i++) {
                $user = User::factory()->create([
                    'name' => "Candidate {$i} for {$post->name}",
                    'email' => "candidate{$i}_{$post->post_id}@example.com",
                    'user_id' => "user_{$post->post_id}_{$i}"
                ]);

                $candidate = Candidacy::create([
                    'candidacy_id' => "cand_{$post->post_id}_{$i}",
                    'user_id' => $user->user_id,
                    'post_id' => $post->post_id,
                    'name' => $user->name,
                    'user_name' => $user->name,
                    'proposer_name' => "Proposer {$i}",
                    'supporter_name' => "Supporter {$i}"
                ]);

                $this->candidates[$post->post_id][] = $candidate;
            }
        }
    }

    /**
     * Test vote counting with regular candidate votes
     */
    public function test_vote_counting_with_candidate_votes()
    {
        // Create votes for president position
        $presidentPost = $this->posts->where('post_id', 'president')->first();
        $presidentCandidates = $this->candidates['president'];

        // Vote for candidate 1: 30 votes
        for ($i = 0; $i < 30; $i++) {
            Vote::create([
                'voting_code' => "vote_code_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $presidentCandidates[0]->candidacy_id]
                    ]
                ])
            ]);
        }

        // Vote for candidate 2: 25 votes
        for ($i = 30; $i < 55; $i++) {
            Vote::create([
                'voting_code' => "vote_code_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $presidentCandidates[1]->candidacy_id]
                    ]
                ])
            ]);
        }

        // Get results using the private method
        $reflection = new \ReflectionClass($this->resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);
        $results = $method->invoke($this->resultController, $this->posts);

        // Assert results
        $presidentResults = collect($results['posts'])->firstWhere('post_id', 'president');

        $this->assertNotNull($presidentResults);
        $this->assertEquals(55, $presidentResults['total_votes_for_post']);
        $this->assertEquals(0, $presidentResults['no_vote_count']);

        // Check candidate vote counts
        $candidate1Result = collect($presidentResults['candidates'])
            ->firstWhere('candidacy_id', $presidentCandidates[0]->candidacy_id);
        $candidate2Result = collect($presidentResults['candidates'])
            ->firstWhere('candidacy_id', $presidentCandidates[1]->candidacy_id);

        $this->assertEquals(30, $candidate1Result['vote_count']);
        $this->assertEquals(25, $candidate2Result['vote_count']);
    }

    /**
     * Test vote counting with "no vote" selections
     */
    public function test_vote_counting_with_no_votes()
    {
        $presidentPost = $this->posts->where('post_id', 'president')->first();
        $presidentCandidates = $this->candidates['president'];

        // Vote for candidate 1: 20 votes
        for ($i = 0; $i < 20; $i++) {
            Vote::create([
                'voting_code' => "vote_code_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $presidentCandidates[0]->candidacy_id]
                    ]
                ])
            ]);
        }

        // No votes: 15 votes
        for ($i = 20; $i < 35; $i++) {
            Vote::create([
                'voting_code' => "vote_code_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }

        // Get results
        $reflection = new \ReflectionClass($this->resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);
        $results = $method->invoke($this->resultController, $this->posts);

        $presidentResults = collect($results['posts'])->firstWhere('post_id', 'president');

        $this->assertEquals(35, $presidentResults['total_votes_for_post']);
        $this->assertEquals(15, $presidentResults['no_vote_count']);

        $candidate1Result = collect($presidentResults['candidates'])
            ->firstWhere('candidacy_id', $presidentCandidates[0]->candidacy_id);
        $this->assertEquals(20, $candidate1Result['vote_count']);
    }

    /**
     * Test comprehensive scenario with 100 voters across multiple posts
     */
    public function test_comprehensive_100_voter_scenario()
    {
        // Scenario: 100 voters, 3 positions, various voting patterns
        for ($voterId = 1; $voterId <= 100; $voterId++) {
            $voteData = [];
            $fieldIndex = 1;

            foreach ($this->posts as $postIndex => $post) {
                $field = 'candidate_' . str_pad($fieldIndex, 2, '0', STR_PAD_LEFT);
                $candidates = $this->candidates[$post->post_id];

                // Different voting patterns based on voter ID
                if ($voterId <= 40) {
                    // First 40 voters: vote for first candidate
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $candidates[0]->candidacy_id]
                        ]
                    ]);
                } elseif ($voterId <= 70) {
                    // Next 30 voters: vote for second candidate
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $candidates[1]->candidacy_id]
                        ]
                    ]);
                } elseif ($voterId <= 85) {
                    // Next 15 voters: vote for third candidate
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $candidates[2]->candidacy_id]
                        ]
                    ]);
                } elseif ($voterId <= 95) {
                    // Next 10 voters: no vote
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => true,
                        'candidates' => null
                    ]);
                } else {
                    // Last 5 voters: vote for fourth candidate
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $candidates[3]->candidacy_id]
                        ]
                    ]);
                }

                $fieldIndex++;
            }

            $voteData['voting_code'] = "comprehensive_vote_$voterId";
            Vote::create($voteData);
        }

        // Get results
        $reflection = new \ReflectionClass($this->resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);
        $results = $method->invoke($this->resultController, $this->posts);

        // Verify results for each post
        foreach ($this->posts as $post) {
            $postResults = collect($results['posts'])->firstWhere('post_id', $post->post_id);
            $candidates = $this->candidates[$post->post_id];

            // Each post should have 100 total votes
            $this->assertEquals(100, $postResults['total_votes_for_post']);
            $this->assertEquals(10, $postResults['no_vote_count']); // 10 no votes per post

            // Check individual candidate counts
            $candidate1 = collect($postResults['candidates'])
                ->firstWhere('candidacy_id', $candidates[0]->candidacy_id);
            $candidate2 = collect($postResults['candidates'])
                ->firstWhere('candidacy_id', $candidates[1]->candidacy_id);
            $candidate3 = collect($postResults['candidates'])
                ->firstWhere('candidacy_id', $candidates[2]->candidacy_id);
            $candidate4 = collect($postResults['candidates'])
                ->firstWhere('candidacy_id', $candidates[3]->candidacy_id);

            $this->assertEquals(40, $candidate1['vote_count'], "Candidate 1 should have 40 votes");
            $this->assertEquals(30, $candidate2['vote_count'], "Candidate 2 should have 30 votes");
            $this->assertEquals(15, $candidate3['vote_count'], "Candidate 3 should have 15 votes");
            $this->assertEquals(5, $candidate4['vote_count'], "Candidate 4 should have 5 votes");

            // Verify total adds up: 40 + 30 + 15 + 5 + 10 (no votes) = 100
            $totalCandidateVotes = $candidate1['vote_count'] + $candidate2['vote_count'] +
                                 $candidate3['vote_count'] + $candidate4['vote_count'];
            $this->assertEquals(90, $totalCandidateVotes);
            $this->assertEquals(100, $totalCandidateVotes + $postResults['no_vote_count']);
        }
    }

    /**
     * Test edge cases and mixed scenarios
     */
    public function test_edge_cases_and_mixed_scenarios()
    {
        // Test 1: All voters choose "no vote"
        for ($i = 1; $i <= 20; $i++) {
            Vote::create([
                'voting_code' => "no_vote_only_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }

        // Test 2: Mixed votes across multiple candidate fields
        for ($i = 21; $i <= 30; $i++) {
            Vote::create([
                'voting_code' => "mixed_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $this->candidates['president'][0]->candidacy_id]
                    ]
                ]),
                'candidate_02' => json_encode([
                    'post_id' => 'vice_president',
                    'no_vote' => true,
                    'candidates' => null
                ]),
                'candidate_03' => json_encode([
                    'post_id' => 'secretary',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $this->candidates['secretary'][1]->candidacy_id]
                    ]
                ])
            ]);
        }

        // Get results
        $reflection = new \ReflectionClass($this->resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);
        $results = $method->invoke($this->resultController, $this->posts);

        // Verify "no vote only" scenario
        $presidentResults = collect($results['posts'])->firstWhere('post_id', 'president');
        $this->assertEquals(30, $presidentResults['total_votes_for_post']); // 20 no votes + 10 candidate votes
        $this->assertEquals(20, $presidentResults['no_vote_count']);

        // Verify mixed scenario for vice president (10 no votes)
        $vpResults = collect($results['posts'])->firstWhere('post_id', 'vice_president');
        $this->assertEquals(10, $vpResults['total_votes_for_post']);
        $this->assertEquals(10, $vpResults['no_vote_count']);

        // Verify mixed scenario for secretary (10 candidate votes)
        $secretaryResults = collect($results['posts'])->firstWhere('post_id', 'secretary');
        $this->assertEquals(10, $secretaryResults['total_votes_for_post']);
        $this->assertEquals(0, $secretaryResults['no_vote_count']);
    }

    /**
     * Test percentage calculations with no votes
     */
    public function test_percentage_calculations_with_no_votes()
    {
        $candidates = $this->candidates['president'];

        // Create specific vote distribution:
        // Candidate 1: 50 votes
        // Candidate 2: 30 votes
        // No votes: 20 votes
        // Total: 100 votes

        // Candidate 1: 50 votes
        for ($i = 0; $i < 50; $i++) {
            Vote::create([
                'voting_code' => "perc_test_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $candidates[0]->candidacy_id]
                    ]
                ])
            ]);
        }

        // Candidate 2: 30 votes
        for ($i = 50; $i < 80; $i++) {
            Vote::create([
                'voting_code' => "perc_test_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $candidates[1]->candidacy_id]
                    ]
                ])
            ]);
        }

        // No votes: 20 votes
        for ($i = 80; $i < 100; $i++) {
            Vote::create([
                'voting_code' => "perc_test_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }

        // Get results
        $reflection = new \ReflectionClass($this->resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);
        $results = $method->invoke($this->resultController, $this->posts);

        $presidentResults = collect($results['posts'])->firstWhere('post_id', 'president');

        $this->assertEquals(100, $presidentResults['total_votes_for_post']);
        $this->assertEquals(20, $presidentResults['no_vote_count']);

        $candidate1Result = collect($presidentResults['candidates'])
            ->firstWhere('candidacy_id', $candidates[0]->candidacy_id);
        $candidate2Result = collect($presidentResults['candidates'])
            ->firstWhere('candidacy_id', $candidates[1]->candidacy_id);

        // Percentages should be calculated based on total votes (including no votes)
        $this->assertEquals(50.0, $candidate1Result['vote_percent']);
        $this->assertEquals(30.0, $candidate2Result['vote_percent']);
    }
}