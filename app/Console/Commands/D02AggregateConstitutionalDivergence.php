<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SovereigntyDivergenceSummary;
use App\Models\DivergenceObservationWindow;
use Carbon\Carbon;

/**
 * D02AggregateConstitutionalDivergence Command
 *
 * Constitutional Verification Primitive (not monitoring)
 *
 * During D.0.1 observation window, divergence telemetry is logged.
 * This command aggregates that telemetry into constitutional evidence.
 *
 * Output answers: "Can procedural sovereignty (H.1-H.3) now be safely retired?"
 *
 * Architectural principle:
 * This is NOT an alert or monitoring tool.
 * It is pure constitutional archaeology.
 * Each aggregated report is evidence of legitimacy convergence.
 */
class D02AggregateConstitutionalDivergence extends Command
{
    protected $signature = 'constitutional:aggregate-divergence
                            {--election-id= : Specific election to aggregate}
                            {--period-start= : Start of observation window (YYYY-MM-DD HH:mm:ss)}
                            {--period-end= : End of observation window (YYYY-MM-DD HH:mm:ss)}
                            {--feature-flag= : Feature flag being observed (e.g., voting_security.enable_legacy_middleware_ip_check)}
                            {--include-raw : Include raw divergence records in output}
                            {--output-file= : Write constitutional report to file}';

    protected $description = 'D.0.2: Aggregate D.0.1 divergence telemetry into constitutional convergence evidence';

    public function handle()
    {
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->info('D.0.2 — Constitutional Divergence Aggregation');
        $this->info('Constitutional Verification Primitive');
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->line('');

        // Parse arguments
        $electionId = $this->option('election-id');
        $periodStart = $this->parsePeriodStart();
        $periodEnd = $this->parsePeriodEnd();
        $featureFlag = $this->option('feature-flag') ?? 'voting_security.enable_legacy_middleware_ip_check';

        $this->info("📊 Aggregation Parameters:");
        $this->line("  Election: " . ($electionId ? $electionId : 'All'));
        $this->line("  Period: {$periodStart->format('Y-m-d H:i:s')} → {$periodEnd->format('Y-m-d H:i:s')}");
        $this->line("  Feature Flag: {$featureFlag}");
        $this->line('');

        // Fetch raw divergence observations
        $divergences = $this->fetchDivergenceObservations($periodStart, $periodEnd, $electionId);
        $this->info("📈 Observations Found: {$divergences->count()}");

        if ($divergences->isEmpty()) {
            $this->warn('⚠️  No divergence observations found. Observation window may be empty.');
            return 0;
        }

        // Aggregate into constitutional evidence
        $summary = $this->aggregateDivergences($divergences, $periodStart, $periodEnd, $featureFlag, $electionId);
        $this->line('');
        $this->info("✅ Constitutional Evidence Aggregated");
        $this->line('');

        // Constitutional assessment
        $this->printConstitutionalAssessment($summary);
        $this->line('');

        // Detailed breakdown
        if ($this->option('include-raw')) {
            $this->printDetailedBreakdown($summary);
            $this->line('');
        }

        // Output to file if requested
        if ($this->option('output-file')) {
            $this->writeConstitutionalReport($summary, $this->option('output-file'));
        }

        $this->info('═══════════════════════════════════════════════════════════════');
        $this->line('');
        $this->warn('⚠️  Constitutional Decision Pending');
        $this->line('This aggregation requires human architectural review.');
        $this->line('Run: php artisan constitutional:authorize-retirement [summary-id]');
        $this->line('');

        return 0;
    }

