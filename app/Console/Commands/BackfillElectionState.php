<?php

namespace App\Console\Commands;

use App\Application\Election\Governance\ElectionStateWriteContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackfillElectionState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-election-state {--audit-only : Report divergences without fixing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill/audit election state consistency with SSOT engine';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $auditOnly = $this->option('audit-only');
        $mode = $auditOnly ? 'AUDIT' : 'REPAIR';

        $this->info("🔍 {$mode} MODE: Checking election states for divergence...");

        $elections = \App\Models\Election::query()
            ->withoutGlobalScopes()
            ->get();

        $divergences = 0;
        $fixed = 0;

        foreach ($elections as $election) {
            // Compute correct state via SSOT engine
            $engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
            $correctState = $engine->getState($election)->value;

            // Check for divergence
            if ($election->state !== $correctState) {
                $divergences++;

                if ($auditOnly) {
                    // AUDIT MODE: Log divergence, don't fix
                    Log::channel('constitutional_integrity')->warning(
                        "Election state divergence detected (audit-only)",
                        [
                            'election_id' => $election->id,
                            'election_slug' => $election->slug,
                            'cached_state' => $election->state,
                            'computed_state' => $correctState,
                            'action' => 'Manual review required',
                        ]
                    );
                    $this->line("  ⚠️  {$election->slug}: {$election->state} → {$correctState} (not fixed)");
                } else {
                    // REPAIR MODE: Fix via authorized context
                    ElectionStateWriteContext::authorize(function() use ($election, $correctState) {
                        $election->update(['state' => $correctState]);
                    });
                    $fixed++;
                    $this->line("  ✅ {$election->slug}: {$election->state} → {$correctState} (fixed)");
                }
            }
        }

        $this->info("\n📊 Summary:");
        $this->line("  Divergences found: {$divergences}");
        if (!$auditOnly) {
            $this->line("  Divergences fixed: {$fixed}");
        }

        if ($auditOnly && $divergences > 0) {
            $this->info("\n💡 Tip: Run without --audit-only to repair divergences.");
        }

        return 0;
    }
}
