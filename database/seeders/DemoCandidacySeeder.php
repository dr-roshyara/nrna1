<?php

namespace Database\Seeders;

use App\Models\DemoCandidacy;
use App\Models\Election;
use Illuminate\Database\Seeder;

/**
 * DemoCandidacySeeder
 *
 * Populates demo_candidacies table with test candidates for demo election.
 * These candidates are used for testing the voting workflow without affecting
 * real election data.
 *
 * Uses DemoCandidacyFactory to generate realistic random candidate data.
 *
 * Usage:
 *   php artisan db:seed --class=DemoCandidacySeeder
 *   php artisan migrate:fresh --seed
 */
class DemoCandidacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get demo election by slug (more reliable than type)
        $demoElection = Election::withoutGlobalScopes()
            ->where('slug', 'demo-election')
            ->firstOrFail();

        $this->command->info('');
        $this->command->info('🎬 Seeding Demo Candidates...');
        $this->command->info('Election: ' . $demoElection->name . ' (ID: ' . $demoElection->id . ')');
        $this->command->info('');

        // Define posts and number of candidates for each
        $postsWithCounts = [
            'president' => 3,
            'vice_president' => 3,
            'secretary' => 3,
        ];

        $totalCreated = 0;

        // Generate candidates for each post using the factory (idempotent via factory)
        foreach ($postsWithCounts as $postId => $count) {
            // Check if candidates already exist for this post
            $existingCount = DemoCandidacy::where('election_id', $demoElection->id)
                ->where('post_id', $postId)
                ->count();

            if ($existingCount >= $count) {
                $this->command->info("✓ Post '{$postId}' already has {$existingCount} candidates, skipping...");
                continue;
            }

            $this->command->info("Creating {$count} candidates for {$postId}...");

            DemoCandidacy::factory()
                ->count($count)
                ->forPost($postId)
                ->forElection($demoElection)
                ->create();

            $totalCreated += $count;
        }

        // Display summary
        $this->command->info('');
        $this->command->info('✅ Demo candidates seeded successfully!');
        $this->command->info('   Total created: ' . $totalCreated . ' candidates');
        $this->command->info('   Storage: demo_candidacies table');
        $this->command->info('');
    }
}
