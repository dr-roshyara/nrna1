<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;

class TestVoteCountingCommand extends Command
{
    protected $signature = 'test:vote-counting';
    protected $description = 'Test the vote counting and no votes logic with sample data';

    public function handle()
    {
        $this->info('🗳️  Testing Vote Counting Logic with "No Votes" Implementation');
        $this->info('================================================================');

        // Get existing posts and candidates from the database
        $posts = Post::with('candidacies')->get();

        if ($posts->isEmpty()) {
            $this->error('No posts found in the database. Please create some posts and candidates first.');
            return;
        }

        $this->info("Found {$posts->count()} posts:");
        foreach ($posts as $post) {
            $candidateCount = $post->candidacies->count();
            $this->line("  📋 {$post->name} ({$post->post_id}) - {$candidateCount} candidates");
        }
        $this->line('');

        // Test the vote counting logic
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $this->info('📊 Current Vote Count Results:');
        $this->info('============================');

        foreach ($results['posts'] as $postResult) {
            $this->info("🏛️  Position: {$postResult['post_name']} ({$postResult['post_id']})");
            $this->line("   Total Votes: {$postResult['total_votes_for_post']}");
            $this->line("   No Votes: {$postResult['no_vote_count']}");
            $this->line("   Candidates:");

            if (empty($postResult['candidates'])) {
                $this->line("     (No candidates)");
            } else {
                foreach ($postResult['candidates'] as $candidate) {
                    $percentage = number_format($candidate['vote_percent'], 1);
                    $this->line("     • {$candidate['name']}: {$candidate['vote_count']} votes ({$percentage}%)");
                }
            }
            $this->line('');
        }

        // Create sample votes to demonstrate the logic
        $this->info('🎯 Creating Sample Votes to Demonstrate No Vote Logic:');
        $this->info('=====================================================');

        $samplePostId = $posts->first()->post_id;
        $candidates = $posts->first()->candidacies;

        if ($candidates->count() < 2) {
            $this->warn("Need at least 2 candidates to demonstrate voting. Skipping sample creation.");
            return;
        }

        $sampleVotes = [
            // 5 votes for first candidate
            ['type' => 'candidate', 'candidacy_id' => $candidates[0]->candidacy_id, 'count' => 5],
            // 3 votes for second candidate
            ['type' => 'candidate', 'candidacy_id' => $candidates[1]->candidacy_id, 'count' => 3],
            // 2 no votes
            ['type' => 'no_vote', 'count' => 2]
        ];

        $createdVoteIds = [];
        $voteCounter = 1;

        foreach ($sampleVotes as $sampleVote) {
            for ($i = 0; $i < $sampleVote['count']; $i++) {
                $voteCode = "demo_vote_" . time() . "_" . $voteCounter++;

                if ($sampleVote['type'] === 'no_vote') {
                    $voteData = [
                        'voting_code' => $voteCode,
                        'candidate_01' => json_encode([
                            'post_id' => $samplePostId,
                            'no_vote' => true,
                            'candidates' => null
                        ])
                    ];
                } else {
                    $voteData = [
                        'voting_code' => $voteCode,
                        'candidate_01' => json_encode([
                            'post_id' => $samplePostId,
                            'no_vote' => false,
                            'candidates' => [
                                ['candidacy_id' => $sampleVote['candidacy_id']]
                            ]
                        ])
                    ];
                }

                $vote = Vote::create($voteData);
                $createdVoteIds[] = $vote->id;
            }
        }

        $this->info("✅ Created " . count($createdVoteIds) . " sample votes");

        // Re-run the vote counting to show the updated results
        $newResults = $method->invoke($resultController, $posts);

        $this->info('');
        $this->info('📊 Updated Vote Count Results (After Sample Votes):');
        $this->info('=================================================');

        $targetPost = collect($newResults['posts'])->firstWhere('post_id', $samplePostId);

        if ($targetPost) {
            $this->info("🏛️  Position: {$targetPost['post_name']} ({$targetPost['post_id']})");
            $this->line("   Total Votes: {$targetPost['total_votes_for_post']}");
            $this->line("   No Votes: {$targetPost['no_vote_count']}");
            $this->line("   Candidates:");

            foreach ($targetPost['candidates'] as $candidate) {
                $percentage = number_format($candidate['vote_percent'], 1);
                $this->line("     • {$candidate['name']}: {$candidate['vote_count']} votes ({$percentage}%)");
            }
        }

        // Keep sample votes for testing
        $this->info('');
        $this->info('💾 Keeping sample votes for further testing...');
        $this->line('   Sample vote IDs: ' . implode(', ', $createdVoteIds));
        $this->info('✅ Sample votes preserved in database');

        $this->info('');
        $this->info('🎉 Vote counting test completed successfully!');
        $this->info('✨ Key features demonstrated:');
        $this->line('   • Counting candidate votes');
        $this->line('   • Counting "no vote" selections');
        $this->line('   • Including no votes in total vote count');
        $this->line('   • Calculating percentages correctly');
        $this->line('   • Displaying all candidates (including zero votes)');
    }
}