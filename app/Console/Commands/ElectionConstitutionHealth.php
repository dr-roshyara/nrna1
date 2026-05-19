<?php

namespace App\Console\Commands;

use App\Application\Election\Monitoring\ConstitutionalMetricsContract;
use Illuminate\Console\Command;

/**
 * Command: php artisan election:constitution:health
 *
 * Displays constitutional governance health and strict-mode readiness.
 * Can be used as a deployment gate in CI/CD pipelines.
 */
class ElectionConstitutionHealth extends Command
{
    protected $signature = 'election:constitution:health';

    protected $description = 'Display constitutional governance health and Phase 3.2 strict-mode readiness';

    public function handle(ConstitutionalMetricsContract $metrics): int
    {
        $health = $metrics->getHealth();

        $this->renderHeader();
        $this->renderOverallStatus($health);
        $this->renderViolationSummary($health);
        $this->renderEvaluationContext($health);
        $this->renderStrictModeReadiness($health);
        $this->renderMetadata($health);

        return $health['strict_mode_ready'] ? 0 : 1;
    }

    private function renderHeader(): void
    {
        $this->line('');
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║   ELECTION CONSTITUTIONAL GOVERNANCE - HEALTH REPORT          ║');
        $this->line('║   Phase 3.2 Strict-Mode Eligibility Assessment               ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->line('');
    }

    private function renderOverallStatus(array $health): void
    {
        $statusIcon = $health['is_healthy'] ? '✓' : '✗';
        $statusColor = $health['is_healthy'] ? 'info' : 'error';
        $statusText = $health['is_healthy'] ? 'HEALTHY' : 'VIOLATIONS DETECTED';

        $this->line("<{$statusColor}>{$statusIcon} Overall Health: {$statusText}</{$statusColor}>");
        $this->line('');
    }

    private function renderViolationSummary(array $health): void
    {
        $this->line('<fg=cyan>━━━ 24-Hour Violation Summary (by Severity) ━━━</>');

        $severities = $health['violations_by_severity'] ?? [];
        $total = $health['violations_24h'];

        $this->line("Total violations: <info>{$total}</info>");

        if ($total > 0) {
            $this->line('  Breakdown:');
            if (($severities['low'] ?? 0) > 0) {
                $this->line("    • <fg=yellow>LOW</> (deprecated reads): {$severities['low']}</>");
            }
            if (($severities['medium'] ?? 0) > 0) {
                $this->line("    • <fg=yellow>MEDIUM</> (illegal queries): {$severities['medium']}</>");
            }
            if (($severities['high'] ?? 0) > 0) {
                $this->line("    • <fg=red>HIGH</> (drift violations): {$severities['high']}</>");
            }
            if (($severities['critical'] ?? 0) > 0) {
                $this->line("    • <fg=red>CRITICAL</> (illegal actions): {$severities['critical']}</>");
            }
        } else {
            $this->line('  <fg=green>All metrics clear</>');
        }
        $this->line('');
    }

    private function renderEvaluationContext(array $health): void
    {
        $this->line('<fg=cyan>━━━ Constitutional Evaluation Context ━━━</>');
        $evals = $health['lifecycle_evaluations'] ?? 0;

        if ($evals > 0) {
            $this->line("Lifecycle evaluations (24h): <info>{$evals}</info>");
            $violationRate = $health['violations_24h'] > 0
                ? round(($health['violations_24h'] / $evals) * 100, 2)
                : 0;
            $this->line("Violation rate: <info>{$violationRate}%</info>");
        } else {
            $this->line('No lifecycle evaluations recorded in 24h window.');
        }
        $this->line('');
    }

    private function renderStrictModeReadiness(array $health): void
    {
        $this->line('<fg=cyan>━━━ Phase 3.2 Strict Mode Eligibility ━━━</>');

        if ($health['strict_mode_ready']) {
            $this->line('<fg=green;options=bold>✓ READY FOR STRICT MODE ACTIVATION</fg=green;options=bold>');
            $this->line('');
            $this->line('Evidence:');
            $this->line('  • 0 violations in 24-hour window');
            $this->line('  • No deprecated field access detected');
            $this->line('  • Query guards operating cleanly');
            $this->line('  • No drift violations observed');
            $this->line('');
            $this->line('<fg=green>This system can safely enter strict-mode governance.</>');
        } else {
            $this->line('<fg=red;options=bold>✗ NOT READY FOR STRICT MODE</fg=red;options=bold>');
            $this->line('');
            $this->line('Issues requiring resolution:');

            if ($health['violations_24h'] > 0) {
                $count = $health['violations_24h'];
                $this->line("  • {$count} constitutional violations must be resolved");
            }

            $this->line('');
            $this->line('Required before strict mode activation:');
            $this->line('  • Zero violations in 24-hour window');
            $this->line('  • All deprecated patterns removed');
            $this->line('  • 24-hour quiet period must pass');
        }
        $this->line('');
    }

    private function renderMetadata(array $health): void
    {
        $this->line('<fg=gray>Report generated: ' . $health['report_timestamp'] . '</>');
        $this->line('');
    }
}
