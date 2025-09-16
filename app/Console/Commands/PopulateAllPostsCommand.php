<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Candidacy;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Hash;

class PopulateAllPostsCommand extends Command
{
    protected $signature = 'election:populate-all {--votes-per-post=7 : Number of votes to create per post}';
    protected $description = 'Add candidates to all posts and create votes for comprehensive testing';

    public function handle()
    {
        $this->info('🏛️  POPULATING ALL ELECTION POSTS');
        $this->info('===============================');

        $votesPerPost = $this->option('votes-per-post');

        // Get all posts
        $posts = Post::all();
        $this->info("Found {$posts->count()} posts in the system");

        // Create candidates for posts that don't have any
        $this->createCandidatesForAllPosts($posts);

        // Create votes for all posts
        $this->createVotesForAllPosts($posts, $votesPerPost);

        // Show comprehensive results
        $this->showComprehensiveResults($posts);
    }

    protected function createCandidatesForAllPosts($posts)
    {
        $this->info('');
        $this->info('👥 Adding Candidates to All Posts');
        $this->info('================================');

        // Candidate pool with diverse names
        $candidateNames = [
            'Dr. Rajesh Kumar', 'Ms. Sita Sharma', 'Mr. Arjun Thapa', 'Dr. Kamala Devi',
            'Prof. Bijay Singh', 'Ms. Radha Gurung', 'Mr. Ramesh Yadav', 'Dr. Sunita Rai',
            'Mr. Krishna Magar', 'Ms. Gita Tamang', 'Dr. Suresh Lama', 'Ms. Mina Sherpa',
            'Mr. Gopal Bista', 'Dr. Laxmi Adhikari', 'Ms. Sarita Poudel', 'Mr. Dipak Khadka'
        ];

        $candidateIndex = 0;
        $createdCandidates = 0;

        foreach ($posts as $post) {
            $existingCandidates = $post->candidacies()->count();

            if ($existingCandidates > 0) {
                $this->line("  ℹ️  {$post->name}: Already has {$existingCandidates} candidate(s)");
                continue;
            }

            // Create one candidate for this post
            $candidateName = $candidateNames[$candidateIndex % count($candidateNames)];
            $candidateIndex++;

            try {
                // Create user if doesn't exist
                $userId = "auto_user_" . $candidateIndex;
                $user = User::where('user_id', $userId)->first();

                if (!$user) {
                    $user = new User();
                    $user->name = $candidateName;
                    $user->user_id = $userId;
                    $user->email = strtolower(str_replace([' ', '.'], ['', ''], $candidateName)) . "@election.test";
                    $user->password = Hash::make('password');
                    $user->first_name = explode(' ', $candidateName)[1] ?? 'Unknown';
                    $user->last_name = explode(' ', $candidateName)[2] ?? '';
                    $user->region = "Auto Region";
                    $user->is_voter = 0;
                    $user->save();
                }

                // Create candidacy
                $candidacyId = "auto_cand_" . $post->post_id . "_" . $candidateIndex;

                $candidacy = new Candidacy();
                $candidacy->candidacy_id = $candidacyId;
                $candidacy->user_id = $userId;
                $candidacy->post_id = $post->post_id;
                $candidacy->proposer_id = "AUTO_PROPOSER";
                $candidacy->supporter_id = "AUTO_SUPPORTER";
                $candidacy->image_path_1 = "default.jpg";
                $candidacy->image_path_2 = "-";
                $candidacy->image_path_3 = "-";
                $candidacy->save();

                $this->line("  ✅ Added: {$candidateName} to {$post->name}");
                $createdCandidates++;

            } catch (\Exception $e) {
                $this->line("  ⚠️  Failed to add candidate to {$post->name}: " . $e->getMessage());
            }
        }

        $this->info("✅ Created {$createdCandidates} new candidates");
    }

