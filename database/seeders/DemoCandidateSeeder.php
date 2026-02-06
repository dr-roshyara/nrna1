<?php

namespace Database\Seeders;

use App\Models\DemoCandidate;
use App\Models\Election;
use Illuminate\Database\Seeder;

/**
 * DemoCandidateSeeder
 *
 * Populates demo_candidacies table with test candidates for demo election.
 * These candidates are used for testing the voting workflow without affecting
 * real election data.
 *
 * Uses DemoCandidateFactory to generate realistic random candidate data.
 *
 * Usage:
 *   php artisan db:seed --class=DemoCandidateSeeder
 *   php artisan migrate:fresh --seed
 */
class DemoCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demoElection = Election::where('type', 'demo')->first();

        if (!$demoElection) {
            $this->command->error('❌ Demo election not found. Run ElectionSeeder first.');
            return;
        }

        $this->command->info('');
        $this->command->info('🎬 Seeding Demo Candidates...');
        $this->command->info('Election: ' . $demoElection->name . ' (ID: ' . $demoElection->id . ')');
        $this->command->info('');

        // Clear existing demo candidates (optional - remove if you want to preserve)
        // DemoCandidate::where('election_id', $demoElection->id)->delete();

        // Define posts and number of candidates for each
        $postsWithCounts = [
            'president' => 3,
            'vice_president' => 3,
            'secretary' => 3,
            'treasurer' => 3,
            'member_at_large' => 5,
        ];

        $totalCreated = 0;

        // Generate candidates for each post using the factory
        foreach ($postsWithCounts as $postId => $count) {
            $this->command->info("Creating {$count} candidates for {$postId}...");

            DemoCandidate::factory()
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
        $this->command->info('Demo candidates by post:');
        foreach ($postsWithCounts as $postId => $count) {
            $this->command->info("   - " . ucwords(str_replace('_', ' ', $postId)) . ": {$count} candidates");
        }
        $this->command->info('');
    }
}
