<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\User;

class DemoDemoElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get platform organisation
        $platformOrg = \App\Models\Organisation::where('type', 'platform')->first();
        if (!$platformOrg) {
            echo "❌ Platform organisation not found!\n";
            return;
        }

        // Create or get the demo election
        $election = Election::firstOrCreate(
            ['type' => 'demo', 'slug' => 'demo-election'],
            [
                'name' => 'Demo Election',
                'description' => 'Public demo election for testing',
                'is_active' => true,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'organisation_id' => $platformOrg->id,
            ]
        );

        echo "✓ Demo election created/found: {$election->name} (ID: {$election->id})\n";

        // Create national posts
        $presidentPost = DemoPost::firstOrCreate(
            [
                'election_id' => $election->id,
                'name' => 'President',
                'is_national_wide' => 1,
                'organisation_id' => $platformOrg->id,
            ],
            [
                'nepali_name' => 'राष्ट्रपति',
                'position_order' => 1,
                'required_number' => 1,
                'state_name' => null,
            ]
        );

        echo "✓ Post created: {$presidentPost->name} (ID: {$presidentPost->id})\n";

        $vicePresidentPost = DemoPost::firstOrCreate(
            [
                'election_id' => $election->id,
                'name' => 'Vice President',
                'is_national_wide' => 1,
                'organisation_id' => $platformOrg->id,
            ],
            [
                'nepali_name' => 'उपराष्ट्रपति',
                'position_order' => 2,
                'required_number' => 1,
                'state_name' => null,
            ]
        );

        echo "✓ Post created: {$vicePresidentPost->name} (ID: {$vicePresidentPost->id})\n";

        // Create regional posts
        $regionalPost = DemoPost::firstOrCreate(
            [
                'election_id' => $election->id,
                'name' => 'Regional Representative',
                'is_national_wide' => 0,
                'state_name' => 'Europe',
                'organisation_id' => $platformOrg->id,
            ],
            [
                'nepali_name' => 'क्षेत्रीय प्रतिनिधि',
                'position_order' => 3,
                'required_number' => 1,
            ]
        );

        echo "✓ Post created: {$regionalPost->name} (ID: {$regionalPost->id})\n";

        // Create candidates for President post
        $candidate1 = DemoCandidacy::firstOrCreate(
            [
                'post_id' => $presidentPost->id,
                'name' => 'Candidate 1',
                'organisation_id' => $platformOrg->id,
            ],
            [
                'user_id' => User::first()->id ?? null,
                'description' => 'First candidate for president',
                'position_order' => 1,
            ]
        );

        echo "✓ Candidate created: {$candidate1->name} for {$presidentPost->name}\n";

        $candidate2 = DemoCandidacy::firstOrCreate(
            [
                'post_id' => $presidentPost->id,
                'name' => 'Candidate 2',
                'organisation_id' => $platformOrg->id,
            ],
            [
                'user_id' => User::skip(1)->first()->id ?? null,
                'description' => 'Second candidate for president',
                'position_order' => 2,
            ]
        );

        echo "✓ Candidate created: {$candidate2->name} for {$presidentPost->name}\n";

        // Create candidate for Vice President post
        $candidate3 = DemoCandidacy::firstOrCreate(
            [
                'post_id' => $vicePresidentPost->id,
                'name' => 'Candidate 3',
                'organisation_id' => $platformOrg->id,
            ],
            [
                'user_id' => User::skip(2)->first()->id ?? null,
                'description' => 'Candidate for vice president',
                'position_order' => 1,
            ]
        );

        echo "✓ Candidate created: {$candidate3->name} for {$vicePresidentPost->name}\n";

        // Create candidate for Regional Representative post
        $candidate4 = DemoCandidacy::firstOrCreate(
            [
                'post_id' => $regionalPost->id,
                'name' => 'Regional Candidate 1',
                'organisation_id' => $platformOrg->id,
            ],
            [
                'user_id' => User::skip(3)->first()->id ?? null,
                'description' => 'Candidate for regional representative',
                'position_order' => 1,
            ]
        );

        echo "✓ Candidate created: {$candidate4->name} for {$regionalPost->name}\n";

        // Verify
        $totalCandidates = DemoCandidacy::where('organisation_id', null)->count();
        $totalPosts = DemoPost::where('organisation_id', null)->count();

        echo "\n✅ DEMO SEEDING COMPLETE:\n";
        echo "   - {$totalPosts} posts created\n";
        echo "   - {$totalCandidates} candidates created\n";
    }
}