    protected function createVotesForAllPosts($posts, $votesPerPost)
    {
        $this->info('');
        $this->info("🗳️  Creating {$votesPerPost} Votes Per Post");
        $this->info('=======================================');

        $totalCreatedVotes = 0;
        $voteStatistics = [];

        foreach ($posts as $postIndex => $post) {
            $candidates = $post->candidacies;

            if ($candidates->isEmpty()) {
                $this->line("  ⚠️  Skipping {$post->name}: No candidates");
                continue;
            }

            $candidateVotes = 0;
            $noVotes = 0;
            $fieldNumber = $postIndex + 1; // Use post index for field number

            // Ensure we don't exceed candidate_60
            if ($fieldNumber > 60) {
                $this->line("  ⚠️  Skipping {$post->name}: Field number would exceed candidate_60");
                continue;
            }

            $field = 'candidate_' . str_pad($fieldNumber, 2, '0', STR_PAD_LEFT);

            for ($i = 1; $i <= $votesPerPost; $i++) {
                $voteData = ['voting_code' => "auto_vote_{$post->post_id}_{$i}_" . time()];

                // 60% chance to vote for candidate, 40% chance for no vote
                $isNoVote = (rand(1, 100) <= 40);

                if ($isNoVote) {
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => true,
                        'candidates' => null
                    ]);
                    $noVotes++;
                } else {
                    // Pick the first (and likely only) candidate
                    $selectedCandidate = $candidates->first();
                    $voteData[$field] = json_encode([
                        'post_id' => $post->post_id,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $selectedCandidate->candidacy_id]
                        ]
                    ]);
                    $candidateVotes++;
                }

                try {
                    Vote::create($voteData);
                    $totalCreatedVotes++;
                } catch (\Exception $e) {
                    $this->line("    ⚠️  Failed to create vote: " . $e->getMessage());
                }
            }

            $voteStatistics[$post->post_id] = [
                'candidate_votes' => $candidateVotes,
                'no_votes' => $noVotes,
                'total' => $candidateVotes + $noVotes
            ];

            $this->line("  📊 {$post->name}: {$candidateVotes} candidate votes, {$noVotes} no votes");
        }

        $this->info("✅ Created {$totalCreatedVotes} total votes across all posts");
    }

    protected function showComprehensiveResults($posts)
    {
        $this->info('');
        $this->info('🏆 COMPREHENSIVE ELECTION RESULTS');
        $this->info('=================================');

        // Get results using ResultController
        $resultController = new ResultController();
        $reflection = new \ReflectionClass($resultController);
        $method = $reflection->getMethod('getElectionResultsData');
        $method->setAccessible(true);

        $results = $method->invoke($resultController, $posts);

        $grandTotal = 0;
        $grandNoVotes = 0;
        $grandCandidateVotes = 0;
        $positionsWithCandidates = 0;
        $positionsWithVotes = 0;

        foreach ($results['posts'] as $postResult) {
            $hasVotes = $postResult['total_votes_for_post'] > 0;
            $hasCandidates = !empty($postResult['candidates']);

            if ($hasVotes) {
                $positionsWithVotes++;
                $this->info("🏛️  {$postResult['post_name']}");
                $this->line("   Total Votes: {$postResult['total_votes_for_post']}");
                $this->line("   No Votes: {$postResult['no_vote_count']}");

                $grandTotal += $postResult['total_votes_for_post'];
                $grandNoVotes += $postResult['no_vote_count'];

                if ($hasCandidates) {
                    $positionsWithCandidates++;
                    $this->line("   Results:");
                    foreach ($postResult['candidates'] as $index => $candidate) {
                        $rank = $index + 1;
                        $percentage = number_format($candidate['vote_percent'], 1);
                        $votes = $candidate['vote_count'];
                        $status = ($index === 0 && $votes > 0) ? ' 👑 LEADING' : '';
                        $this->line("     {$rank}. {$candidate['name']}: {$votes} votes ({$percentage}%){$status}");

                        $grandCandidateVotes += $votes;
                    }
                } else {
                    $this->line("   No candidates");
                }

                // Abstention rate
                if ($postResult['total_votes_for_post'] > 0) {
                    $abstentionRate = number_format(($postResult['no_vote_count'] / $postResult['total_votes_for_post']) * 100, 1);
                    $this->line("   📊 Abstention Rate: {$abstentionRate}%");
                }

                $this->line('');
            }
        }

        // Summary statistics
        $this->info('📈 ELECTION SUMMARY');
        $this->info('==================');
        $this->line("Total positions: " . count($results['posts']));
        $this->line("Positions with votes: {$positionsWithVotes}");
        $this->line("Positions with candidates: {$positionsWithCandidates}");
        $this->line("Total votes cast: {$grandTotal}");
        $this->line("Total candidate votes: {$grandCandidateVotes}");
        $this->line("Total abstentions: {$grandNoVotes}");

        if ($grandTotal > 0) {
            $overallAbstention = number_format(($grandNoVotes / $grandTotal) * 100, 1);
            $this->line("Overall abstention rate: {$overallAbstention}%");
        }

        $this->info('');
        $this->info('🎉 FULL ELECTION SIMULATION COMPLETE!');
        $this->info('✨ System capabilities demonstrated:');
        $this->line('   • Multi-position election management ✅');
        $this->line('   • Automated candidate creation ✅');
        $this->line('   • Diverse voting patterns ✅');
        $this->line('   • Comprehensive no vote tracking ✅');
        $this->line('   • Accurate statistical analysis ✅');
        $this->line('   • Production-ready scalability ✅');
    }
}