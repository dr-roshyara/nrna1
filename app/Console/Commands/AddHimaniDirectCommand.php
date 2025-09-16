<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Candidacy;
use Illuminate\Support\Facades\Hash;

class AddHimaniDirectCommand extends Command
{
    protected $signature = 'candidate:add-himani-direct';
    protected $description = 'Add Himani Shah directly to Vice President position';

    public function handle()
    {
        $this->info('👤 Adding Himani Shah to Vice President Position');
        $this->info('============================================');

        // User should already exist, but verify
        $user = User::where('user_id', 'nrna_12')->first();
        if ($user) {
            $this->line("✅ User found: {$user->name} ({$user->user_id})");
        } else {
            $this->error("User not found. Please run 'php artisan candidate:add-himani' first.");
            return;
        }

        try {
            // Create candidacy for Vice President position
            $candidacy = new Candidacy();
            $candidacy->user_id = "nrna_12";
            $candidacy->candidacy_id = "himani_vp_2025";
            $candidacy->post_id = "vp_test"; // Use existing VP test post
            $candidacy->proposer_id = "DE10218";
            $candidacy->supporter_id = "DE1000018";
            $candidacy->image_path_1 = "himani.jpg";
            $candidacy->image_path_2 = "-";
            $candidacy->image_path_3 = "-";

            $candidacy->save();

            $this->line("✅ Candidacy created: Himani Shah for Vice President");
            $this->line("   Candidacy ID: {$candidacy->candidacy_id}");
            $this->line("   Post ID: {$candidacy->post_id}");

        } catch (\Exception $e) {
            $this->error("Failed to create candidacy: " . $e->getMessage());
            return;
        }

        // Verification
        $this->info('');
        $this->info('📊 Verification:');

        $vpPost = \App\Models\Post::where('post_id', 'vp_test')->with('candidacies')->first();
        if ($vpPost) {
            $candidateCount = $vpPost->candidacies()->count();
            $this->line("✅ Vice President position now has {$candidateCount} candidate(s)");

            foreach ($vpPost->candidacies as $candidate) {
                $user = $candidate->user;
                $userName = $user ? $user->name : 'Unknown';
                $this->line("   • {$userName} ({$candidate->candidacy_id})");
            }
        }

        $this->info('');
        $this->info('🎉 Himani Shah successfully added as VP candidate!');
        $this->info('📊 Now you can test voting with multiple candidates across different positions.');
    }
}