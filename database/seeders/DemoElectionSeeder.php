<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidate;
use Illuminate\Database\Seeder;

class DemoElectionSeeder extends Seeder
{
    /**
     * Seed the application's database with demo election data
     * Run with: php artisan db:seed --class=DemoElectionSeeder
     */
    public function run()
    {
        // Clean state: Delete ALL demo elections to ensure only one exists
        // This prevents conflicts when multiple demo elections are created
        Election::where('type', 'demo')->delete();

        // Create Demo Election with standard slug
        // Using 'demo-election' ensures route('/election/demo/start') finds the correct election
        $election = Election::create([
            'name' => 'Demo Election',
            'slug' => 'demo-election',
            'type' => 'demo',
            'is_active' => true,
            'description' => 'Public demo election for testing the voting system without registration',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(365)->format('Y-m-d'),
        ]);

        $this->command->info("✅ Created Demo Election: {$election->name}");

        // ========== POST 1: PRESIDENT ==========
        $post1 = Post::create([
            'post_id' => 'president-' . $election->id,
            'name' => 'President',
            'nepali_name' => 'राष्ट्रपति',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        // Candidates for President
        $presidents = [
            [
                'user_name' => 'Alice Johnson',
                'candidacy_name' => 'Alice Johnson - Progressive Platform',
                'proposer_name' => 'John Doe',
                'supporter_name' => 'Jane Smith',
            ],
            [
                'user_name' => 'Bob Smith',
                'candidacy_name' => 'Bob Smith - Economic Growth',
                'proposer_name' => 'Michael Brown',
                'supporter_name' => 'Sarah Wilson',
            ],
            [
                'user_name' => 'Carol Williams',
                'candidacy_name' => 'Carol Williams - Community First',
                'proposer_name' => 'David Lee',
                'supporter_name' => 'Emma Davis',
            ],
        ];

        foreach ($presidents as $index => $candidate) {
            DemoCandidate::create([
                'user_id' => 'demo-president-' . $election->id . '-' . ($index + 1),
                'post_id' => $post1->post_id,
                'election_id' => $election->id,
                'candidacy_id' => 'demo-president-' . $election->id . '-' . ($index + 1),
                'user_name' => $candidate['user_name'],
                'candidacy_name' => $candidate['candidacy_name'],
                'proposer_name' => $candidate['proposer_name'],
                'supporter_name' => $candidate['supporter_name'],
                'position_order' => $index + 1,
            ]);
        }

        $this->command->info("  ├─ Created Post: {$post1->name} ({$post1->nepali_name})");
        $this->command->info("  │  └─ Added " . count($presidents) . " candidates");

        // ========== POST 2: VICE PRESIDENT ==========
        $post2 = Post::create([
            'post_id' => 'vice-president-' . $election->id,
            'name' => 'Vice President',
            'nepali_name' => 'उप-राष्ट्रपति',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 2,
        ]);

        // Candidates for Vice President
        $vicePresidents = [
            [
                'user_name' => 'Daniel Miller',
                'candidacy_name' => 'Daniel Miller - Innovation Leader',
                'proposer_name' => 'Robert Johnson',
                'supporter_name' => 'Patricia Garcia',
            ],
            [
                'user_name' => 'Eva Martinez',
                'candidacy_name' => 'Eva Martinez - Social Justice',
                'proposer_name' => 'Kevin Brown',
                'supporter_name' => 'Lisa Anderson',
            ],
            [
                'user_name' => 'Frank Wilson',
                'candidacy_name' => 'Frank Wilson - Infrastructure Expert',
                'proposer_name' => 'Paul Taylor',
                'supporter_name' => 'Mary Thomas',
            ],
        ];

        foreach ($vicePresidents as $index => $candidate) {
            DemoCandidate::create([
                'user_id' => 'demo-vice-president-' . $election->id . '-' . ($index + 1),
                'post_id' => $post2->post_id,
                'election_id' => $election->id,
                'candidacy_id' => 'demo-vice-president-' . $election->id . '-' . ($index + 1),
                'user_name' => $candidate['user_name'],
                'candidacy_name' => $candidate['candidacy_name'],
                'proposer_name' => $candidate['proposer_name'],
                'supporter_name' => $candidate['supporter_name'],
                'position_order' => $index + 1,
            ]);
        }

        $this->command->info("  ├─ Created Post: {$post2->name} ({$post2->nepali_name})");
        $this->command->info("  │  └─ Added " . count($vicePresidents) . " candidates");

        // ========== POST 3: SECRETARY ==========
        $post3 = Post::create([
            'post_id' => 'secretary-' . $election->id,
            'name' => 'Secretary',
            'nepali_name' => 'सचिव',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 3,
        ]);

        // Candidates for Secretary
        $secretaries = [
            [
                'user_name' => 'Grace Lee',
                'candidacy_name' => 'Grace Lee - Administration Expert',
                'proposer_name' => 'James Harris',
                'supporter_name' => 'Nancy Clark',
            ],
            [
                'user_name' => 'Henry White',
                'candidacy_name' => 'Henry White - Organization Specialist',
                'proposer_name' => 'Christopher Lewis',
                'supporter_name' => 'Jennifer Martin',
            ],
            [
                'user_name' => 'Iris Walker',
                'candidacy_name' => 'Iris Walker - Communications Lead',
                'proposer_name' => 'Daniel Hall',
                'supporter_name' => 'Michelle Moore',
            ],
        ];

        foreach ($secretaries as $index => $candidate) {
            DemoCandidate::create([
                'user_id' => 'demo-secretary-' . $election->id . '-' . ($index + 1),
                'post_id' => $post3->post_id,
                'election_id' => $election->id,
                'candidacy_id' => 'demo-secretary-' . $election->id . '-' . ($index + 1),
                'user_name' => $candidate['user_name'],
                'candidacy_name' => $candidate['candidacy_name'],
                'proposer_name' => $candidate['proposer_name'],
                'supporter_name' => $candidate['supporter_name'],
                'position_order' => $index + 1,
            ]);
        }

        $this->command->info("  ├─ Created Post: {$post3->name} ({$post3->nepali_name})");
        $this->command->info("  │  └─ Added " . count($secretaries) . " candidates");

        // ========== SUMMARY ==========
        $totalPosts = 3;
        $totalCandidacies = DemoCandidate::where('election_id', $election->id)->count();

        $this->command->info("\n📊 Demo Election Summary:");
        $this->command->info("  ✅ Election: {$election->name}");
        $this->command->info("  ✅ Posts: {$totalPosts}");
        $this->command->info("  ✅ Total Candidates: {$totalCandidacies}");
        $this->command->info("\n🚀 Access at: http://localhost:8000/election/demo/start");
    }
}
