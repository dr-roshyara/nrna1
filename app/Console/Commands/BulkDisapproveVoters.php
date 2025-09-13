<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class BulkDisapproveVoters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voters:cant-vote
                            {--force : Skip confirmation prompt}
                            {--include-voted : Include users who have already voted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk disapprove all voters by setting can_vote=false for all registered voters. Alternative: Use web interface at /election/viewboard';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚫 Bulk Voter Disapproval Tool');
        $this->info('=============================');

        // Get current admin user info
        $currentUser = auth()->user();
        if (!$currentUser) {
            $this->error('❌ Error: No authenticated user found. Please run this command in proper context.');
            return 1;
        }

        $adminName = $currentUser->name ?? 'System Admin';

        // Build query for registered voters
        $query = User::where('is_voter', true);

        // By default, exclude users who have already voted (safety measure)
        if (!$this->option('include-voted')) {
            $query->where('has_voted', false);
        }

        // Get voters who are currently approved
        $votersToDisapprove = $query->where('can_vote', true)->get();
        $totalVoters = User::where('is_voter', true)->count();
        $currentlyApproved = User::where('is_voter', true)->where('can_vote', true)->count();
        $alreadyDisapproved = User::where('is_voter', true)->where('can_vote', false)->count();
        $votedUsers = User::where('is_voter', true)->where('has_voted', true)->count();

        // Show current status
        $this->info('Current Voter Status:');
        $this->table(['Status', 'Count'], [
            ['Total Registered Voters', $totalVoters],
            ['Currently Approved', $currentlyApproved],
            ['Already Disapproved', $alreadyDisapproved],
            ['Have Already Voted', $votedUsers],
            ['Will be Disapproved', $votersToDisapprove->count()],
        ]);

        if ($votersToDisapprove->count() === 0) {
            $this->info('✅ All eligible voters are already disapproved!');
            return 0;
        }

        // Show some sample voters that will be affected
        if ($votersToDisapprove->count() > 0) {
            $this->info('Sample voters to be disapproved:');
            $sampleVoters = $votersToDisapprove->take(5);
            $sampleData = $sampleVoters->map(function($voter) {
                return [
                    'ID' => $voter->id,
                    'Name' => $voter->name,
                    'Email' => $voter->email,
                    'Voted' => $voter->has_voted ? '✅ Yes' : '❌ No',
                    'Current Status' => $voter->can_vote ? '✅ Approved' : '❌ Disapproved'
                ];
            });
            $this->table(['ID', 'Name', 'Email', 'Voted', 'Current Status'], $sampleData->toArray());

            if ($votersToDisapprove->count() > 5) {
                $this->info("... and " . ($votersToDisapprove->count() - 5) . " more voters");
            }
        }

        // Safety warnings
        $this->warn('⚠️  WARNING: This action will remove voting access from ' . $votersToDisapprove->count() . ' voters.');

        if (!$this->option('include-voted') && $votedUsers > 0) {
            $this->info("ℹ️  Note: {$votedUsers} voters who have already voted will be preserved.");
            $this->info("   Use --include-voted flag to disapprove them as well.");
        }

        if ($this->option('include-voted') && $votedUsers > 0) {
            $this->error("⚠️  DANGER: You are about to disapprove voters who have already voted!");
            $this->error("   This may cause data integrity issues.");
        }

        // Confirmation prompt (unless --force is used)
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to bulk disapprove these voters?')) {
                $this->info('Operation cancelled.');
                return 0;
            }

            // Double confirmation for risky operations
            if ($this->option('include-voted') && $votedUsers > 0) {
                if (!$this->confirm('Are you ABSOLUTELY sure you want to disapprove voters who have already voted?')) {
                    $this->info('Operation cancelled for safety.');
                    return 0;
                }
            }
        }

        try {
            $disapproved = 0;
            $failed = 0;

            $this->info('Processing voters...');
            $progressBar = $this->output->createProgressBar($votersToDisapprove->count());

            foreach ($votersToDisapprove as $voter) {
                try {
                    // Update voter disapproval
                    $voter->update([
                        'can_vote' => false,
                        'suspendedBy' => $adminName,
                        'suspended_at' => now(),
                        'approvedBy' => null,          // Clear approval info when disapproving
                        // Note: We keep voting_ip for audit trail
                    ]);

                    $disapproved++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("Failed to disapprove voter {$voter->name} (ID: {$voter->id}): " . $e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            // Show results
            $this->info('✅ Bulk disapproval completed!');
            $this->table(['Result', 'Count'], [
                ['Successfully Disapproved', $disapproved],
                ['Failed', $failed],
                ['Total Processed', $disapproved + $failed],
            ]);

            if ($disapproved > 0) {
                $this->info('📋 Changes made:');
                $this->line('   • can_vote set to false');
                $this->line("   • suspendedBy set to '{$adminName}'");
                $this->line('   • suspended_at set to current timestamp');
                $this->line('   • approvedBy cleared');
                $this->line('   • voting_ip preserved for audit trail');

                $this->info('');
                $this->info('🎯 Next steps:');
                $this->line('   • Disapproved voters cannot access voting');
                $this->line('   • Re-approve individually at /voters/index');
                $this->line('   • Or bulk approve with: php artisan voters:can-vote');
            }

            return $failed > 0 ? 1 : 0;

        } catch (\Exception $e) {
            $this->error('❌ Error during bulk disapproval: ' . $e->getMessage());
            return 1;
        }
    }
}