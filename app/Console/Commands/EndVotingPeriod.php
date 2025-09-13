<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElectionService;
use Spatie\Permission\Models\Permission;

class EndVotingPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:end-voting
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End the election voting period';

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

        if (!$isVotingActive) {
            $this->warn('⚠️  Voting period is already inactive!');

            if (!$this->option('force') && !$this->confirm('Do you want to force end the voting period anyway?')) {
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

        // Show voting statistics before ending
        $this->info('');
        $this->info('📊 Current Voting Statistics:');
        try {
            $totalCodes = \App\Models\Code::count();
            $activeSessions = \App\Models\Code::where('can_vote_now', true)->count();
            $completedVotes = \App\Models\Code::where('has_voted', true)->count();

            $this->table(['Metric', 'Count'], [
                ['Total Codes Generated', $totalCodes],
                ['Active Voting Sessions', $activeSessions],
                ['Completed Votes', $completedVotes],
            ]);

            if ($activeSessions > 0) {
                $this->warn("⚠️  Warning: {$activeSessions} active voting sessions will be terminated!");
            }
        } catch (\Exception $e) {
            $this->warn('Could not fetch voting statistics: ' . $e->getMessage());
        }

        // Confirmation prompt (unless --force is used)
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to end the voting period?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        try {
            // End voting period
            if (ElectionService::endVotingPeriod()) {
                $this->info('✅ Voting period has been ended successfully!');

                // Log the action
                $this->info('📋 Actions performed:');
                $this->line('   • Voting period flag removed');
                $this->line('   • Active voting sessions terminated');
                $this->line('   • Cache cleared');
                $this->line('   • Voting is now inactive');

                $this->info('');
                $this->info('🎯 Next steps:');
                $this->line('   • Election results can now be reviewed');
                $this->line('   • Use "php artisan election:publish-results" to publish results');
                $this->line('   • No new voting codes can be generated');

                return 0;
            } else {
                $this->error('❌ Failed to end voting period. Check logs for details.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error ending voting period: ' . $e->getMessage());
            return 1;
        }
    }
}