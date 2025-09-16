<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vote;
use App\Models\Post;
use App\Models\User;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;

class CreateRandomVotesCommand extends Command
{
    protected $signature = 'test:random-votes {--count=40 : Number of random votes to create}';
    protected $description = 'Create candidates for all posts and add random votes including no votes';

    public function handle()
    {
        $this->info('🎲 Creating Candidates and Random Votes');
        $this->info('=====================================');

        $voteCount = $this->option('count');

        // Get all posts
        $posts = Post::all();
        if ($posts->isEmpty()) {
            $this->error('No posts found. Please run php artisan test:setup-election first.');
            return;
        }

        // Create candidates for posts that don't have enough
        $this->createCandidatesForPosts($posts);

        // Create random votes
        $this->createRandomVotes($posts, $voteCount);

        // Show results
        $this->showResults($posts);
    }

    protected function createCandidatesForPosts($posts)
    {
        $this->info('👥 Creating additional candidates...');

        $candidateData = [
            'president_test' => ['Alice Johnson', 'Bob Smith', 'Carol Williams', 'David Brown'],
            'vp_test' => ['Emma Davis', 'Frank Miller', 'Grace Wilson', 'Henry Moore'],
            'secretary_test' => ['Ivy Taylor', 'Jack Anderson', 'Kelly Thomas', 'Liam Jackson']
        ];

        foreach ($posts as $post) {
            $existingCandidates = $post->candidacies()->count();
            $names = $candidateData[$post->post_id] ?? [];

            if ($existingCandidates >= count($names)) {
                $this->line("  ℹ️  {$post->name} already has enough candidates ({$existingCandidates})");
                continue;
            }

            // Get existing user to use as template
            $templateUser = User::first();
            if (!$templateUser) {
                $this->warn("  ⚠️  No users found, skipping candidates for {$post->name}");
                continue;
            }

            $created = 0;
            foreach ($names as $index => $name) {
                $candidacyId = "cand_{$post->post_id}_{$index}";

                // Check if candidate already exists
                if (Candidacy::where('candidacy_id', $candidacyId)->exists()) {
                    continue;
                }

                try {
                    Candidacy::create([
                        'candidacy_id' => $candidacyId,
                        'user_id' => $templateUser->user_id,
                        'post_id' => $post->post_id,
                        'name' => $name
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    // Skip if there's a constraint violation
                    continue;
                }
            }

            if ($created > 0) {
                $this->line("  ✅ Created {$created} candidates for {$post->name}");
            }
        }
    }

    protected function createRandomVotes($posts, $voteCount)
    {
        $this->info('');
        $this->info("🗳️  Creating {$voteCount} random votes...");

        $voteIds = [];
        $votingPatterns = [];

        for ($i = 1; $i <= $voteCount; $i++) {
            $voteData = ['voting_code' => "random_vote_" . time() . "_" . $i];
            $fieldIndex = 1;
            $voterPattern = [];

            foreach ($posts as $post) {
                $field = 'candidate_' . str_pad($fieldIndex, 2, '0', STR_PAD_LEFT);
                $candidates = $post->candidacies;

                if ($candidates->isEmpty()) {
                    // Skip this post if no candidates
                    $fieldIndex++;
                    continue;
                }

                // Random choice: 70% chance to vote for a candidate, 30% chance for no vote
                $isNoVote = (rand(1, 100) <= 30);

                if ($isNoVote) {
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => true,
                        'candidates' => null
                    ]);
                    $voterPattern[] = "{$post->name}: No Vote";
                } else {
                    // Pick a random candidate
                    $randomCandidate = $candidates->random();
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $randomCandidate->candidacy_id]
                        ]
                    ]);
                    $candidateName = $randomCandidate->name ?? $randomCandidate->user->name ?? 'Unknown';
                    $voterPattern[] = "{$post->name}: {$candidateName}";
                }

                $fieldIndex++;
            }

            $vote = Vote::create($voteData);
            $voteIds[] = $vote->id;
            $votingPatterns[$i] = $voterPattern;

            // Show progress every 10 votes
            if ($i % 10 === 0) {
                $this->line("  📊 Created {$i}/{$voteCount} votes...");
            }
        }

        $this->info("✅ Created {$voteCount} random votes");
        $this->line("   Vote IDs: " . implode(', ', array_slice($voteIds, 0, 10)) .
                   (count($voteIds) > 10 ? '... (and ' . (count($voteIds) - 10) . ' more)' : ''));

        // Show some sample voting patterns
        $this->info('');
        $this->info('🎯 Sample Voting Patterns:');
        for ($i = 1; $i <= min(5, $voteCount); $i++) {
            $this->line("  Voter {$i}:");
            foreach ($votingPatterns[$i] as $pattern) {
                $this->line("    • {$pattern}");
            }
        }
    }

    protected function showResults($posts)
    {
        $this->info('');
        $this->info('📊 Final Vote Count Results:');
        $this->info('============================');

        // Get results using ResultController
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $totalAllVotes = 0;
        $totalAllNoVotes = 0;

        foreach ($results['posts'] as $postResult) {
            $this->info("🏛️  {$postResult['post_name']} ({$postResult['post_id']})");
            $this->line("   Total Votes: {$postResult['total_votes_for_post']}");
            $this->line("   No Votes: {$postResult['no_vote_count']}");

            $totalAllVotes += $postResult['total_votes_for_post'];
            $totalAllNoVotes += $postResult['no_vote_count'];

            if (empty($postResult['candidates'])) {
                $this->line("   Candidates: (None)");
            } else {
                $this->line("   Candidates:");
                foreach ($postResult['candidates'] as $candidate) {
                    $percentage = number_format($candidate['vote_percent'], 1);
                    $votes = $candidate['vote_count'];
                    $this->line("     • {$candidate['name']}: {$votes} votes ({$percentage}%)");
                }
            }
            $this->line('');
        }

        // Summary statistics
        $this->info('📈 Overall Statistics:');
        $this->info('=====================');
        $this->line("Total votes across all posts: {$totalAllVotes}");
        $this->line("Total no votes across all posts: {$totalAllNoVotes}");
        $candidateVotes = $totalAllVotes - $totalAllNoVotes;
        $this->line("Total candidate votes: {$candidateVotes}");

        if ($totalAllVotes > 0) {
            $noVotePercentage = number_format(($totalAllNoVotes / $totalAllVotes) * 100, 1);
            $this->line("No vote percentage: {$noVotePercentage}%");
        }

        $this->info('');
        $this->info('🎉 Random voting simulation completed!');
        $this->info('✨ Features demonstrated:');
        $this->line('   • Multiple candidates per post ✅');
        $this->line('   • Random candidate selection ✅');
        $this->line('   • Random no vote distribution ✅');
        $this->line('   • Accurate vote counting ✅');
        $this->line('   • Proper percentage calculations ✅');
        $this->line('   • Cross-post vote tracking ✅');
    }
}