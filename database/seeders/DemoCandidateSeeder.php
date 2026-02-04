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
 * Usage: php artisan db:seed --class=DemoCandidateSeeder
 * Or:    php artisan migrate:fresh --seed
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

        // Demo candidate data - diverse names and posts for testing
        $candidates = [
            // President candidates
            [
                'candidacy_id' => 'DEMO_PRES_001',
                'user_id' => 'demo_user_001',
                'user_name' => 'Sarah Johnson',
                'candidacy_name' => 'Sarah Johnson',
                'post_id' => 'president',
                'post_name' => 'President',
                'proposer_id' => 'demo_prop_001',
                'proposer_name' => 'Michael Brown',
                'supporter_id' => 'demo_supp_001',
                'supporter_name' => 'Emily Davis',
                'election_id' => $demoElection->id,
            ],
            [
                'candidacy_id' => 'DEMO_PRES_002',
                'user_id' => 'demo_user_002',
                'user_name' => 'David Martinez',
                'candidacy_name' => 'David Martinez',
                'post_id' => 'president',
                'post_name' => 'President',
                'proposer_id' => 'demo_prop_002',
                'proposer_name' => 'Jennifer Wilson',
                'supporter_id' => 'demo_supp_002',
                'supporter_name' => 'Robert Anderson',
                'election_id' => $demoElection->id,
            ],

            // Vice President candidates
            [
                'candidacy_id' => 'DEMO_VP_001',
                'user_id' => 'demo_user_003',
                'user_name' => 'Maria Garcia',
                'candidacy_name' => 'Maria Garcia',
                'post_id' => 'vice_president',
                'post_name' => 'Vice President',
                'proposer_id' => 'demo_prop_003',
                'proposer_name' => 'James Taylor',
                'supporter_id' => 'demo_supp_003',
                'supporter_name' => 'Lisa Moore',
                'election_id' => $demoElection->id,
            ],
            [
                'candidacy_id' => 'DEMO_VP_002',
                'user_id' => 'demo_user_004',
                'user_name' => 'Ahmed Hassan',
                'candidacy_name' => 'Ahmed Hassan',
                'post_id' => 'vice_president',
                'post_name' => 'Vice President',
                'proposer_id' => 'demo_prop_004',
                'proposer_name' => 'Patricia Lee',
                'supporter_id' => 'demo_supp_004',
                'supporter_name' => 'Christopher White',
                'election_id' => $demoElection->id,
            ],

            // Secretary candidates
            [
                'candidacy_id' => 'DEMO_SEC_001',
                'user_id' => 'demo_user_005',
                'user_name' => 'Yuki Tanaka',
                'candidacy_name' => 'Yuki Tanaka',
                'post_id' => 'secretary',
                'post_name' => 'Secretary',
                'proposer_id' => 'demo_prop_005',
                'proposer_name' => 'Anna Schmidt',
                'supporter_id' => 'demo_supp_005',
                'supporter_name' => 'Marcus Johnson',
                'election_id' => $demoElection->id,
            ],
            [
                'candidacy_id' => 'DEMO_SEC_002',
                'user_id' => 'demo_user_006',
                'user_name' => 'Isabella Rodriguez',
                'candidacy_name' => 'Isabella Rodriguez',
                'post_id' => 'secretary',
                'post_name' => 'Secretary',
                'proposer_id' => 'demo_prop_006',
                'proposer_name' => 'Klaus Mueller',
                'supporter_id' => 'demo_supp_006',
                'supporter_name' => 'Sophie Martin',
                'election_id' => $demoElection->id,
            ],

            // Treasurer candidates
            [
                'candidacy_id' => 'DEMO_TREAS_001',
                'user_id' => 'demo_user_007',
                'user_name' => 'Chen Wei',
                'candidacy_name' => 'Chen Wei',
                'post_id' => 'treasurer',
                'post_name' => 'Treasurer',
                'proposer_id' => 'demo_prop_007',
                'proposer_name' => 'Elena Petrov',
                'supporter_id' => 'demo_supp_007',
                'supporter_name' => 'Lars Eriksson',
                'election_id' => $demoElection->id,
            ],
            [
                'candidacy_id' => 'DEMO_TREAS_002',
                'user_id' => 'demo_user_008',
                'user_name' => 'Priya Patel',
                'candidacy_name' => 'Priya Patel',
                'post_id' => 'treasurer',
                'post_name' => 'Treasurer',
                'proposer_id' => 'demo_prop_008',
                'proposer_name' => 'Dimitrios Vasilis',
                'supporter_id' => 'demo_supp_008',
                'supporter_name' => 'Fatima Al-Rashid',
                'election_id' => $demoElection->id,
            ],

            // Member at Large candidates
            [
                'candidacy_id' => 'DEMO_MAL_001',
                'user_id' => 'demo_user_009',
                'user_name' => 'Antonio Silva',
                'candidacy_name' => 'Antonio Silva',
                'post_id' => 'member_at_large',
                'post_name' => 'Member at Large',
                'proposer_id' => 'demo_prop_009',
                'proposer_name' => 'Olga Sokolov',
                'supporter_id' => 'demo_supp_009',
                'supporter_name' => 'David Kim',
                'election_id' => $demoElection->id,
            ],
            [
                'candidacy_id' => 'DEMO_MAL_002',
                'user_id' => 'demo_user_010',
                'user_name' => 'Amara Okafor',
                'candidacy_name' => 'Amara Okafor',
                'post_id' => 'member_at_large',
                'post_name' => 'Member at Large',
                'proposer_id' => 'demo_prop_010',
                'proposer_name' => 'Thomas O\'Brien',
                'supporter_id' => 'demo_supp_010',
                'supporter_name' => 'Nina Kowalski',
                'election_id' => $demoElection->id,
            ],
        ];

        // Create or update candidates
        $created = 0;
        foreach ($candidates as $candidateData) {
            $candidate = DemoCandidate::firstOrCreate(
                ['candidacy_id' => $candidateData['candidacy_id']],
                $candidateData
            );
            if ($candidate->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info('✅ Demo candidates seeded successfully!');
        $this->command->info('   Total created: ' . $created . ' candidates');
        $this->command->info('   Total in table: ' . count($candidates) . ' candidates');
        $this->command->info('   Election: ' . $demoElection->name . ' (ID: ' . $demoElection->id . ')');
        $this->command->info('   Storage: demo_candidacies table');
        $this->command->info('');
        $this->command->info('Demo candidates by post:');
        $this->command->info('   - President: 2 candidates');
        $this->command->info('   - Vice President: 2 candidates');
        $this->command->info('   - Secretary: 2 candidates');
        $this->command->info('   - Treasurer: 2 candidates');
        $this->command->info('   - Member at Large: 2 candidates');
    }
}
