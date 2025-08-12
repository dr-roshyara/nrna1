<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CheckUserPermissionsCommand extends Command
{
    protected $signature = 'user:check-permissions {email? : User email address}';
    protected $description = 'Check roles and permissions for a user';

    public function handle()
    {
        $email = $this->argument('email') ?: $this->ask('Enter user email address');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User with email '{$email}' not found!");
            
            $this->info("\n📋 Available users:");
            User::all()->each(function($user) {
                $this->line("   • {$user->email} ({$user->name})");
            });
            
            return 1;
        }

        $this->info("👤 User: {$user->name} ({$user->email})");
        $this->newLine();

        // Show roles
        $roles = $user->roles;
        if ($roles->count() > 0) {
            $this->info("🏷️ Roles:");
            $roles->each(function($role) {
                $this->line("   ✅ {$role->name}");
            });
        } else {
            $this->line("🏷️ Roles: None assigned");
        }

        $this->newLine();

        // Show direct permissions
        $permissions = $user->permissions;
        if ($permissions->count() > 0) {
            $this->info("🔑 Direct Permissions:");
            $permissions->each(function($permission) {
                $this->line("   ✅ {$permission->name}");
            });
        } else {
            $this->line("🔑 Direct Permissions: None assigned");
        }

        $this->newLine();

        // Show all permissions (including through roles)
        $allPermissions = $user->getAllPermissions();
        if ($allPermissions->count() > 0) {
            $this->info("🎯 All Available Permissions (via roles + direct):");
            $allPermissions->each(function($permission) {
                $this->line("   ✅ {$permission->name}");
            });
        }

        $this->newLine();

        // Check specific publisher permissions
        $publisherChecks = [
            'publisher role' => $user->hasRole('publisher'),
            'election-committee role' => $user->hasRole('election-committee'),
            'authorize-results permission' => $user->can('authorize-results'),
            'view-publisher-dashboard permission' => $user->can('view-publisher-dashboard'),
        ];

        $this->info("🔍 Publisher Access Check:");
        foreach ($publisherChecks as $check => $hasAccess) {
            $status = $hasAccess ? '✅' : '❌';
            $this->line("   {$status} {$check}");
        }

        $this->newLine();

        if ($user->hasRole('publisher')) {
            $this->info("🎉 You have publisher access! You can visit:");
            $this->line("   🌐 http://127.0.0.1:8000/publisher/authorize");
        } else {
            $this->error("⚠️ You don't have publisher access. Run this to fix:");
            $this->line("   php artisan user:make-publisher {$email}");
        }

        return 0;
    }
}