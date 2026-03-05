<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the initial election setup:
     * - Demo Election (for testing - all users eligible)
     * - Real Election (for production - timing restricted)
     *
     * Usage: php artisan db:seed --class=ElectionSeeder
     *
     * Note: Elections belong to the Platform organisation, not a tenant.
     * Uses UUID-based organisation lookup instead of hardcoded IDs.
     */
    public function run()
    {
        // Get platform organisation (created by OrganisationSeeder)
        $platform = Organisation::getDefaultPlatform();

        // DEMO ELECTION - For Testing
        // Features:
        // - All users can vote (no can_vote_now restriction)
        // - Data is separate from real elections (demo_votes table)
        // - Can be reset/cleared for testing
        // - Safe for testing voting workflows
        // - Belongs to Platform organisation
        Election::withoutGlobalScopes()->firstOrCreate(
            ['slug' => 'demo-election'],
            [
                'name' => 'Demo Election - Testing Only',
                'type' => 'demo',
                'description' => 'Test the voting system with demo candidates. All users can vote.',
                'is_active' => true,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addMonths(3),
                'organisation_id' => $platform->id,
            ]
        );

        // REAL ELECTION - For Production
        // Features:
        // - Only users with can_vote_now=1 can vote (timing restricted)
        // - Data stored in real votes/results tables
        // - Official election results
        // - Permanent audit trail
        // - Belongs to Platform organisation
        Election::withoutGlobalScopes()->firstOrCreate(
            ['slug' => '2024-general-election'],
            [
                'name' => '2024 General Election',
                'type' => 'real',
                'description' => 'Official voting for the current election period',
                'is_active' => true,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addWeeks(2),
                'organisation_id' => $platform->id,
            ]
        );

        $this->command->info('✅ Elections seeded successfully!');
        $this->command->info('');
        $this->command->info('ELECTION SETUP:');
        $this->command->info('   Demo Election (slug: demo-election)');
        $this->command->info('   - Type: demo');
        $this->command->info('   - Access: /election/demo/start');
        $this->command->info('   - Eligibility: All users allowed');
        $this->command->info('   - Storage: demo_votes table');
        $this->command->info('');
        $this->command->info('   Real Election (slug: 2024-general-election)');
        $this->command->info('   - Type: real');
        $this->command->info('   - Access: /election/select or /vote/create (default)');
        $this->command->info('   - Eligibility: can_vote_now == 1 only');
        $this->command->info('   - Storage: votes table');
        $this->command->info('');
        $this->command->info('SECURITY:');
        $this->command->info('   Both elections store votes anonymously (no user_id)');
        $this->command->info('   Authorization via voting_code hash only');
    }
}
