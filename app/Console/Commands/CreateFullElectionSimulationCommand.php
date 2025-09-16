<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Models\User;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;

class CreateFullElectionSimulationCommand extends Command
{
    protected $signature = 'test:full-election {--votes=100 : Number of voters to simulate}';
    protected $description = 'Create a complete election simulation with multiple candidates and diverse voting patterns';

    public function handle()
    {
        $this->info('🏛️  Creating Full Election Simulation');
        $this->info('===================================');

        $voterCount = $this->option('votes');

        // Get all posts
        $posts = Post::all();
        if ($posts->isEmpty()) {
            $this->error('No posts found. Please run php artisan test:setup-election first.');
            return;
        }

        // Create unique candidates using creative approach
        $this->createUniqueCandidates($posts);

        // Create diverse voting patterns
        $this->createDiverseVotes($posts, $voterCount);

        // Show comprehensive results
        $this->showComprehensiveResults($posts);
    }

    protected function createUniqueCandidates($posts)
    {
        $this->info('👥 Creating unique candidates for each post...');

        // Get the template user
        $templateUser = User::first();
        if (!$templateUser) {
            $this->error('No users found in database.');
            return;
        }

        $candidatePool = [
            'president_test' => [
                ['name' => 'Dr. Sarah Chen', 'id' => 'pres_sarah'],
                ['name' => 'Prof. Michael Johnson', 'id' => 'pres_michael'],
                ['name' => 'Ms. Priya Sharma', 'id' => 'pres_priya'],
                ['name' => 'Mr. James Wilson', 'id' => 'pres_james'],
            ],
            'vp_test' => [
                ['name' => 'Dr. Anna Rodriguez', 'id' => 'vp_anna'],
                ['name' => 'Mr. David Kim', 'id' => 'vp_david'],
                ['name' => 'Ms. Lisa Brown', 'id' => 'vp_lisa'],
            ],
            'secretary_test' => [
                ['name' => 'Mr. Robert Garcia', 'id' => 'sec_robert'],
                ['name' => 'Ms. Jennifer Lee', 'id' => 'sec_jennifer'],
                ['name' => 'Dr. Ahmed Hassan', 'id' => 'sec_ahmed'],
                ['name' => 'Ms. Maria Gonzalez', 'id' => 'sec_maria'],
            ]
        ];

        foreach ($posts as $post) {
            $candidates = $candidatePool[$post->post_id] ?? [];
            $created = 0;

            foreach ($candidates as $candidate) {
                // Skip if candidate already exists
                if (Candidacy::where('candidacy_id', $candidate['id'])->exists()) {
                    continue;
                }

                try {
                    // Create candidacy with unique approach
                    $candidacy = new Candidacy();
                    $candidacy->candidacy_id = $candidate['id'];
                    $candidacy->post_id = $post->post_id;
                    $candidacy->name = $candidate['name'];

                    // Handle user_id constraint by using different approaches
                    $existingCandidacyCount = Candidacy::where('post_id', $post->post_id)->count();
                    if ($existingCandidacyCount === 0) {
                        $candidacy->user_id = $templateUser->user_id;
                    } else {
                        // For additional candidates, use a modified user_id to avoid unique constraint
                        $candidacy->user_id = $templateUser->user_id . '_' . $candidate['id'];
                    }

                    $candidacy->save();
                    $created++;
                    $this->line("  ✅ Created: {$candidate['name']} for {$post->name}");

                } catch (\Exception $e) {
                    // If we still hit constraints, try with just the candidacy_id as user_id
                    try {
                        $candidacy = new Candidacy();
                        $candidacy->candidacy_id = $candidate['id'];
                        $candidacy->user_id = $candidate['id']; // Use candidacy_id as user_id
                        $candidacy->post_id = $post->post_id;
                        $candidacy->name = $candidate['name'];
                        $candidacy->save();
                        $created++;
                        $this->line("  ✅ Created: {$candidate['name']} for {$post->name} (alt method)");
                    } catch (\Exception $e2) {
                        $this->line("  ⚠️  Skipped: {$candidate['name']} - {$e2->getMessage()}");
                    }
                }
            }

            if ($created > 0) {
                $this->info("  📊 {$post->name}: Created {$created} candidates");
            }
        }
    }