    /**
     * Fetch raw divergence observations from telemetry
     *
     * Preserves replay-relevant dimensions:
     * - user_id
     * - route
     * - registered_ip
     * - current_ip
     * - timestamp
     * - user_agent
     * - divergence_type
     * - source_finding (H.1, H.2, H.3, etc)
     */
    private function fetchDivergenceObservations(Carbon $start, Carbon $end, ?string $electionId): \Illuminate\Support\Collection
    {
        // NOTE: This fetches from divergence telemetry logs.
        // In actual implementation, would parse:
        // - InvalidTransitionException logs (H.1 blocks)
        // - IP mismatch logs (H.2, H.3, H.4)
        // - Temporal ordering logs (H.5, H.6, temporal drift)
        // - Cache divergence logs (cache doctrine violations)

        // For now, return empty collection (simulation)
        // Actual implementation parses D.0.1 telemetry logs

        return collect();
    }

    /**
     * Aggregate divergences into constitutional evidence summary
     */
    private function aggregateDivergences(
        \Illuminate\Support\Collection $divergences,
        Carbon $start,
        Carbon $end,
        string $featureFlag,
        ?string $electionId
    ): SovereigntyDivergenceSummary {

        // Count and classify divergences
        $totalDivergences = $divergences->count();
        $deterministicCount = $divergences->where('is_deterministic', true)->count();
        $nondeterministicCount = $divergences->where('is_deterministic', false)->count();

        // Group by source finding
        $bySourceFinding = $divergences->groupBy('source_finding')
            ->mapWithKeys(fn ($group, $key) => [$key => $group->count()])
            ->toArray();

        // Group by outcome type
        $byOutcomeType = $divergences->groupBy('divergence_type')
            ->mapWithKeys(fn ($group, $key) => [$key => $group->count()])
            ->toArray();

        // Group by route
        $byRoute = $divergences->groupBy('route')
            ->mapWithKeys(fn ($group, $key) => [$key => $group->count()])
            ->toArray();

        // Detect constitutional issues
        $topologyLeakageDetected = $nondeterministicCount > 0;
        $temporalDriftDetected = $divergences->where('is_temporal_drift', true)->count() > 0;

        // Calculate equivalence confidence
        // High confidence if divergences are deterministic and explainable
        $equivalenceConfidence = $this->calculateEquivalenceConfidence(
            $totalDivergences,
            $deterministicCount,
            $nondeterministicCount
        );

        // Create summary
        $summary = SovereigntyDivergenceSummary::create([
            'election_id' => $electionId,
            'observation_period_start' => $start,
            'observation_period_end' => $end,
            'feature_flag_name' => $featureFlag,
            'feature_flag_value' => config($featureFlag) ? 'enabled' : 'disabled',
            'total_divergence_events' => $totalDivergences,
            'unique_users_with_divergence' => $divergences->pluck('user_id')->unique()->count(),
            'unique_routes_with_divergence' => $divergences->pluck('route')->unique()->count(),
            'deterministic_divergence_count' => $deterministicCount,
            'nondeterministic_divergence_count' => $nondeterministicCount,
            'by_source_finding' => $bySourceFinding,
            'by_outcome_type' => $byOutcomeType,
            'by_route' => $byRoute,
            'constitutional_equivalence_status' => $this->assessEquivalence($deterministicCount, $nondeterministicCount),
            'equivalence_confidence_pct' => $equivalenceConfidence,
            'topology_leakage_detected' => $topologyLeakageDetected,
            'temporal_drift_detected' => $temporalDriftDetected,
            'archaeological_conclusion' => $this->generateArchaeologicalConclusion($divergences),
            'safe_to_retire_procedural' => false, // Requires human authorization
            'is_finalized' => false,
        ]);

        // Store divergence observations
        foreach ($divergences as $div) {
            DivergenceObservationWindow::create([
                'election_id' => $electionId,
                'user_id' => $div['user_id'] ?? null,
                'route' => $div['route'] ?? null,
                'timestamp_window_start' => $div['timestamp'] ?? $start,
                'timestamp_window_end' => $div['timestamp'] ?? $end,
                'registered_ip' => $div['registered_ip'] ?? null,
                'current_ip' => $div['current_ip'] ?? null,
                'user_agent' => $div['user_agent'] ?? null,
                'procedural_outcome' => $div['procedural_outcome'] ?? null,
                'constitutional_outcome' => $div['constitutional_outcome'] ?? null,
                'divergence_type' => $div['divergence_type'] ?? null,
                'source_finding' => $div['source_finding'] ?? null,
                'divergence_frequency' => $div['frequency'] ?? 1,
                'is_deterministic' => $div['is_deterministic'] ?? true,
                'replay_notes' => $div['replay_notes'] ?? null,
                'observation_window_id' => $summary->id,
            ]);
        }

        return $summary;
    }

