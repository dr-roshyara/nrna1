<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a platform admin user with credentials:
     * - Email: admin@publicdigit.org
     * - Password: password
     *
     * Usage: php artisan db:seed --class=PlatformAdminSeeder
     */
    public function run(): void
    {
        // Get the platform organisation (created by OrganisationSeeder)
        $platform = Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->firstOrFail();

        // Create admin user (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'admin@publicdigit.org'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
                'organisation_id' => $platform->id,
                'region' => 'Bayern',
                'email_verified_at' => now(),
            ]
        );

        // Create pivot record linking admin to platform org with 'admin' role
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $admin->id, 'organisation_id' => $platform->id],
            ['role' => 'admin']
        );

        $this->command->info('✅ Platform admin seeded successfully!');
        $this->command->info('   Email: admin@publicdigit.org');
        $this->command->info('   Password: password');
        $this->command->info('   Role: admin');
    }
}
