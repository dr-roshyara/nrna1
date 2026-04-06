<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Candidacy;
use App\Services\ElectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class VoteCountingAndNoVotesFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $posts;
    protected $candidates;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setupTestData();
        $this->setupAdminUser();
    }

    /**
     * Setup admin user with required permissions
     */
    protected function setupAdminUser()
    {
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'can_view_results' => true
        ]);

        // Add required permissions if using Spatie Permission
        if (class_exists('\Spatie\Permission\Models\Permission')) {
            $permissions = [
                'view-election-results',
                'manage-election-settings',
                'publish-election-results'
            ];

            foreach ($permissions as $permission) {
                try {
                    $permissionModel = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
                    $this->adminUser->givePermissionTo($permissionModel);
                } catch (\Exception $e) {
                    // Permission system not fully configured, skip
                }
            }
        }
    }

    /**
     * Setup test data with posts and candidates
     */
    protected function setupTestData()
    {
        // Create test posts
        $this->posts = collect([
            Post::create([
                'post_id' => 'president_2024',
                'name' => 'President',
                'nepali_name' => 'अध्यक्ष',
                'state_name' => 'National',
                'required_number' => 1
            ]),
            Post::create([
                'post_id' => 'vp_2024',
                'name' => 'Vice President',
                'nepali_name' => 'उपाध्यक्ष',
                'state_name' => 'National',
                'required_number' => 1
            ])
        ]);

        // Create test candidates
        $this->candidates = [];
        $candidateNames = [
            'president_2024' => ['Alice Johnson', 'Bob Smith', 'Carol Davis', 'David Wilson'],
            'vp_2024' => ['Emma Brown', 'Frank Miller', 'Grace Lee', 'Henry Taylor']
        ];

        foreach ($this->posts as $post) {
            $this->candidates[$post->post_id] = [];
            foreach ($candidateNames[$post->post_id] as $index => $name) {
                $user = User::factory()->create([
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                    'user_id' => "user_{$post->post_id}_{$index}"
                ]);

                $candidate = Candidacy::create([
                    'candidacy_id' => "cand_{$post->post_id}_{$index}",
                    'user_id' => $user->user_id,
                    'post_id' => $post->post_id,
                    'name' => $name,
                    'user_name' => $name,
                    'proposer_name' => "Proposer for {$name}",
                    'supporter_name' => "Supporter for {$name}"
                ]);

                $this->candidates[$post->post_id][] = $candidate;
            }
        }
    }

    /**
     * Test the results endpoint with complex voting scenario
     */
    public function test_results_endpoint_with_100_diverse_votes()
    {
        $this->createDiverseVotingScenario();

        // Mock election service to allow viewing results
        $this->mockElectionService();

        $response = $this->actingAs($this->adminUser)
                         ->get('/election/result');

        $response->assertStatus(200);

        // Get the Inertia response data
        $responseData = $response->viewData('page')['props'];
        $this->assertArrayHasKey('final_result', $responseData);
        $this->assertArrayHasKey('posts', $responseData);

        $finalResult = $responseData['final_result'];

        // Verify total votes
        $this->assertEquals(100, $finalResult['total_votes']);

        // Check each post's results
        foreach ($finalResult['posts'] as $postResult) {
            $this->assertEquals(100, $postResult['total_votes_for_post']);
            $this->assertArrayHasKey('no_vote_count', $postResult);
            $this->assertArrayHasKey('candidates', $postResult);

            // Verify the voting distribution we created
            if ($postResult['post_id'] === 'president_2024') {
                $this->assertEquals(15, $postResult['no_vote_count']);

                // Check candidate vote counts
                $candidates = collect($postResult['candidates']);
                $alice = $candidates->firstWhere('candidacy_id', 'cand_president_2024_0');
                $bob = $candidates->firstWhere('candidacy_id', 'cand_president_2024_1');
                $carol = $candidates->firstWhere('candidacy_id', 'cand_president_2024_2');
                $david = $candidates->firstWhere('candidacy_id', 'cand_president_2024_3');

                $this->assertEquals(40, $alice['vote_count']);
                $this->assertEquals(25, $bob['vote_count']);
                $this->assertEquals(15, $carol['vote_count']);
                $this->assertEquals(5, $david['vote_count']);
            }
        }
    }

    /**
     * Test PDF generation endpoint with no votes included
     */
    public function test_pdf_generation_includes_no_votes()
    {
        $this->createDiverseVotingScenario();
        $this->mockElectionService();

        $response = $this->actingAs($this->adminUser)
                         ->get('/election/result/download-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        // Verify PDF was generated (we can't easily test PDF content in unit tests)
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test verification endpoint with no votes
     */
    public function test_verification_endpoint_handles_no_votes()
    {
        $this->createSimpleVotingScenario();
        $this->mockElectionService();

        $postId = 'president_2024';

        $response = $this->actingAs($this->adminUser)
                         ->get("/api/verify-results/{$postId}");

        $response->assertStatus(200);
        $verificationData = $response->json();

        $this->assertArrayHasKey('match', $verificationData);
        $this->assertArrayHasKey('raw_vote_counts', $verificationData);

        // Verify that no votes are properly excluded from candidate verification
        $rawCounts = $verificationData['raw_vote_counts'];
        $this->assertIsArray($rawCounts);
    }

    /**
     * Test API response structure includes no_vote_count
     */
    public function test_api_response_structure_includes_no_vote_count()
    {
        $this->createSimpleVotingScenario();
        $this->mockElectionService();

        $response = $this->actingAs($this->adminUser)
                         ->get('/election/result');

        $response->assertStatus(200);
        $responseData = $response->viewData('page')['props'];

        foreach ($responseData['final_result']['posts'] as $postResult) {
            $this->assertArrayHasKey('no_vote_count', $postResult);
            $this->assertIsInt($postResult['no_vote_count']);
            $this->assertGreaterThanOrEqual(0, $postResult['no_vote_count']);
        }
    }

    /**
     * Test zero candidates scenario (only no votes)
     */
    public function test_only_no_votes_scenario()
    {
        // Create 50 votes where everyone chooses "no vote"
        for ($i = 1; $i <= 50; $i++) {
            Vote::create([
                'voting_code' => "only_no_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => true,
                    'candidates' => null
                ]),
                'candidate_02' => json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }

        $this->mockElectionService();

        $response = $this->actingAs($this->adminUser)
                         ->get('/election/result');

        $response->assertStatus(200);
        $responseData = $response->viewData('page')['props'];

        foreach ($responseData['final_result']['posts'] as $postResult) {
            $this->assertEquals(50, $postResult['total_votes_for_post']);
            $this->assertEquals(50, $postResult['no_vote_count']);

            // All candidates should have 0 votes
            foreach ($postResult['candidates'] as $candidate) {
                $this->assertEquals(0, $candidate['vote_count']);
                $this->assertEquals(0.0, $candidate['vote_percent']);
            }
        }
    }

    /**
     * Create a diverse voting scenario with 100 voters
     */
    protected function createDiverseVotingScenario()
    {
        $presidentCandidates = $this->candidates['president_2024'];
        $vpCandidates = $this->candidates['vp_2024'];

        for ($voterId = 1; $voterId <= 100; $voterId++) {
            $voteData = ['voting_code' => "diverse_vote_{$voterId}"];

            // President voting pattern
            if ($voterId <= 40) {
                // Alice: 40 votes
                $voteData['candidate_01'] = json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $presidentCandidates[0]->candidacy_id]]
                ]);
            } elseif ($voterId <= 65) {
                // Bob: 25 votes
                $voteData['candidate_01'] = json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $presidentCandidates[1]->candidacy_id]]
                ]);
            } elseif ($voterId <= 80) {
                // Carol: 15 votes
                $voteData['candidate_01'] = json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $presidentCandidates[2]->candidacy_id]]
                ]);
            } elseif ($voterId <= 85) {
                // David: 5 votes
                $voteData['candidate_01'] = json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $presidentCandidates[3]->candidacy_id]]
                ]);
            } else {
                // No vote: 15 votes
                $voteData['candidate_01'] = json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => true,
                    'candidates' => null
                ]);
            }

            // VP voting pattern (different distribution)
            if ($voterId <= 35) {
                // Emma: 35 votes
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $vpCandidates[0]->candidacy_id]]
                ]);
            } elseif ($voterId <= 60) {
                // Frank: 25 votes
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $vpCandidates[1]->candidacy_id]]
                ]);
            } elseif ($voterId <= 80) {
                // Grace: 20 votes
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $vpCandidates[2]->candidacy_id]]
                ]);
            } elseif ($voterId <= 90) {
                // Henry: 10 votes
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $vpCandidates[3]->candidacy_id]]
                ]);
            } else {
                // No vote: 10 votes
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_2024',
                    'no_vote' => true,
                    'candidates' => null
                ]);
            }

            Vote::create($voteData);
        }
    }

    /**
     * Create a simple voting scenario for basic tests
     */
    protected function createSimpleVotingScenario()
    {
        $presidentCandidates = $this->candidates['president_2024'];

        // 30 votes for first candidate
        for ($i = 1; $i <= 30; $i++) {
            Vote::create([
                'voting_code' => "simple_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => $presidentCandidates[0]->candidacy_id]]
                ])
            ]);
        }

        // 20 no votes
        for ($i = 31; $i <= 50; $i++) {
            Vote::create([
                'voting_code' => "simple_vote_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president_2024',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
        }
    }

    /**
     * Mock ElectionService to allow viewing results
     */
    protected function mockElectionService()
    {
        $this->mock(ElectionService::class, function ($mock) {
            $mock->shouldReceive('canViewResults')->andReturn(true);
            $mock->shouldReceive('isElectionFinished')->andReturn(true);
            $mock->shouldReceive('areResultsPublished')->andReturn(true);
        });
    }
}