<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class BulkApproveVoters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voters:can-vote
                            {--force : Skip confirmation prompt}
                            {--exclude-voted : Exclude users who have already voted}
                            {--enable-ip-check : Set voting_ip from user_ip to enable IP checking}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk approve all voters by setting can_vote=true for all registered voters. Alternative: Use web interface at /election/viewboard';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🗳️  Bulk Voter Approval Tool');
        $this->info('============================');

        // Get current admin user info
        $currentUser = auth()->user();
        if (!$currentUser) {
            $this->error('❌ Error: No authenticated user found. Please run this command in proper context.');
            return 1;
        }

        $adminName = $currentUser->name ?? 'System Admin';

        // Build query for registered voters
        $query = User::where('is_voter', true);

        // Option to exclude already voted users
        if ($this->option('exclude-voted')) {
            $query->where('has_voted', false);
        }

        // Get voters who are not yet approved
        $votersToApprove = $query->where('can_vote', false)->get();
        $totalVoters = User::where('is_voter', true)->count();
        $alreadyApproved = User::where('is_voter', true)->where('can_vote', true)->count();

        // Show current status
        $this->info('Current Voter Status:');
        $this->table(['Status', 'Count'], [
            ['Total Registered Voters', $totalVoters],
            ['Already Approved', $alreadyApproved],
            ['Pending Approval', $votersToApprove->count()],
            ['Will be Approved', $votersToApprove->count()],
        ]);

        // Show IP checking configuration
        if ($this->option('enable-ip-check')) {
            $this->warn('🔒 IP Address Checking: ENABLED');
            $this->line('   • voting_ip will be set from user_ip');
            $this->line('   • Voters must vote from their registered IP address');
        } else {
            $this->info('🔓 IP Address Checking: DISABLED');
            $this->line('   • voting_ip will be left empty (null)');
            $this->line('   • Voters can vote from any IP address');
        }

        if ($votersToApprove->count() === 0) {
            $this->info('✅ All registered voters are already approved!');
            return 0;
        }

        // Show some sample voters that will be affected
        if ($votersToApprove->count() > 0) {
            $this->info('Sample voters to be approved:');
            $sampleVoters = $votersToApprove->take(5);
            $sampleData = $sampleVoters->map(function($voter) {
                return [
                    'ID' => $voter->id,
                    'Name' => $voter->name,
                    'Email' => $voter->email,
                    'IP' => $voter->user_ip ?? 'Not recorded',
                    'Current Status' => $voter->can_vote ? '✅ Approved' : '⏳ Pending'
                ];
            });
            $this->table(['ID', 'Name', 'Email', 'IP', 'Current Status'], $sampleData->toArray());

            if ($votersToApprove->count() > 5) {
                $this->info("... and " . ($votersToApprove->count() - 5) . " more voters");
            }
        }

        // Confirmation prompt (unless --force is used)
        if (!$this->option('force')) {
            $this->warn('⚠️  This will approve ' . $votersToApprove->count() . ' voters for voting access.');

            if (!$this->confirm('Are you sure you want to bulk approve all pending voters?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        try {
            $approved = 0;
            $failed = 0;

            $this->info('Processing voters...');
            $progressBar = $this->output->createProgressBar($votersToApprove->count());

            foreach ($votersToApprove as $voter) {
                try {
                    // Prepare update data
                    $updateData = [
                        'can_vote' => true,
                        'approvedBy' => $adminName,
                        'suspendedBy' => null,          // Clear suspension info when approved
                        'suspended_at' => null          // Clear suspension timestamp
                    ];

                    // Conditionally set voting_ip based on flag
                    if ($this->option('enable-ip-check')) {
                        $updateData['voting_ip'] = $voter->user_ip; // Enable IP checking
                    } else {
                        $updateData['voting_ip'] = null; // Disable IP checking
                    }

                    // Update voter approval
                    $voter->update($updateData);

                    $approved++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("Failed to approve voter {$voter->name} (ID: {$voter->id}): " . $e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            // Show results
            $this->info('✅ Bulk approval completed!');
            $this->table(['Result', 'Count'], [
                ['Successfully Approved', $approved],
                ['Failed', $failed],
                ['Total Processed', $approved + $failed],
            ]);

            if ($approved > 0) {
                $this->info('📋 Changes made:');
                $this->line('   • can_vote set to true');
                if ($this->option('enable-ip-check')) {
                    $this->line('   • voting_ip copied from user_ip (IP checking enabled)');
                } else {
                    $this->line('   • voting_ip set to null (IP checking disabled)');
                }
                $this->line("   • approvedBy set to '{$adminName}'");
                $this->line('   • Suspension info cleared');

                $this->info('');
                $this->info('🎯 Next steps:');
                $this->line('   • Approved voters can now access voting');
                $this->line('   • Start voting period with: php artisan election:start-voting');
                $this->line('   • Monitor voting at /election/viewboard');
            }

            return $failed > 0 ? 1 : 0;

        } catch (\Exception $e) {
            $this->error('❌ Error during bulk approval: ' . $e->getMessage());
            return 1;
        }
    }
}