<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AuditCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:cleanup {--days=30 : Delete folders older than N days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete election audit folders older than specified days (default: 30 days)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $auditPath = storage_path('logs/audit');

        // Handle: Audit directory doesn't exist
        if (!File::exists($auditPath)) {
            $this->info('No audit logs found.');
            return self::SUCCESS;
        }

        // Calculate cutoff timestamp
        $cutoff = now()->subDays($days)->timestamp;
        $deletedCount = 0;

        // Iterate directories in audit path
        foreach (File::directories($auditPath) as $folder) {
            $modifiedTime = File::lastModified($folder);

            // Delete if older than cutoff
            if ($modifiedTime < $cutoff) {
                $folderName = basename($folder);
                File::deleteDirectory($folder);
                $this->line("Deleted: {$folderName}");
                $deletedCount++;
            }
        }

        // Report results
        $this->info("Cleanup complete. {$deletedCount} folder(s) deleted.");

        return self::SUCCESS;
    }
}
