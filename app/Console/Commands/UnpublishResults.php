<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElectionService;

class UnpublishResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:unpublish-results {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish election results and make them unavailable to voters';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if results are already unpublished
        if (!ElectionService::areResultsPublished()) {
            $this->warn('⚠️  Election results are already unpublished!');
            return 0;
        }

        // Confirmation prompt unless --confirm flag is used
        if (!$this->option('confirm')) {
            $this->info('📊 Current status: Results are published and available to voters');
            if (!$this->confirm('Are you sure you want to unpublish the election results? This will make them unavailable to voters.')) {
                $this->info('❌ Operation cancelled.');
                return 1;
            }
        }

        try {
            // Unpublish results using the service
            if (ElectionService::unpublishResults()) {
                $this->info('✅ Election results have been successfully unpublished!');
                $this->info('🔒 Results are now unavailable at: /election/result');
                $this->newLine();
                $this->info('📊 Only users with "view-election-results" permission can now access results.');

                return 0;
            } else {
                $this->error('❌ Failed to unpublish results.');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to unpublish results: ' . $e->getMessage());
            return 1;
        }
    }
}
