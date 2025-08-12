<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPublisherCommand extends Command
{
    protected $signature = 'user:make-publisher {email? : User email address}';
    protected $description = 'Assign publisher role to a user';

    public function handle()
    {
        // Get email from argument or ask for it
        $email = $this->argument('email') ?: $this->ask('Enter user email address');
        
        try {
            // Find user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("❌ User with email '{$email}' not found!");
                
                // Show available users
                $this->info("\n📋 Available users:");
                User::all()->each(function($user) {
                    $this->line("   • {$user->email} ({$user->name})");
                });
                
                return 1;
            }

            // Create publisher role if doesn't exist
            $publisherRole = Role::firstOrCreate(['name' => 'publisher']);
            $this->info("✅ Publisher role ensured");

            // Create publisher permissions if don't exist
            $permissions = [
                'authorize-results',
                'view-publisher-dashboard',
                'manage-election-results'
            ];

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $publisherRole->givePermissionTo($permission);
            }
            $this->info("✅ Publisher permissions ensured");

            // Assign role to user
            if ($user->hasRole('publisher')) {
                $this->info("ℹ️ User already has publisher role");
            } else {
                $user->assignRole('publisher');
                $this->info("✅ Publisher role assigned to user");
            }

            // Also add election-committee role for good measure
            $committeeRole = Role::firstOrCreate(['name' => 'election-committee']);
            if (!$user->hasRole('election-committee')) {
                $user->assignRole('election-committee');
                $this->info("✅ Election committee role also assigned");
            }

            // Show final status
            $this->newLine();
            $this->info("🎉 Success! User permissions updated:");
            $this->line("   👤 User: {$user->name} ({$user->email})");
            $this->line("   🏷️ Roles: " . $user->roles->pluck('name')->join(', '));
            $this->line("   🔑 Permissions: " . $user->permissions->pluck('name')->join(', '));
            
            $this->newLine();
            $this->info("🌐 You can now access:");
            $this->line("   • Publisher Dashboard: http://127.0.0.1:8000/publisher/authorize");
            $this->line("   • Election Committee: http://127.0.0.1:8000/election/committee");

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}