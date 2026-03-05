<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Post;
use App\Models\Organisation;
use Illuminate\Database\Seeder;

class DemoElectionSeeder extends Seeder
{
    /**
     * Seed demo election POSTS (not candidacies)
     *
     * This creates the post/position structure for the demo election.
     * Candidates are seeded separately via DemoCandidacySeeder.
     *
     * Run with: php artisan db:seed --class=DemoElectionSeeder
     */
    public function run()
    {
        // Get platform organisation (created by OrganisationSeeder)
        $platform = Organisation::getDefaultPlatform();

        // Get Demo Election (created by ElectionSeeder)
        $election = Election::withoutGlobalScopes()
            ->where('slug', 'demo-election')
            ->firstOrFail();

        $this->command->info('🚀 Creating demo election posts...');
        $this->command->info("   Election: {$election->name}");
        $this->command->info('');

        // ========== POST 1: PRESIDENT ==========
        $post1 = Post::firstOrCreate(
            ['post_id' => 'president-' . $election->id],
            [
                'election_id' => $election->id,
                'name' => 'President',
                'nepali_name' => 'राष्ट्रपति',
                'state_name' => 'National',
                'required_number' => 1,
                'position_order' => 1,
            ]
        );

        $this->command->info("  ├─ Created Post: {$post1->name} ({$post1->nepali_name})");

        // ========== POST 2: VICE PRESIDENT ==========
        $post2 = Post::firstOrCreate(
            ['post_id' => 'vice-president-' . $election->id],
            [
                'election_id' => $election->id,
                'name' => 'Vice President',
                'nepali_name' => 'उप-राष्ट्रपति',
                'state_name' => 'National',
                'required_number' => 1,
                'position_order' => 2,
            ]
        );

        $this->command->info("  ├─ Created Post: {$post2->name} ({$post2->nepali_name})");

        // ========== POST 3: SECRETARY ==========
        $post3 = Post::firstOrCreate(
            ['post_id' => 'secretary-' . $election->id],
            [
                'election_id' => $election->id,
                'name' => 'Secretary',
                'nepali_name' => 'सचिव',
                'state_name' => 'National',
                'required_number' => 1,
                'position_order' => 3,
            ]
        );

        $this->command->info("  └─ Created Post: {$post3->name} ({$post3->nepali_name})");

        // ========== SUMMARY ==========
        $this->command->info("\n✅ Demo Election Posts Created:");
        $this->command->info("  - President");
        $this->command->info("  - Vice President");
        $this->command->info("  - Secretary");
        $this->command->info("\n📌 Note: Candidates are seeded by DemoCandidacySeeder");
        $this->command->info("🚀 Access at: http://localhost:8000/election/demo/start");
    }
}
