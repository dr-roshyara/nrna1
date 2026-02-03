<?php

namespace Database\Seeders;

use App\Models\Election;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates demo and real election templates.
     *
     * @return void
     */
    public function run()
    {
        // Create demo election
        Election::firstOrCreate(
            ['slug' => 'demo-election'],
            [
                'name' => 'Demo Election',
                'slug' => 'demo-election',
                'description' => 'Test election to familiarize users with the voting process',
                'type' => 'demo',
                'is_active' => true,
                'settings' => [
                    'allow_multiple_registrations' => false,
                    'require_approval' => true,
                    'show_results' => true,
                ],
            ]
        );

        // Create real election
        Election::firstOrCreate(
            ['slug' => 'real-election'],
            [
                'name' => 'Real Election',
                'slug' => 'real-election',
                'description' => 'Official election for all registered voters',
                'type' => 'real',
                'is_active' => false,  // Start inactive until configured
                'settings' => [
                    'allow_multiple_registrations' => false,
                    'require_approval' => true,
                    'show_results' => false,
                    'show_results_after' => true,
                ],
            ]
        );

        $this->command->info('Elections seeded successfully!');
    }
}
