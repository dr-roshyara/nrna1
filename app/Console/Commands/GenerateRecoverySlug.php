<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VotingSecurityService;

class GenerateRecoverySlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:recover-voter {user_id} {--reason=Expired slug recovery} {--admin=Election Committee}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a recovery voting slug for a voter with expired/problematic access';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(VotingSecurityService $securityService)
    {
        $userId = $this->argument('user_id');
        $reason = $this->option('reason');
        $adminName = $this->option('admin');

        // Find the user
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ User with ID {$userId} not found.");
            return 1;
        }

        // Display user info
        $this->info("🔍 User Found:");
        $this->line("   Name: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   NRNA ID: {$user->nrna_id}");
        $this->line("   Is Voter: " . ($user->is_voter ? 'Yes' : 'No'));
        $this->line("   Can Vote: " . ($user->can_vote ? 'Yes' : 'No'));
        $this->line("   Has Voted: " . ($user->has_voted ? 'Yes' : 'No'));

        // Check eligibility
        if ($user->has_voted) {
            $this->error("❌ Cannot generate recovery slug - user has already completed voting.");
            return 1;
        }

        if (!$user->is_voter || !$user->can_vote) {
            $this->error("❌ Cannot generate recovery slug - user is not eligible to vote.");
            return 1;
        }

        // Show current slugs
        $currentSlugs = VoterSlug::where('user_id', $user->id)->get();
        if ($currentSlugs->count() > 0) {
            $this->info("\n🔍 Current Slugs:");
            foreach ($currentSlugs as $slug) {
                $status = $slug->is_active ? 'ACTIVE' : 'INACTIVE';
                $expired = $slug->expires_at < now() ? '(EXPIRED)' : '';
                $this->line("   {$slug->slug} - Step {$slug->current_step} - {$status} {$expired}");
                $this->line("   Created: {$slug->created_at}, Expires: {$slug->expires_at}");
            }
        } else {
            $this->line("\n📋 No existing slugs found.");
        }

        // Confirm action
        if (!$this->confirm("\n❓ Generate recovery slug for {$user->name}?")) {
            $this->info("Operation cancelled.");
            return 0;
        }

        try {
            // Deactivate existing slugs
            $deactivatedCount = 0;
            $existingSlugs = VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($existingSlugs as $slug) {
                $slug->update([
                    'is_active' => false,
                    'step_meta' => array_merge($slug->step_meta ?? [], [
                        'deactivated_by_admin' => true,
                        'deactivated_reason' => 'recovery_slug_generated',
                        'admin_name' => $adminName,
                        'cli_recovery' => true,
                    ])
                ]);
                $deactivatedCount++;
            }

            if ($deactivatedCount > 0) {
                $this->info("✅ Deactivated {$deactivatedCount} existing slug(s)");
            }

            // Generate new recovery slug
            $result = $securityService->secureSlugGeneration($user, 'cli_admin_recovery');

            if (!$result['success']) {
                $this->error('❌ Failed to generate recovery slug: ' . implode(', ', $result['reasons']));
                return 1;
            }

            $newSlug = $result['slug'];

            // Add recovery metadata
            $newSlug->update([
                'step_meta' => array_merge($newSlug->step_meta ?? [], [
                    'recovery_slug' => true,
                    'admin_generated' => true,
                    'admin_name' => $adminName,
                    'recovery_reason' => $reason,
                    'generated_at' => now()->toISOString(),
                    'cli_generated' => true,
                ])
            ]);

            // Success output
            $this->info("\n🎉 Recovery slug generated successfully!");
            $this->line("   Slug: {$newSlug->slug}");
            $this->line("   Expires: {$newSlug->expires_at}");
            $this->line("   Step: {$newSlug->current_step}");

            $recoveryUrl = url("/v/{$newSlug->slug}/code/create");
            $this->info("\n🔗 Recovery URL:");
            $this->line("   {$recoveryUrl}");

            $this->info("\n📋 Instructions:");
            $this->line("   1. Provide this URL to the voter");
            $this->line("   2. The voter can use this link to continue/restart voting");
            $this->line("   3. This slug expires in 30 minutes");
            $this->line("   4. The voter will start from step 1 (code verification)");

            \Log::info('CLI Recovery slug generated', [
                'admin_name' => $adminName,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'new_slug' => $newSlug->slug,
                'reason' => $reason,
                'expires_at' => $newSlug->expires_at,
                'method' => 'cli_command',
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error generating recovery slug: {$e->getMessage()}");
            \Log::error('CLI Recovery slug generation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin' => $adminName,
            ]);
            return 1;
        }
    }
}