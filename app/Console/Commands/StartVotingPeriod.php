<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElectionService;
use Spatie\Permission\Models\Permission;

class StartVotingPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:start-voting
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the election voting period';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🗳️  Election Voting Period Control');
        $this->info('==================================');

        // Check current status
        $isVotingActive = ElectionService::isVotingPeriodActive();
        $hasVotingStarted = ElectionService::hasVotingStarted();

        if ($isVotingActive) {
            $this->warn('⚠️  Voting period is already active!');

            if (!$this->option('force') && !$this->confirm('Do you want to restart the voting period?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        // Show current election status
        $this->info('Current Election Status:');
        $this->table(['Property', 'Value'], [
            ['Election Active', config('election.is_active') ? '✅ Yes' : '❌ No'],
            ['Results Published', ElectionService::areResultsPublished() ? '✅ Yes' : '❌ No'],
            ['Voting Started', $hasVotingStarted ? '✅ Yes' : '❌ No'],
            ['Voting Period Active', $isVotingActive ? '✅ Yes' : '❌ No'],
        ]);

        // Confirmation prompt (unless --force is used)
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to start the voting period?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        try {
            // Start voting period
            if (ElectionService::startVotingPeriod()) {
                $this->info('✅ Voting period has been started successfully!');

                // Log the action
                $this->info('📋 Actions performed:');
                $this->line('   • Voting period flag created');
                $this->line('   • Cache cleared');
                $this->line('   • Voting is now active');

                $this->info('');
                $this->info('🎯 Next steps:');
                $this->line('   • Voters can now generate voting codes');
                $this->line('   • Election results are hidden during voting');
                $this->line('   • Use "php artisan election:end-voting" to end the voting period');

                return 0;
            } else {
                $this->error('❌ Failed to start voting period. Check logs for details.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error starting voting period: ' . $e->getMessage());
            return 1;
        }
    }
}