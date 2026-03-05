<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Phase 1: Foundation
            // Create platform organisation (single, default instance)
            OrganisationSeeder::class,

            // Phase 2: Admin & Permissions
            // Create platform admin user with proper role pivot
            PlatformAdminSeeder::class,

            // Phase 3: Elections
            // Create demo and real elections for platform
            ElectionSeeder::class,

            // Phase 4: Election Structure
            // Create posts (positions) for demo election
            DemoElectionSeeder::class,

            // Phase 5: Demo Data
            // Create demo candidates for testing
            DemoCandidacySeeder::class,
        ]);
    }
}
