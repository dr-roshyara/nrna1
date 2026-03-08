<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupExpiredVoterSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:clean-expired-slugs
                            {--hours=24 : Hours old to consider for cleanup (default: 24 hours)}
                            {--detailed : Show detailed cleanup information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired voter slugs older than specified hours. Runs as background task to maintain database hygiene.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoursOld = (int) $this->option('hours');
        $detailed = $this->option('detailed');
        $cutoffTime = Carbon::now()->subHours($hoursOld);

        $this->line('🧹 Starting cleanup of expired voter slugs...');
        $this->line("   Cutoff time: {$cutoffTime->toDateTimeString()}");

        try {
            // Clean up expired real voter slugs
            $realDeleted = $this->cleanupVoterSlugs($cutoffTime, $detailed);

            // Clean up expired demo voter slugs
            $demoDeleted = $this->cleanupDemoVoterSlugs($cutoffTime, $detailed);

            $totalDeleted = $realDeleted + $demoDeleted;

            if ($totalDeleted > 0) {
                $this->info("✅ Successfully cleaned up {$totalDeleted} expired voter slugs");
                Log::info('Voter slugs cleanup completed', [
                    'real_deleted' => $realDeleted,
                    'demo_deleted' => $demoDeleted,
                    'total_deleted' => $totalDeleted,
                    'cutoff_time' => $cutoffTime->toDateTimeString(),
                ]);
            } else {
                $this->info('✅ No expired voter slugs to clean up');
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error during cleanup: {$e->getMessage()}");
            Log::error('Voter slugs cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Clean up expired real voter slugs
     *
     * @param Carbon $cutoffTime
     * @param bool $verbose
     * @return int Number of deleted records
     */
    private function cleanupVoterSlugs(Carbon $cutoffTime, bool $verbose): int
    {
        // Only delete soft-deleted expired slugs
        $slugsToDelete = VoterSlug::onlyTrashed()
            ->where('expires_at', '<', $cutoffTime)
            ->withoutGlobalScopes()
            ->count();

        if ($slugsToDelete > 0) {
            if ($verbose) {
                $this->line("   • Found {$slugsToDelete} soft-deleted real voter slugs to permanently remove");
            }

            $deleted = VoterSlug::onlyTrashed()
                ->where('expires_at', '<', $cutoffTime)
                ->withoutGlobalScopes()
                ->forceDelete();

            if ($verbose) {
                $this->line("   • Permanently deleted {$deleted} real voter slugs");
            }

            return $deleted;
        }

        return 0;
    }

    /**
     * Clean up expired demo voter slugs
     *
     * @param Carbon $cutoffTime
     * @param bool $verbose
     * @return int Number of deleted records
     */
    private function cleanupDemoVoterSlugs(Carbon $cutoffTime, bool $verbose): int
    {
        // Demo slugs might not be soft-deleted, so handle both cases
        $slugsToDelete = DemoVoterSlug::where('expires_at', '<', $cutoffTime)
            ->withoutGlobalScopes()
            ->count();

        if ($slugsToDelete > 0) {
            if ($verbose) {
                $this->line("   • Found {$slugsToDelete} demo voter slugs to delete");
            }

            $deleted = DemoVoterSlug::where('expires_at', '<', $cutoffTime)
                ->withoutGlobalScopes()
                ->delete();

            if ($verbose) {
                $this->line("   • Deleted {$deleted} demo voter slugs");
            }

            return $deleted;
        }

        return 0;
    }
}
