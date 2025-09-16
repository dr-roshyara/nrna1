<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Candidacy;
use Illuminate\Support\Facades\Hash;

class AddHimaniCandidateCommand extends Command
{
    protected $signature = 'candidate:add-himani';
    protected $description = 'Add Himani Shah as a candidate to the election system';

    public function handle()
    {
        $this->info('👤 Adding Himani Shah as Candidate');
        $this->info('================================');

        try {
            // Create the user first
            $this->info('Creating user...');

            $user = new User();
            $user->name = "Himani Shah";
            $user->user_id = "nrna_12";
            $user->email = "himani@example.com";
            $user->password = Hash::make('password'); // Always hash passwords
            $user->first_name = "Himani";
            $user->last_name = "Shah";
            $user->nrna_id = "TEST_2025_12";
            $user->is_voter = 0;

            // Add other fields that might be required
            $user->region = "Test Region";

            $user->save();
            $this->line("✅ User created: {$user->name} (ID: {$user->user_id})");

        } catch (\Exception $e) {
            // User might already exist, try to find existing user
            $user = User::where('user_id', 'nrna_12')->first();
            if ($user) {
                $this->line("ℹ️  User already exists: {$user->name} (ID: {$user->user_id})");
            } else {
                $this->error("Failed to create user: " . $e->getMessage());
                return;
            }
        }

        try {
            // Create the candidacy
            $this->info('Creating candidacy...');

            $candi = new Candidacy();
            $candi->user_id = "nrna_12";
            $candi->candidacy_id = "TEST_2025_12";
            $candi->post_id = "2025_04";
            $candi->proposer_id = "DE10218"; // Link to the proposer's nrna_id
            $candi->supporter_id = "DE1000018"; // Link to the supporter's nrna_id
            $candi->image_path_1 = "himani.jpg";
            $candi->image_path_2 = "-";
            $candi->image_path_3 = "-";

            $candi->save();
            $this->line("✅ Candidacy created: {$candi->candidacy_id} for post {$candi->post_id}");

        } catch (\Exception $e) {
            $this->error("Failed to create candidacy: " . $e->getMessage());

            // Try to get more details about the error
            if (strpos($e->getMessage(), 'post_id') !== false) {
                $this->warn("Note: Post ID '2025_04' might not exist in the posts table.");
                $this->line("Available posts:");

                $posts = \App\Models\Post::all(['post_id', 'name']);
                foreach ($posts as $post) {
                    $this->line("  📋 {$post->post_id}: {$post->name}");
                }

                // Ask if user wants to use an existing post
                if ($this->confirm('Would you like to add the candidate to an existing post instead?')) {
                    $this->addToExistingPost($candi);
                }
            }
            return;
        }

        // Verify the candidate was added successfully
        $this->info('');
        $this->info('Verification:');
        $this->verifyCandidate();

        $this->info('');
        $this->info('🎉 Himani Shah successfully added as candidate!');
        $this->info('📊 You can now test vote counting with this new candidate.');
    }

    protected function addToExistingPost($candidacy)
    {
        $posts = \App\Models\Post::all(['post_id', 'name']);

        if ($posts->isEmpty()) {
            $this->error('No posts available in the system.');
            return;
        }

        $this->info('Please choose a post:');
        foreach ($posts as $index => $post) {
            $this->line("  {$index}: {$post->post_id} - {$post->name}");
        }

        $choice = $this->ask('Enter the number of the post you want to use');

        if (isset($posts[$choice])) {
            $selectedPost = $posts[$choice];

            try {
                $candidacy->post_id = $selectedPost->post_id;
                $candidacy->save();

                $this->line("✅ Candidacy created for post: {$selectedPost->name} ({$selectedPost->post_id})");

            } catch (\Exception $e) {
                $this->error("Still failed to create candidacy: " . $e->getMessage());
            }
        } else {
            $this->error('Invalid choice.');
        }
    }

    protected function verifyCandidate()
    {
        $user = User::where('user_id', 'nrna_12')->first();
        $candidacy = Candidacy::where('candidacy_id', 'TEST_2025_12')->first();

        if ($user) {
            $this->line("✅ User verified: {$user->name} ({$user->user_id})");
        } else {
            $this->line("❌ User not found");
        }

        if ($candidacy) {
            $this->line("✅ Candidacy verified: {$candidacy->candidacy_id} for post {$candidacy->post_id}");

            // Check if post exists
            $post = \App\Models\Post::where('post_id', $candidacy->post_id)->first();
            if ($post) {
                $this->line("✅ Post verified: {$post->name}");
            } else {
                $this->line("⚠️  Post {$candidacy->post_id} not found");
            }
        } else {
            $this->line("❌ Candidacy not found");
        }
    }
}