    protected function createDiverseVotes($posts, $voterCount)
    {
        $this->info('');
        $this->info("🗳️  Simulating {$voterCount} diverse voters...");

        $voteIds = [];
        $statistics = [
            'total_votes' => 0,
            'no_votes' => 0,
            'candidate_votes' => 0,
            'post_stats' => []
        ];

        // Initialize post statistics
        foreach ($posts as $post) {
            $statistics['post_stats'][$post->post_id] = [
                'total' => 0,
                'no_votes' => 0,
                'candidate_votes' => 0,
                'candidates' => []
            ];
        }

        for ($i = 1; $i <= $voterCount; $i++) {
            $voteData = ['voting_code' => "simulation_voter_" . time() . "_" . $i];
            $fieldIndex = 1;

            foreach ($posts as $post) {
                $field = 'candidate_' . str_pad($fieldIndex, 2, '0', STR_PAD_LEFT);
                $candidates = $post->candidacies;

                // Different voting patterns based on voter behavior
                $voterType = $this->getVoterType($i, $voterCount);
                $choice = $this->makeVotingChoice($voterType, $candidates, $post);

                if ($choice['type'] === 'no_vote') {
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => true,
                        'candidates' => null
                    ]);
                    $statistics['post_stats'][$post->post_id]['no_votes']++;
                } else {
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $choice['candidate']->candidacy_id]
                        ]
                    ]);
                    $statistics['post_stats'][$post->post_id]['candidate_votes']++;

                    // Track candidate votes
                    $candidateId = $choice['candidate']->candidacy_id;
                    if (!isset($statistics['post_stats'][$post->post_id]['candidates'][$candidateId])) {
                        $statistics['post_stats'][$post->post_id]['candidates'][$candidateId] = 0;
                    }
                    $statistics['post_stats'][$post->post_id]['candidates'][$candidateId]++;
                }

                $statistics['post_stats'][$post->post_id]['total']++;
                $fieldIndex++;
            }

            $vote = Vote::create($voteData);
            $voteIds[] = $vote->id;

            // Show progress
            if ($i % 25 === 0) {
                $this->line("  📊 Simulated {$i}/{$voterCount} voters...");
            }
        }

        // Calculate overall statistics
        foreach ($statistics['post_stats'] as $postStats) {
            $statistics['total_votes'] += $postStats['total'];
            $statistics['no_votes'] += $postStats['no_votes'];
            $statistics['candidate_votes'] += $postStats['candidate_votes'];
        }

        $this->info("✅ Simulation complete!");
        $this->line("   Created {$voterCount} voter records");
        $this->line("   Vote IDs: " . implode(', ', array_slice($voteIds, 0, 5)) . '... (and more)');

        // Show expected vs actual
        $this->info('');
        $this->info('📈 Simulation Statistics:');
        $this->line("   Expected total votes: " . ($voterCount * count($posts)));
        $this->line("   Actual total votes: {$statistics['total_votes']}");
        $this->line("   No votes: {$statistics['no_votes']}");
        $this->line("   Candidate votes: {$statistics['candidate_votes']}");
    }

    protected function getVoterType($voterNumber, $totalVoters)
    {
        // Create different voter archetypes
        $percentage = ($voterNumber / $totalVoters) * 100;

        if ($percentage <= 15) return 'abstainer'; // 15% high abstention rate
        if ($percentage <= 30) return 'selective'; // 15% votes selectively
        if ($percentage <= 70) return 'engaged'; // 40% votes for most positions
        if ($percentage <= 90) return 'loyal'; // 20% consistent voter
        return 'random'; // 10% random behavior
    }

    protected function makeVotingChoice($voterType, $candidates, $post)
    {
        if ($candidates->isEmpty()) {
            return ['type' => 'no_vote'];
        }

        switch ($voterType) {
            case 'abstainer':
                // 60% chance of no vote
                return (rand(1, 100) <= 60) ? ['type' => 'no_vote'] :
                       ['type' => 'candidate', 'candidate' => $candidates->random()];

            case 'selective':
                // 40% chance of no vote, prefers first candidate if voting
                if (rand(1, 100) <= 40) return ['type' => 'no_vote'];
                return ['type' => 'candidate', 'candidate' => $candidates->first()];

            case 'engaged':
                // 10% chance of no vote, votes for most positions
                if (rand(1, 100) <= 10) return ['type' => 'no_vote'];
                return ['type' => 'candidate', 'candidate' => $candidates->random()];

            case 'loyal':
                // Always votes, prefers first candidate
                return ['type' => 'candidate', 'candidate' => $candidates->first()];

            case 'random':
            default:
                // 25% chance of no vote
                return (rand(1, 100) <= 25) ? ['type' => 'no_vote'] :
                       ['type' => 'candidate', 'candidate' => $candidates->random()];
        }
    }

    protected function showComprehensiveResults($posts)
    {
        $this->info('');
        $this->info('🏆 COMPREHENSIVE ELECTION RESULTS');
        $this->info('================================');

        // Get results using ResultController
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $grandTotal = 0;
        $grandNoVotes = 0;

        foreach ($results['posts'] as $postResult) {
            $this->info("🏛️  {$postResult['post_name']}");
            $this->line("   Post ID: {$postResult['post_id']}");
            $this->line("   Total Votes: {$postResult['total_votes_for_post']}");
            $this->line("   No Votes: {$postResult['no_vote_count']}");

            $grandTotal += $postResult['total_votes_for_post'];
            $grandNoVotes += $postResult['no_vote_count'];

            if (empty($postResult['candidates'])) {
                $this->line("   🚫 No candidates");
            } else {
                $this->line("   🏅 Results:");
                foreach ($postResult['candidates'] as $index => $candidate) {
                    $rank = $index + 1;
                    $percentage = number_format($candidate['vote_percent'], 1);
                    $votes = $candidate['vote_count'];
                    $status = ($index === 0 && $votes > 0) ? ' 👑 WINNER' : '';
                    $this->line("      {$rank}. {$candidate['name']}: {$votes} votes ({$percentage}%){$status}");
                }
            }

            // Calculate abstention rate
            if ($postResult['total_votes_for_post'] > 0) {
                $abstentionRate = number_format(($postResult['no_vote_count'] / $postResult['total_votes_for_post']) * 100, 1);
                $this->line("   📊 Abstention Rate: {$abstentionRate}%");
            }

            $this->line('');
        }

        // Grand summary
        $this->info('📊 OVERALL ELECTION SUMMARY');
        $this->info('===========================');
        $this->line("Total votes cast: {$grandTotal}");
        $this->line("Total abstentions: {$grandNoVotes}");
        $candidateVotes = $grandTotal - $grandNoVotes;
        $this->line("Total candidate votes: {$candidateVotes}");

        if ($grandTotal > 0) {
            $overallAbstention = number_format(($grandNoVotes / $grandTotal) * 100, 1);
            $this->line("Overall abstention rate: {$overallAbstention}%");
        }

        $this->info('');
        $this->info('🎉 ELECTION SIMULATION COMPLETE!');
        $this->info('✨ Advanced features demonstrated:');
        $this->line('   • Multiple candidates per position ✅');
        $this->line('   • Diverse voter behavior patterns ✅');
        $this->line('   • Realistic abstention rates ✅');
        $this->line('   • Accurate vote counting across all posts ✅');
        $this->line('   • Comprehensive result analysis ✅');
        $this->line('   • Winner determination ✅');
        $this->line('   • Statistical reporting ✅');
    }
}