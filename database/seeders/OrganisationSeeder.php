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
        // Create Platform Organisation (ID=1)
        // This is the DEFAULT organisation for all users
        Organisation::firstOrCreate(
            ['slug' => 'platform'],
            [
                'id' => 1,
                'name' => 'Platform',
                'type' => 'other',
            ]
        );

        $this->command->info('✅ Organisations seeded successfully!');
        $this->command->info('   Platform (ID=1) - Default organisation');
    }
}
