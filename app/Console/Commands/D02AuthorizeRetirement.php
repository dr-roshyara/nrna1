<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SovereigntyDivergenceSummary;
use App\Models\User;

/**
 * D02AuthorizeRetirement Command
 *
 * Constitutional Sovereign Decision Gate
 *
 * After D.0.2 aggregation generates ConstitutionalEquivalenceReport,
 * a human architect must AUTHORIZE whether procedural sovereignty
 * (H.1-H.3) can be safely retired.
 *
 * This command enforces that retirement authority remains with
 * human governance, not autonomous telemetry tools.
 *
 * Architectural principle:
 * Deletion authority must NEVER migrate into monitoring/telemetry infrastructure.
 */
class D02AuthorizeRetirement extends Command
{
    protected $signature = 'constitutional:authorize-retirement
                            {summary-id : ID of SovereigntyDivergenceSummary}
                            {--approve : Approve retirement (default: interactive)}
                            {--deny : Deny retirement}
                            {--reason= : Explanation for decision}';

    protected $description = 'D.0.2: Human architect authorizes procedural sovereignty retirement';

    public function handle()
    {
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('D.0.2 — Retirement Authorization Gate');
        $this->info('Constitutional Sovereign Decision');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->line('');

        // Fetch summary
        $summaryId = $this->argument('summary-id');
        $summary = SovereigntyDivergenceSummary::find($summaryId);

        if (!$summary) {
            $this->error("❌ Summary not found: {$summaryId}");
            return 1;
        }

        if ($summary->is_finalized) {
            $this->warn("⚠️  Summary already finalized. Cannot modify.");
            $this->line("Authorized by: User {$summary->authorized_by_user_id}");
            $this->line("Timestamp: {$summary->authorized_at}");
            return 1;
        }

        // Display constitutional evidence
        $this->displayConstitutionalEvidence($summary);
        $this->line('');

        // Get decision
        $approved = $this->getDecision();

        if (!$approved && !$this->option('deny')) {
            $this->info('Authorization cancelled.');
            return 0;
        }

        // Get current user (or prompt for architect identity)
        $architect = $this->getArchitect();

        if (!$architect) {
            $this->error('❌ Could not determine architect identity.');
            return 1;
        }

        // Record authorization
        $this->recordAuthorization($summary, $architect, $approved);
        $this->line('');

        if ($approved) {
            $this->info('✅ RETIREMENT AUTHORIZED');
            $this->info("Architect: {$architect->name}");
            $this->info("Timestamp: " . now()->format('Y-m-d H:i:s'));
            $this->line('');
            $this->warn('⚠️  Next Step: Run D.0.3 to retire H.1-H.3 procedural authority');
        } else {
            $this->error('❌ RETIREMENT DENIED');
            $this->info("Architect: {$architect->name}");
            $this->info("Reason: " . ($this->option('reason') ?? 'Not specified'));
            $this->line('');
            $this->warn('⚠️  Procedural sovereignty remains active pending resolution.');
        }

        $this->line('');
        $this->info('═══════════════════════════════════════════════════════════════');

        return 0;
    }

    /**
     * Display the constitutional evidence report
     */
    private function displayConstitutionalEvidence(SovereigntyDivergenceSummary $summary): void
    {
        $this->info('📋 Constitutional Evidence:');
        $this->line('');
        $this->line($summary->getConstitutionalAssessment());
        $this->line('');

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Divergence Events', $summary->total_divergence_events],
                ['Deterministic Divergences', $summary->deterministic_divergence_count],
                ['Nondeterministic Divergences', $summary->nondeterministic_divergence_count],
                ['Equivalence Confidence', "{$summary->equivalence_confidence_pct}%"],
                ['Topology Leakage Detected', $summary->topology_leakage_detected ? 'YES ⚠️' : 'NO'],
                ['Temporal Drift Detected', $summary->temporal_drift_detected ? 'YES ⚠️' : 'NO'],
                ['Constitutional Status', $summary->constitutional_equivalence_status],
            ]
        );

        $this->line('');
        $this->info('Architectural Conclusion:');
        $this->line($summary->archaeological_conclusion);
    }

    /**
     * Get decision from architect (interactive or flag-based)
     */
    private function getDecision(): bool
    {
        if ($this->option('approve')) {
            return true;
        }

        if ($this->option('deny')) {
            return false;
        }

        // Interactive decision
        $this->line('');
        $this->warn('ARCHITECTURAL DECISION REQUIRED');
        $this->line('');
        $this->line('Question: Can procedural sovereignty (H.1-H.3) be safely retired?');
        $this->line('');

        return $this->confirm(
            'Do you authorize procedural sovereignty retirement?',
            false // Default to NO (safer)
        );
    }

    /**
     * Get architect identity (from auth, or prompt)
     */
    private function getArchitect(): ?User
    {
        // Try authenticated user
        if (auth()->check()) {
            return auth()->user();
        }

        // Prompt for architect email
        $email = $this->ask('Architect email address:');

        return User::where('email', $email)->first();
    }

    /**
     * Record authorization decision in database
     */
    private function recordAuthorization(SovereigntyDivergenceSummary $summary, User $architect, bool $approved): void
    {
        $summary->authorize($architect, $approved);

        // Log for audit trail
        \Log::info('Constitutional Retirement Authorization', [
            'summary_id' => $summary->id,
            'architect_id' => $architect->id,
            'architect_email' => $architect->email,
            'approved' => $approved,
            'reason' => $this->option('reason'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
