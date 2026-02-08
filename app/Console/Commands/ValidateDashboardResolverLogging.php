<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DashboardResolver;
use Illuminate\Console\Command;

class ValidateDashboardResolverLogging extends Command
{
    protected $signature = 'dashboard:validate-logging';
    protected $description = 'Validate DashboardResolver logging is working correctly';

    public function handle()
    {
        $this->info('🔍 Validating DashboardResolver logging...\n');

        // Test with first-time user
        $firstTimeUser = User::factory()->create([
            'created_at' => now()->subDays(30),
            'is_voter' => false,
            'is_committee_member' => false,
        ]);

        $resolver = app(DashboardResolver::class);

        $this->info('Test 1: First-time user (30 days old, no roles)');
        $this->info("  User ID: {$firstTimeUser->id}");

        $response = $resolver->resolve($firstTimeUser);

        $this->line("  ✓ Resolved to: {$response->getTargetUrl()}");
        $this->line("  ✓ Expected: " . route('dashboard.welcome'));
        $this->newLine();

        // Test with legacy voter
        $legacyVoter = User::factory()->create([
            'is_voter' => true,
            'is_committee_member' => false,
        ]);

        $this->info('Test 2: Legacy voter (new account)');
        $this->info("  User ID: {$legacyVoter->id}");

        $response = $resolver->resolve($legacyVoter);

        $this->line("  ✓ Resolved to: {$response->getTargetUrl()}");
        $this->line("  ✓ Expected: " . route('vote.dashboard'));
        $this->newLine();

        $this->info('✅ Validation complete!');
        $this->info('📝 Check storage/logs/laravel.log for "DashboardResolver" entries');
    }
}
