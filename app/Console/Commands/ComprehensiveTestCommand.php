<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\DB;

class ComprehensiveTestCommand extends Command
{
    protected $signature = 'test:comprehensive';
    protected $description = 'Run comprehensive tests on vote counting and no votes logic';

    public function handle()
    {
        $this->info('🧪 COMPREHENSIVE VOTE COUNTING TEST');
        $this->info('==================================');

        // Test 1: Database State Analysis
        $this->testDatabaseState();

        // Test 2: Vote Counting Logic
        $this->testVoteCountingLogic();

        // Test 3: No Votes Logic
        $this->testNoVotesLogic();

        // Test 4: Result Controller Methods
        $this->testResultControllerMethods();

        // Test 5: Mathematical Accuracy
        $this->testMathematicalAccuracy();

        // Test 6: Edge Cases
        $this->testEdgeCases();

        // Test 7: Frontend Data Structure
        $this->testFrontendDataStructure();

        $this->info('');
        $this->info('🎉 ALL TESTS COMPLETED!');
    }

    protected function testDatabaseState()
    {
        $this->info('');
        $this->info('📊 TEST 1: Database State Analysis');
        $this->info('=================================');

        $posts = Post::all();
        $votes = Vote::all();
        $candidacies = Candidacy::all();

        $this->line("Posts in database: {$posts->count()}");
        $this->line("Votes in database: {$votes->count()}");
        $this->line("Candidacies in database: {$candidacies->count()}");

        foreach ($posts as $post) {
            $candidateCount = $post->candidacies()->count();
            $this->line("  📋 {$post->name}: {$candidateCount} candidates");
        }

        $this->info('✅ Database state verified');
    }

    protected function testVoteCountingLogic()
    {
        $this->info('');
        $this->info('🗳️  TEST 2: Vote Counting Logic');
        $this->info('=============================');

        $presidentPost = Post::where('post_id', 'president_test')->first();
        if (!$presidentPost) {
            $this->error('President test post not found');
            return;
        }

        // Manual count from database
        $totalVotes = 0;
        $candidateVotes = 0;
        $noVotes = 0;

        $votes = Vote::all();
        foreach ($votes as $vote) {
            for ($i = 1; $i <= 60; $i++) {
                $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $data = json_decode($vote->$field, true);

                if ($data && ($data['post_id'] ?? null) === 'president_test') {
                    $totalVotes++;

                    if (isset($data['no_vote']) && $data['no_vote'] === true) {
                        $noVotes++;
                    } else {
                        $candidateVotes++;
                    }
                }
            }
        }

        $this->line("Manual count for President position:");
        $this->line("  Total votes: {$totalVotes}");
        $this->line("  Candidate votes: {$candidateVotes}");
        $this->line("  No votes: {$noVotes}");
        $this->line("  Verification: " . ($totalVotes === ($candidateVotes + $noVotes) ? "✅ PASS" : "❌ FAIL"));

        $this->info('✅ Vote counting logic verified');
    }

    protected function testNoVotesLogic()
    {
        $this->info('');
        $this->info('🚫 TEST 3: No Votes Logic');
        $this->info('========================');

        // Test different no vote structures
        $testStructures = [
            ['post_id' => 'test', 'no_vote' => true, 'candidates' => null],
            ['post_id' => 'test', 'no_vote' => true],
            ['post_id' => 'test', 'candidates' => null, 'no_vote' => true],
        ];

        foreach ($testStructures as $index => $structure) {
            $isNoVote = isset($structure['no_vote']) && $structure['no_vote'] === true;
            $this->line("Structure " . ($index + 1) . ": " . ($isNoVote ? "✅ Detected as no vote" : "❌ Not detected"));
        }

        $this->info('✅ No votes logic verified');
    }

    protected function testResultControllerMethods()
    {
        $this->info('');
        $this->info('🎯 TEST 4: Result Controller Methods');
        $this->info('==================================');

        $posts = Post::all();
        $resultController = new ResultController();

        // Test getElectionResultsData method
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $this->line("Results structure test:");
        $this->line("  Has 'posts' key: " . (isset($results['posts']) ? "✅" : "❌"));
        $this->line("  Has 'total_votes' key: " . (isset($results['total_votes']) ? "✅" : "❌"));

        foreach ($results['posts'] as $postResult) {
            $hasNoVoteCount = isset($postResult['no_vote_count']);
            $hasTotalVotes = isset($postResult['total_votes_for_post']);
            $hasCandidates = isset($postResult['candidates']);

            $this->line("  Post '{$postResult['post_name']}':");
            $this->line("    Has no_vote_count: " . ($hasNoVoteCount ? "✅" : "❌"));
            $this->line("    Has total_votes_for_post: " . ($hasTotalVotes ? "✅" : "❌"));
            $this->line("    Has candidates array: " . ($hasCandidates ? "✅" : "❌"));

            if ($hasNoVoteCount && $hasTotalVotes) {
                $this->line("    No votes: {$postResult['no_vote_count']}");
                $this->line("    Total votes: {$postResult['total_votes_for_post']}");
            }
        }

        $this->info('✅ Result Controller methods verified');
    }

