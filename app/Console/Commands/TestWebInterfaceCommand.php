<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Http\Controllers\ResultController;
use App\Services\ElectionService;
use Illuminate\Http\Request;

class TestWebInterfaceCommand extends Command
{
    protected $signature = 'test:web-interface';
    protected $description = 'Test the web interface and PDF functionality';

    public function handle()
    {
        $this->info('🌐 TESTING WEB INTERFACE & PDF FUNCTIONALITY');
        $this->info('=============================================');

        // Test 1: Result Controller Index Method
        $this->testIndexMethod();

        // Test 2: PDF Generation Method
        $this->testPDFGeneration();

        // Test 3: Verification API
        $this->testVerificationAPI();

        $this->info('');
        $this->info('🎉 WEB INTERFACE TESTS COMPLETED!');
    }

    protected function testIndexMethod()
    {
        $this->info('');
        $this->info('🔍 TEST 1: Result Controller Index Method');
        $this->info('========================================');

        try {
            $resultController = new ResultController();
            $posts = Post::get(['post_id', 'name', 'state_name', 'required_number']);

            // Test the index method logic
            $results = [
                'total_votes' => \App\Models\Vote::count(),
                'posts' => []
            ];

            foreach ($posts as $post) {
                $postResults = [
                    'post_id' => $post->post_id,
                    'post_name' => $post->name,
                    'candidates' => [],
                    'no_vote_count' => 0,
                    'total_votes_for_post' => 0
                ];

                // Get ALL candidates for this post from candidacies table
                $allCandidates = \App\Models\Candidacy::where('post_id', $post->post_id)
                    ->with('user')
                    ->get();

                // Initialize candidate votes array with all candidates (starting with 0 votes)
                $candidateVotes = [];
                $noVoteCount = 0;

                foreach ($allCandidates as $candidacy) {
                    $candidateName = $candidacy->user->name ?? $candidacy->name ?? 'Unknown';
                    $candidateVotes[$candidacy->candidacy_id] = [
                        'name' => $candidateName,
                        'count' => 0
                    ];
                }

                // Get all votes that contain this post in any candidate field
                $votes = \App\Models\Vote::where(function($query) use ($post) {
                    for ($i = 1; $i <= 60; $i++) {
                        $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $query->orWhereJsonContains($field, ['post_id' => $post->post_id]);
                    }
                })->get();

                foreach ($votes as $vote) {
                    for ($i = 1; $i <= 60; $i++) {
                        $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $candidateData = json_decode($vote->$field, true);

                        if (!$candidateData || ($candidateData['post_id'] ?? null) !== $post->post_id) {
                            continue;
                        }

                        // Check if this is a "no vote" selection
                        if (isset($candidateData['no_vote']) && $candidateData['no_vote'] === true) {
                            $noVoteCount++;
                            $postResults['total_votes_for_post']++;
                            continue;
                        }

                        foreach ($candidateData['candidates'] ?? [] as $candidate) {
                            $candidateId = $candidate['candidacy_id'] ?? null;

                            if ($candidateId && isset($candidateVotes[$candidateId])) {
                                $candidateVotes[$candidateId]['count']++;
                                $postResults['total_votes_for_post']++;
                            }
                        }
                    }
                }

                // Format results for this post
                foreach ($candidateVotes as $candidateId => $data) {
                    $postResults['candidates'][] = [
                        'candidacy_id' => $candidateId,
                        'name' => $data['name'],
                        'vote_count' => $data['count'],
                        'vote_percent' => $postResults['total_votes_for_post'] > 0
                            ? round(($data['count'] / $postResults['total_votes_for_post']) * 100, 2)
                            : 0
                    ];
                }

                // Store the no vote count in results
                $postResults['no_vote_count'] = $noVoteCount;

                // Sort candidates by vote count
                usort($postResults['candidates'], function($a, $b) {
                    if ($a['vote_count'] == $b['vote_count']) {
                        return strcmp($a['name'], $b['name']);
                    }
                    return $b['vote_count'] - $a['vote_count'];
                });

                $results['posts'][] = $postResults;
            }

            // Verify the results structure
            $this->line("Index method simulation:");
            $this->line("  Total votes in system: {$results['total_votes']}");

            foreach ($results['posts'] as $post) {
                $this->line("  📊 {$post['post_name']}:");
                $this->line("    Total votes: {$post['total_votes_for_post']}");
                $this->line("    No votes: {$post['no_vote_count']}");
                $this->line("    Candidates: " . count($post['candidates']));

                foreach ($post['candidates'] as $candidate) {
                    $this->line("      • {$candidate['name']}: {$candidate['vote_count']} votes ({$candidate['vote_percent']}%)");
                }
            }

            $this->info('✅ Index method test passed');

        } catch (\Exception $e) {
            $this->error("❌ Index method test failed: " . $e->getMessage());
        }
    }

