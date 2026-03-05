<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organisation;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the foundational organisations:
     * - Platform (ID=1): The default organisation for demo/testing users
     *   All new users without explicit organisation assignment go here.
     *
     * Usage: php artisan db:seed --class=OrganisationSeeder
     */
    public function run(): void
    {
        // Create Platform Organisation
        // This is the DEFAULT organisation for all users
        Organisation::firstOrCreate(
            ['slug' => 'platform'],
            [
                'name' => 'Platform',
                'type' => 'platform',
                'is_default' => true,
            ]
        );

        $this->command->info('✅ Organisations seeded successfully!');
        $this->command->info('   Platform - Default organisation (type=platform, is_default=true)');
    }
}
