<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Domain\Election\Enum\ElectionMode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackfillVoterSourceStrategy extends Command
{
    protected $signature = 'app:backfill-voter-source-strategy {--audit-only : Report elections missing snapshot without fixing} {--chunk=100 : Batch size for large datasets}';

    protected $description = 'Backfill voter_source_strategy for pre-Phase-2 elections from organisation governance policy';

    public function handle()
    {
        $auditOnly = $this->option('audit-only');
        $chunkSize = $this->option('chunk');
        $mode = $auditOnly ? 'AUDIT' : 'REPAIR';

        $this->info("🔍 {$mode} MODE: Backfilling voter_source_strategy...\n");

        $processed = 0;
        $skipped = 0;
        $failed = 0;

        Election::query()
            ->withoutGlobalScopes()
            ->whereNull('voter_source_strategy')
            ->chunkById($chunkSize, function ($elections) use ($auditOnly, &$processed, &$skipped, &$failed) {
                foreach ($elections as $election) {
                    try {
                        // Election already has snapshot (shouldn't happen due to whereNull, but be safe)
                        if ($election->voter_source_strategy !== null) {
                            $skipped++;
                            continue;
                        }

                        // Derive strategy from organisation's governance policy (legitimate use)
                        $organisation = $election->organisation;
                        $strategy = ElectionMode::fromOrganisation($organisation)->value;

                        if ($auditOnly) {
                            // AUDIT MODE: Log without updating
                            Log::channel('constitutional_integrity')->info(
                                'Election missing voter_source_strategy (audit-only)',
                                [
                                    'election_id' => $election->id,
                                    'election_slug' => $election->slug,
                                    'organisation_id' => $organisation->id,
                                    'organisation_slug' => $organisation->slug,
                                    'derived_strategy' => $strategy,
                                ]
                            );
                            $this->line("  ℹ️  {$election->slug}: would set to {$strategy}");
                        } else {
                            // REPAIR MODE: Update election with derived snapshot
                            $election->update(['voter_source_strategy' => $strategy]);
                            $processed++;
                            $this->line("  ✅ {$election->slug}: backfilled with {$strategy}");
                        }
                    } catch (\Throwable $e) {
                        $failed++;
                        Log::channel('constitutional_integrity')->error(
                            'Backfill failed for election',
                            [
                                'election_id' => $election->id,
                                'election_slug' => $election->slug,
                                'error' => $e->getMessage(),
                            ]
                        );
                        $this->line("  ❌ {$election->slug}: backfill failed - {$e->getMessage()}");
                    }
                }
            });

        $this->info("\n📊 Summary:");
        if ($auditOnly) {
            $this->line("  Elections missing snapshot: {$processed}");
        } else {
            $this->line("  Elections backfilled: {$processed}");
            $this->line("  Elections skipped (already filled): {$skipped}");
            $this->line("  Elections failed: {$failed}");
        }

        if ($failed > 0) {
            $this->warn("\n⚠️  {$failed} elections failed backfill. Check logs for details.");
            $this->line("   Re-run this command to retry failures.");
        }

        if ($auditOnly && $processed > 0) {
            $this->info("\n💡 Tip: Run without --audit-only to backfill {$processed} elections.");
        }

        return $failed > 0 ? 1 : 0;
    }
}