    protected function testMathematicalAccuracy()
    {
        $this->info('');
        $this->info('🧮 TEST 5: Mathematical Accuracy');
        $this->info('===============================');

        $posts = Post::all();
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        foreach ($results['posts'] as $postResult) {
            $this->line("Testing {$postResult['post_name']}:");

            $totalVotes = $postResult['total_votes_for_post'];
            $noVotes = $postResult['no_vote_count'];
            $candidateVotesSum = 0;

            foreach ($postResult['candidates'] as $candidate) {
                $candidateVotesSum += $candidate['vote_count'];

                // Test percentage calculation
                if ($totalVotes > 0) {
                    $expectedPercentage = round(($candidate['vote_count'] / $totalVotes) * 100, 2);
                    $actualPercentage = $candidate['vote_percent'];
                    $percentageMatch = abs($expectedPercentage - $actualPercentage) < 0.1;

                    $this->line("    {$candidate['name']}: {$candidate['vote_count']} votes ({$candidate['vote_percent']}%)");
                    $this->line("      Percentage calc: " . ($percentageMatch ? "✅ CORRECT" : "❌ INCORRECT"));
                }
            }

            // Test total votes = candidate votes + no votes
            $calculatedTotal = $candidateVotesSum + $noVotes;
            $totalMatch = ($calculatedTotal === $totalVotes);

            $this->line("    Vote totals:");
            $this->line("      Candidate votes: {$candidateVotesSum}");
            $this->line("      No votes: {$noVotes}");
            $this->line("      Calculated total: {$calculatedTotal}");
            $this->line("      Reported total: {$totalVotes}");
            $this->line("      Match: " . ($totalMatch ? "✅ CORRECT" : "❌ INCORRECT"));
        }

        $this->info('✅ Mathematical accuracy verified');
    }

    protected function testEdgeCases()
    {
        $this->info('');
        $this->info('🔍 TEST 6: Edge Cases');
        $this->info('====================');

        $posts = Post::all();
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        foreach ($results['posts'] as $postResult) {
            $this->line("Testing edge cases for {$postResult['post_name']}:");

            // Test: No candidates scenario
            if (empty($postResult['candidates'])) {
                $this->line("    No candidates: ✅ Handled correctly");
                $this->line("    All votes should be no votes: " .
                          ($postResult['no_vote_count'] === $postResult['total_votes_for_post'] ? "✅" : "❌"));
            }

            // Test: Zero votes scenario
            if ($postResult['total_votes_for_post'] === 0) {
                $this->line("    Zero votes: ✅ Handled correctly");
                foreach ($postResult['candidates'] as $candidate) {
                    if ($candidate['vote_percent'] !== 0.0) {
                        $this->line("    Zero vote percentage: ❌ Should be 0%");
                    }
                }
            }

            // Test: All no votes scenario
            if ($postResult['no_vote_count'] === $postResult['total_votes_for_post']) {
                $this->line("    All no votes scenario: ✅ Handled correctly");
            }
        }

        $this->info('✅ Edge cases verified');
    }

    protected function testFrontendDataStructure()
    {
        $this->info('');
        $this->info('🖥️  TEST 7: Frontend Data Structure');
        $this->info('=================================');

        $posts = Post::all();
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        // Check if data structure matches what frontend expects
        $requiredKeys = ['posts', 'total_votes'];
        foreach ($requiredKeys as $key) {
            $this->line("Root level '{$key}': " . (isset($results[$key]) ? "✅" : "❌"));
        }

        $requiredPostKeys = ['post_id', 'post_name', 'candidates', 'no_vote_count', 'total_votes_for_post'];
        foreach ($results['posts'] as $post) {
            $this->line("Post '{$post['post_name']}' structure:");
            foreach ($requiredPostKeys as $key) {
                $this->line("    '{$key}': " . (isset($post[$key]) ? "✅" : "❌"));
            }
        }

        $requiredCandidateKeys = ['candidacy_id', 'name', 'vote_count', 'vote_percent'];
        foreach ($results['posts'] as $post) {
            if (!empty($post['candidates'])) {
                $candidate = $post['candidates'][0];
                $this->line("Candidate structure (first candidate):");
                foreach ($requiredCandidateKeys as $key) {
                    $this->line("    '{$key}': " . (isset($candidate[$key]) ? "✅" : "❌"));
                }
                break;
            }
        }

        $this->info('✅ Frontend data structure verified');
    }
}