<?php

namespace App\Console\Commands;

use App\Contexts\Election\Application\Service\ResolvesEvidencePreservationWindow;
use App\Models\Election;
use DateTimeImmutable;
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
     *
     * Constitutional Policy 2 — the Retention Invariant: evidence required for a legally
     * permissible challenge must never expire before that challenge can no longer be
     * initiated or resolved. The age cutoff alone cannot honour that, so deletion is now
     * gated by each folder's election Evidence Preservation Window.
     *
     * The guard changes WHAT is deleted, never HOW: traversal, removal and reporting are
     * untouched. Audit/Retention owns the decision; Election answers whether the window
     * is open (R-65).
     */
    public function handle(ResolvesEvidencePreservationWindow $preservation): int
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
        $now = new DateTimeImmutable();
        $deletedCount = 0;

        // Iterate directories in audit path
        foreach (File::directories($auditPath) as $folder) {
            $modifiedTime = File::lastModified($folder);

            // Delete if older than cutoff AND its preservation window has closed
            if ($modifiedTime < $cutoff && !$this->isPreserved($folder, $preservation, $now)) {
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

    /**
     * Is this folder's evidence still constitutionally required?
     *
     * Fails closed twice over: a folder whose name cannot be parsed, and a folder whose
     * election cannot be found, are both preserved. Evidence is never treated as expired
     * on the strength of a missing fact.
     */
    private function isPreserved(
        string $folder,
        ResolvesEvidencePreservationWindow $preservation,
        DateTimeImmutable $now
    ): bool {
        $slug = $this->electionSlugOf(basename($folder));

        if ($slug === null) {
            return true;
        }

        // Retention is a system-wide job: audit folders are not tenant-scoped and a CLI
        // run has no tenant session, so the BelongsToTenant global scope would hide every
        // election and preserve everything indiscriminately. Bypassing it is what lets the
        // guard see the elections it must judge — it grants no cross-tenant capability to
        // any user-facing path. A soft-deleted election is simply not found, which the
        // caller already treats as "preserve".
        $election = Election::query()->withoutGlobalScopes()->where('slug', $slug)->first();

        if (!$election instanceof Election) {
            return true;
        }

        return $preservation->isOpenFor($election, $now);
    }

    /**
     * Audit folders are named `{slug}_{Ymd}_{Hi}` by ElectionAuditService; the slug is
     * everything before that timestamp suffix. Returns null when the name does not
     * match, which the caller treats as unresolvable.
     */
    private function electionSlugOf(string $folderName): ?string
    {
        if (preg_match('/^(?<slug>.+)_\d{8}_\d{4}$/', $folderName, $matches) !== 1) {
            return null;
        }

        return $matches['slug'];
    }
}