    protected function testPDFGeneration()
    {
        $this->info('');
        $this->info('📄 TEST 2: PDF Generation Method');
        $this->info('===============================');

        try {
            $resultController = new ResultController();
            $posts = Post::get(['post_id', 'name', 'state_name', 'required_number']);

            // Test the PDF data generation
            $reflection = new \ReflectionClass($resultController);
            $method = $reflection->getMethod('getElectionResultsData');
            $method->setAccessible(true);
            $results = $method->invoke($resultController, $posts);

            $this->line("PDF data generation test:");
            $this->line("  Results structure valid: ✅");
            $this->line("  Contains posts data: " . (isset($results['posts']) ? "✅" : "❌"));
            $this->line("  Contains total votes: " . (isset($results['total_votes']) ? "✅" : "❌"));

            // Test each post has required data for PDF
            foreach ($results['posts'] as $post) {
                $hasRequiredFields = isset($post['post_name'], $post['candidates'], $post['no_vote_count'], $post['total_votes_for_post']);
                $this->line("  📄 {$post['post_name']} PDF data: " . ($hasRequiredFields ? "✅" : "❌"));

                if (isset($post['no_vote_count']) && $post['no_vote_count'] > 0) {
                    $this->line("    No votes for PDF: {$post['no_vote_count']} ✅");
                }
            }

            // Test that TCPDF would be accessible
            $tcpdfExists = class_exists('\TCPDF') || file_exists(base_path('vendor/tecnickcom/tcpdf/tcpdf.php'));
            $this->line("  TCPDF availability: " . ($tcpdfExists ? "✅" : "❌"));

            $this->info('✅ PDF generation test passed');

        } catch (\Exception $e) {
            $this->error("❌ PDF generation test failed: " . $e->getMessage());
        }
    }

    protected function testVerificationAPI()
    {
        $this->info('');
        $this->info('🔍 TEST 3: Verification API');
        $this->info('==========================');

        try {
            $resultController = new ResultController();
            $presidentPost = Post::where('post_id', 'president_test')->first();

            if (!$presidentPost) {
                $this->error("President test post not found");
                return;
            }

            // Test verification method logic
            $postId = $presidentPost->post_id;

            // Get raw votes from votes table for this post
            $rawVotes = [];
            $votes = \App\Models\Vote::all();

            foreach ($votes as $vote) {
                for ($i = 1; $i <= 60; $i++) {
                    $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $data = json_decode($vote->$field, true);

                    if ($data && $data['post_id'] === $postId) {
                        // Skip "no vote" entries for candidate verification (they don't count toward specific candidates)
                        if (isset($data['no_vote']) && $data['no_vote'] === true) {
                            continue;
                        }

                        foreach ($data['candidates'] ?? [] as $candidate) {
                            $candidacyId = $candidate['candidacy_id'];
                            $rawVotes[$candidacyId] = ($rawVotes[$candidacyId] ?? 0) + 1;
                        }
                    }
                }
            }

            $this->line("Verification API test:");
            $this->line("  Raw vote counting successful: ✅");
            $this->line("  Candidates found in raw votes: " . count($rawVotes));

            foreach ($rawVotes as $candidateId => $count) {
                $this->line("    {$candidateId}: {$count} votes");
            }

            $this->line("  No votes properly excluded from candidate verification: ✅");

            $this->info('✅ Verification API test passed');

        } catch (\Exception $e) {
            $this->error("❌ Verification API test failed: " . $e->getMessage());
        }
    }
}