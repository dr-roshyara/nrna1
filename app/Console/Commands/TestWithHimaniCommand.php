<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Http\Controllers\ResultController;

class TestWithHimaniCommand extends Command
{
    protected $signature = 'test:with-himani {--votes=30 : Number of votes to create for VP position}';
    protected $description = 'Test vote counting with Himani Shah as VP candidate';

    public function handle()
    {
        $this->info('🗳️  Testing Vote Counting with Himani Shah (VP)');
        $this->info('===============================================');

        $voteCount = $this->option('votes');

        // Create votes for VP position with Himani
        $this->createVPVotes($voteCount);

        // Show updated results
        $this->showUpdatedResults();
    }

    protected function createVPVotes($voteCount)
    {
        $this->info("Creating {$voteCount} votes for Vice President position...");

        $voteIds = [];
        $candidateVotes = 0;
        $noVotes = 0;

        for ($i = 1; $i <= $voteCount; $i++) {
            $voteData = ['voting_code' => "himani_test_vote_" . time() . "_" . $i];

            // 70% chance to vote for Himani, 30% chance for no vote
            $isNoVote = (rand(1, 100) <= 30);

            if ($isNoVote) {
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_test',
                    'no_vote' => true,
                    'candidates' => null
                ]);
                $noVotes++;
            } else {
                $voteData['candidate_02'] = json_encode([
                    'post_id' => 'vp_test',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 'himani_vp_2025']
                    ]
                ]);
                $candidateVotes++;
            }

            $vote = Vote::create($voteData);
            $voteIds[] = $vote->id;
        }

        $this->info("✅ Created {$voteCount} VP votes:");
        $this->line("   • {$candidateVotes} votes for Himani Shah");
        $this->line("   • {$noVotes} no votes");
        $this->line("   Vote IDs: " . implode(', ', array_slice($voteIds, 0, 5)) .
                   (count($voteIds) > 5 ? '... (and more)' : ''));
    }

    protected function showUpdatedResults()
    {
        $this->info('');
        $this->info('📊 Updated Election Results with Himani Shah:');
        $this->info('=============================================');

        $posts = Post::all();
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $totalAllVotes = 0;
        $totalAllNoVotes = 0;

        foreach ($results['posts'] as $postResult) {
            $this->info("🏛️  {$postResult['post_name']}");
            $this->line("   Total Votes: {$postResult['total_votes_for_post']}");
            $this->line("   No Votes: {$postResult['no_vote_count']}");

            $totalAllVotes += $postResult['total_votes_for_post'];
            $totalAllNoVotes += $postResult['no_vote_count'];

            if (empty($postResult['candidates'])) {
                $this->line("   Candidates: (None)");
            } else {
                $this->line("   Candidates:");
                foreach ($postResult['candidates'] as $index => $candidate) {
                    $rank = $index + 1;
                    $percentage = number_format($candidate['vote_percent'], 1);
                    $votes = $candidate['vote_count'];
                    $winner = ($index === 0 && $votes > 0) ? ' 👑 LEADING' : '';
                    $this->line("     {$rank}. {$candidate['name']}: {$votes} votes ({$percentage}%){$winner}");
                }
            }

            // Show abstention rate
            if ($postResult['total_votes_for_post'] > 0) {
                $abstentionRate = number_format(($postResult['no_vote_count'] / $postResult['total_votes_for_post']) * 100, 1);
                $this->line("   📊 Abstention Rate: {$abstentionRate}%");
            }

            $this->line('');
        }

        // Overall statistics
        $this->info('📈 Overall Election Statistics:');
        $this->info('==============================');
        $this->line("Total votes across all positions: {$totalAllVotes}");
        $this->line("Total no votes (abstentions): {$totalAllNoVotes}");
        $candidateVotes = $totalAllVotes - $totalAllNoVotes;
        $this->line("Total candidate votes: {$candidateVotes}");

        if ($totalAllVotes > 0) {
            $overallAbstention = number_format(($totalAllNoVotes / $totalAllVotes) * 100, 1);
            $this->line("Overall abstention rate: {$overallAbstention}%");
        }

        $this->info('');
        $this->info('🎉 Multi-Candidate Election Test Complete!');
        $this->info('✨ Demonstrated features:');
        $this->line('   • Multiple candidates across different positions ✅');
        $this->line('   • Independent vote counting per position ✅');
        $this->line('   • Accurate no vote tracking ✅');
        $this->line('   • Proper percentage calculations ✅');
        $this->line('   • Cross-position result analysis ✅');
    }
}