<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElectionService;

class PublishResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:publish-results {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish election results and make them available to voters';

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
        // Check if results are already published
        if (ElectionService::areResultsPublished()) {
            $this->warn('⚠️  Election results are already published!');
            return 0;
        }

        // Confirmation prompt unless --confirm flag is used
        if (!$this->option('confirm')) {
            if (!$this->confirm('Are you sure you want to publish the election results? This action cannot be undone.')) {
                $this->info('❌ Operation cancelled.');
                return 1;
            }
        }

        try {
            // Publish results using the service
            if (ElectionService::publishResults()) {
                $this->info('✅ Election results have been successfully published!');
                $this->info('🔗 Results are now available at: /election/result');
                $this->newLine();
                $this->info('📊 Voters can now access the election results from the dashboard.');

                return 0;
            } else {
                $this->error('❌ Failed to publish results.');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Failed to publish results: ' . $e->getMessage());
            return 1;
        }
    }

}