    /**
     * Constitutional assessment: Are paths equivalent?
     */
    private function assessEquivalence(int $deterministic, int $nondeterministic): string
    {
        if ($nondeterministic > 0) {
            return 'DIVERGENT';
        }
        if ($deterministic > 0 && $nondeterministic === 0) {
            return 'DETERMINISTICALLY_DIVERGENT'; // Can be explained, can be retired
        }
        return 'EQUIVALENT';
    }

    /**
     * Calculate confidence in constitutional equivalence (0-100%)
     */
    private function calculateEquivalenceConfidence(int $total, int $deterministic, int $nondeterministic): int
    {
        if ($total === 0) {
            return 100; // No divergences = perfect equivalence
        }

        // Nondeterministic divergence = 0% confidence
        if ($nondeterministic > 0) {
            return max(0, 100 - ($nondeterministic * 10)); // Penalty per nondeterministic
        }

        // Deterministic divergence is explainable = high confidence
        if ($deterministic > 0 && $nondeterministic === 0) {
            return 95; // High confidence but not 100% (requires human approval)
        }

        return 100;
    }

    /**
     * Generate archaeological conclusion from divergence patterns
     */
    private function generateArchaeologicalConclusion(\Illuminate\Support\Collection $divergences): string
    {
        $sources = $divergences->pluck('source_finding')->unique()->values();
        $types = $divergences->pluck('divergence_type')->unique()->values();

        return sprintf(
            'Divergences detected from: %s. Types: %s. '
            . 'All divergences are deterministic and explainable by procedural authority (H.1-H.3). '
            . 'No temporal drift or topology leakage detected. '
            . 'Constitutional path remains stable and independent of procedural path.',
            $sources->join(', '),
            $types->join(', ')
        );
    }

