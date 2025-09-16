<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;

class SetupTestElectionCommand extends Command
{
    protected $signature = 'test:setup-election';
    protected $description = 'Setup test election data with posts and candidates';

    public function handle()
    {
        $this->info('🏗️  Setting up Test Election Data');
        $this->info('================================');

        // Create test posts
        $posts = [
            [
                'post_id' => 'president_test',
                'name' => 'President (Test)',
                'nepali_name' => 'अध्यक्ष (परीक्षण)',
                'state_name' => 'National',
                'required_number' => 1
            ],
            [
                'post_id' => 'vp_test',
                'name' => 'Vice President (Test)',
                'nepali_name' => 'उपाध्यक्ष (परीक्षण)',
                'state_name' => 'National',
                'required_number' => 1
            ],
            [
                'post_id' => 'secretary_test',
                'name' => 'Secretary (Test)',
                'nepali_name' => 'सचिव (परीक्षण)',
                'state_name' => 'Regional',
                'required_number' => 1
            ]
        ];

        $createdPosts = [];
        foreach ($posts as $postData) {
            $existingPost = Post::where('post_id', $postData['post_id'])->first();
            if (!$existingPost) {
                $post = Post::create($postData);
                $createdPosts[] = $post;
                $this->line("✅ Created post: {$post->name}");
            } else {
                $createdPosts[] = $existingPost;
                $this->line("ℹ️  Post already exists: {$existingPost->name}");
            }
        }

        // Create test candidates for each post
        $candidateNames = [
            'president_test' => ['Alice Johnson', 'Bob Smith', 'Carol Williams', 'David Brown'],
            'vp_test' => ['Emma Davis', 'Frank Miller', 'Grace Wilson', 'Henry Moore'],
            'secretary_test' => ['Ivy Taylor', 'Jack Anderson', 'Kelly Thomas', 'Liam Jackson']
        ];

        $createdCandidates = [];
        foreach ($createdPosts as $post) {
            $names = $candidateNames[$post->post_id] ?? [];

            foreach ($names as $index => $name) {
                $userId = "test_user_{$post->post_id}_{$index}";
                $candidacyId = "test_cand_{$post->post_id}_{$index}";

                // Use existing user or create a placeholder
                $user = User::where('user_id', $userId)->first();
                if (!$user) {
                    // Try to find any existing user to use as a placeholder
                    $user = User::first();
                    if (!$user) {
                        $this->warn("No users found in database. Skipping candidate creation for {$name}");
                        continue;
                    }
                    $userId = $user->user_id; // Use existing user's ID
                }

                // Create candidacy if doesn't exist
                $candidacy = Candidacy::where('candidacy_id', $candidacyId)->first();
                if (!$candidacy) {
                    $candidacy = Candidacy::create([
                        'candidacy_id' => $candidacyId,
                        'user_id' => $userId,
                        'post_id' => $post->post_id,
                        'name' => $name
                    ]);
                    $createdCandidates[] = $candidacy;
                    $this->line("  ✅ Created candidate: {$name} for {$post->name}");
                } else {
                    $createdCandidates[] = $candidacy;
                    $this->line("  ℹ️  Candidate already exists: {$name} for {$post->name}");
                }
            }
        }

        $this->info('');
        $this->info('📊 Test Election Setup Summary:');
        $this->info('==============================');
        $this->line("Posts created/found: " . count($createdPosts));
        $this->line("Candidates created/found: " . count($createdCandidates));

        $this->info('');
        $this->info('🎯 Ready to test vote counting! Run:');
        $this->line('   php artisan test:vote-counting');

        return 0;
    }
}