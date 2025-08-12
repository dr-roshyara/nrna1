<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Publisher;
use App\Models\Election;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SetupPublisherCommand extends Command
{
    protected $signature = 'election:setup-publisher 
                          {--email=admin@nrna.org : Publisher email address}
                          {--password=password123 : Publisher login password}
                          {--auth-password=auth123 : Publisher authorization password}
                          {--name=Test Publisher : Publisher name}';

    protected $description = 'Create a test publisher account for testing authorization';

    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $authPassword = $this->option('auth-password');
        $name = $this->option('name');

        $this->info('Setting up publisher account...');

        try {
            // 1. Create or update user account
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]
            );

            $this->info("✅ User created/updated: {$user->email}");

            // 2. Create publisher role if it doesn't exist
            $publisherRole = Role::firstOrCreate(['name' => 'publisher']);
            $this->info("✅ Publisher role ensured");

            // 3. Assign publisher role to user
            if (!$user->hasRole('publisher')) {
                $user->assignRole('publisher');
                $this->info("✅ Publisher role assigned to user");
            }

            // 4. Create publisher record
            $publisher = Publisher::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'title' => 'Test Publisher - Election Committee',
                    'password' => Hash::make($password),
                    'authorization_password' => Hash::make($authPassword),
                    'is_active' => true,
                ]
            );

            $this->info("✅ Publisher record created: {$publisher->name}");

            // 5. Create test election if none exists
            $election = Election::first();
            if (!$election) {
                $election = Election::create([
                    'name' => 'Test Election 2024',
                    'description' => 'Test election for development',
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addDays(7),
                    'authorization_session_id' => \Str::uuid(),
                    'authorization_deadline' => now()->addHours(24),
                ]);
                $this->info("✅ Test election created: {$election->name}");
            }

            // 6. Set election to sealed phase for testing
            $election->update([
                'phase' => 'sealed',
                'authorization_session_id' => \Str::uuid(),
            ]);
            $this->info("✅ Election set to 'sealed' phase for authorization testing");

            // 7. Display login credentials
            $this->newLine();
            $this->info('🎉 Publisher setup complete!');
            $this->newLine();
            $this->line('📋 LOGIN CREDENTIALS:');
            $this->line("   Email: {$email}");
            $this->line("   Password: {$password}");
            $this->newLine();
            $this->line('🔐 AUTHORIZATION CREDENTIALS:');
            $this->line("   Authorization Password: {$authPassword}");
            $this->newLine();
            $this->line('🌐 ACCESS URLs:');
            $this->line('   Login: http://127.0.0.1:8000/login');
            $this->line('   Publisher Dashboard: http://127.0.0.1:8000/publisher/authorize');
            $this->newLine();
            $this->info('Now you can:');
            $this->info('1. Login with the credentials above');
            $this->info('2. Visit /publisher/authorize to test the authorization interface');
            $this->info('3. Use the authorization password to complete the authorization process');

        } catch (\Exception $e) {
            $this->error('❌ Setup failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}