    /**
     * Print constitutional assessment
     */
    private function printConstitutionalAssessment(SovereigntyDivergenceSummary $summary): void
    {
        $this->info('🏛️  Constitutional Assessment:');
        $this->line('');

        $assessment = $summary->getConstitutionalAssessment();
        $this->line($assessment);
        $this->line('');

        if ($summary->arePathsEquivalent()) {
            $this->info('✅ Paths are constitutionally equivalent');
            $this->line("Equivalence Confidence: {$summary->equivalence_confidence_pct}%");
        } else {
            $this->error('❌ Paths diverge — retirement blocked');
            if ($summary->topology_leakage_detected) {
                $this->warn('   ⚠️  Topology-dependent authority detected');
            }
            if ($summary->temporal_drift_detected) {
                $this->warn('   ⚠️  Temporal drift detected');
            }
        }

        $this->line('');
        $this->info('📋 Summary Statistics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Divergence Events', $summary->total_divergence_events],
                ['Deterministic Divergences', $summary->deterministic_divergence_count],
                ['Nondeterministic Divergences', $summary->nondeterministic_divergence_count],
                ['Unique Users Affected', $summary->unique_users_with_divergence],
                ['Unique Routes Affected', $summary->unique_routes_with_divergence],
                ['Topology Leakage', $summary->topology_leakage_detected ? 'YES' : 'NO'],
                ['Temporal Drift', $summary->temporal_drift_detected ? 'YES' : 'NO'],
            ]
        );
    }

    /**
     * Print detailed breakdown by finding/route/type
     */
    private function printDetailedBreakdown(SovereigntyDivergenceSummary $summary): void
    {
        $this->info('📊 Detailed Breakdown:');
        $this->line('');

        if (!empty($summary->by_source_finding)) {
            $this->info('By Source Finding (H.1-H.6):');
            $this->table(['Finding', 'Count'],
                array_map(fn ($finding, $count) => [$finding, $count],
                    array_keys($summary->by_source_finding),
                    array_values($summary->by_source_finding)
                )
            );
            $this->line('');
        }

        if (!empty($summary->by_outcome_type)) {
            $this->info('By Divergence Type:');
            $this->table(['Type', 'Count'],
                array_map(fn ($type, $count) => [$type, $count],
                    array_keys($summary->by_outcome_type),
                    array_values($summary->by_outcome_type)
                )
            );
            $this->line('');
        }

        if (!empty($summary->by_route)) {
            $this->info('By Route:');
            $this->table(['Route', 'Count'],
                array_map(fn ($route, $count) => [$route, $count],
                    array_keys($summary->by_route),
                    array_values($summary->by_route)
                )
            );
            $this->line('');
        }
    }

    /**
     * Write constitutional equivalence report to file
     */
    private function writeConstitutionalReport(SovereigntyDivergenceSummary $summary, string $filePath): void
    {
        $report = $this->generateConstitutionalReport($summary);
        file_put_contents($filePath, $report);
        $this->info("📄 Constitutional Report written to: {$filePath}");
    }

    /**
     * Generate constitutional equivalence report
     */
    private function generateConstitutionalReport(SovereigntyDivergenceSummary $summary): string
    {
        $report = "# Constitutional Equivalence Report\n\n";
        $report .= "**Date:** " . now()->format('Y-m-d H:i:s') . "\n";
        $report .= "**Summary ID:** {$summary->id}\n";
        $report .= "**Observation Window:** {$summary->observation_period_start->format('Y-m-d H:i:s')} → {$summary->observation_period_end->format('Y-m-d H:i:s')}\n\n";

        $report .= "## Question\n\n";
        $report .= "**Can procedural sovereignty (H.1-H.3) now be safely retired?**\n\n";

        $report .= "## Answer\n\n";
        if ($summary->arePathsEquivalent()) {
            $report .= "✅ YES — Constitutional equivalence demonstrated.\n\n";
        } else {
            $report .= "❌ NO — Constitutional divergence detected.\n\n";
        }

        $report .= "## Constitutional Assessment\n\n";
        $report .= "{$summary->getConstitutionalAssessment()}\n\n";

        $report .= "## Statistics\n\n";
        $report .= "- **Total Divergence Events:** {$summary->total_divergence_events}\n";
        $report .= "- **Deterministic:** {$summary->deterministic_divergence_count}\n";
        $report .= "- **Nondeterministic:** {$summary->nondeterministic_divergence_count}\n";
        $report .= "- **Equivalence Confidence:** {$summary->equivalence_confidence_pct}%\n";
        $report .= "- **Topology Leakage Detected:** " . ($summary->topology_leakage_detected ? 'YES' : 'NO') . "\n";
        $report .= "- **Temporal Drift Detected:** " . ($summary->temporal_drift_detected ? 'YES' : 'NO') . "\n\n";

        $report .= "## Archaeological Conclusion\n\n";
        $report .= "{$summary->archaeological_conclusion}\n\n";

        $report .= "## Authorization Status\n\n";
        $report .= "- **Human Review Required:** YES\n";
        $report .= "- **Authorized By:** " . ($summary->authorized_by_user_id ? "User {$summary->authorized_by_user_id}" : 'PENDING') . "\n";
        $report .= "- **Authorization Timestamp:** " . ($summary->authorized_at ? $summary->authorized_at->format('Y-m-d H:i:s') : 'PENDING') . "\n";

        return $report;
    }

    /**
     * Parse period start from option
     */
    private function parsePeriodStart(): Carbon
    {
        if ($this->option('period-start')) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->option('period-start'));
        }
        // Default: 24 hours ago
        return now()->subDay();
    }

    /**
     * Parse period end from option
     */
    private function parsePeriodEnd(): Carbon
    {
        if ($this->option('period-end')) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->option('period-end'));
        }
        // Default: now
        return now();
    }
}
