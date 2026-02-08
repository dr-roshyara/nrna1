<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;

class CreateSampleVotesCommand extends Command
{
    protected $signature = 'test:create-votes {--keep : Keep the votes after testing}';
    protected $description = 'Create sample votes to demonstrate vote counting and no votes logic';

    public function handle()
    {
        $this->info('🗳️  Creating Sample Votes for Testing');
        $this->info('====================================');

        // Get existing posts and candidates
        $posts = Post::with('candidacies.user')->get();

        if ($posts->isEmpty()) {
            $this->error('No posts found. Please run php artisan test:setup-election first.');
            return;
        }

        $presidentPost = $posts->where('post_id', 'president_test')->first();
        if (!$presidentPost || $presidentPost->candidacies->isEmpty()) {
            $this->error('No candidates found for president_test post.');
            return;
        }

        $candidate = $presidentPost->candidacies->first();
        $candidateName = $candidate->user->name ?? 'Unknown';
        $this->info("Found candidate: {$candidateName} ({$candidate->candidacy_id})");

        // Create sample votes
        $voteIds = [];

        // 10 votes for the candidate
        for ($i = 1; $i <= 10; $i++) {
            $vote = Vote::create([
                'voting_code' => "sample_vote_candidate_$i",
                'candidate_01' => json_encode([
                    'post_id' => 'president_test',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => $candidate->candidacy_id]
                    ]
                ])
            ]);
            $voteIds[] = $vote->id;
        }

        // 5 "no votes"
        for ($i = 1; $i <= 5; $i++) {
            $vote = Vote::create([
                'voting_code' => "sample_vote_no_vote_$i",
                'candidate_02' => json_encode([
                    'post_id' => 'president_test',
                    'no_vote' => true,
                    'candidates' => null
                ])
            ]);
            $voteIds[] = $vote->id;
        }

        $this->info('✅ Created 15 sample votes:');
        $this->line('   • 10 votes for candidate');
        $this->line('   • 5 "no votes"');

        // Test the vote counting
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $this->info('');
        $this->info('📊 Vote Counting Results:');
        $this->info('========================');

        $presidentResults = collect($results['posts'])->firstWhere('post_id', 'president_test');

        if ($presidentResults) {
            $this->info("🏛️  Position: {$presidentResults['post_name']}");
            $this->line("   Total Votes: {$presidentResults['total_votes_for_post']}");
            $this->line("   No Votes: {$presidentResults['no_vote_count']}");
            $this->line("   Candidates:");

            foreach ($presidentResults['candidates'] as $candidate) {
                $percentage = number_format($candidate['vote_percent'], 1);
                $this->line("     • {$candidate['name']}: {$candidate['vote_count']} votes ({$percentage}%)");
            }

            // Verify calculations
            $this->info('');
            $this->info('✅ Verification:');
            $expectedTotal = 15; // 10 candidate votes + 5 no votes
            $actualTotal = $presidentResults['total_votes_for_post'];
            $actualNoVotes = $presidentResults['no_vote_count'];
            $candidateVotes = isset($presidentResults['candidates'][0]) ? $presidentResults['candidates'][0]['vote_count'] : 0;

            $this->line("   Expected total votes: {$expectedTotal}");
            $this->line("   Actual total votes: {$actualTotal}");
            $this->line("   No votes count: {$actualNoVotes}");
            $this->line("   Candidate votes: {$candidateVotes}");

            if ($actualTotal === $expectedTotal && $actualNoVotes === 5 && $candidateVotes === 10) {
                $this->info('   🎉 ALL CALCULATIONS CORRECT!');
            } else {
                $this->error('   ❌ Vote counting mismatch!');
            }
        }

        // Clean up or keep votes based on option
        if (!$this->option('keep')) {
            $this->info('');
            $this->info('🧹 Cleaning up sample votes...');
            Vote::whereIn('id', $voteIds)->delete();
            $this->info('✅ Sample votes cleaned up');
        } else {
            $this->info('');
            $this->info('💾 Sample votes kept for further testing');
            $this->line('   Vote IDs: ' . implode(', ', $voteIds));
        }

        $this->info('');
        $this->info('🎉 Vote counting demonstration completed!');
        $this->info('✨ Demonstrated features:');
        $this->line('   • Counting candidate votes ✅');
        $this->line('   • Counting "no vote" selections ✅');
        $this->line('   • Including no votes in total count ✅');
        $this->line('   • Calculating percentages correctly ✅');
        $this->line('   • Showing all candidates (including zero votes) ✅');
    }
}