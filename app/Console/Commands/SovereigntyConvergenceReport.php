<?php

namespace App\Console\Commands;

use App\Infrastructure\Observation\DivergenceTelemetryStore;
use Illuminate\Console\Command;

/**
 * sovereignty:convergence-report
 *
 * D.0.2 — Sovereignty Convergence Certification Command.
 *
 * Reads D.0.1 shadow-mode divergence telemetry and produces a structured
 * convergence report answering the constitutional question:
 *
 *   "Are procedural sovereignty and constitutional sovereignty
 *    still producing materially different legitimacy interpretations?"
 *
 * CONSTITUTIONAL LAW:
 * - Observational only — summarizes, classifies, preserves evidence
 * - Never determines retirement safety — D.0.3 requires human governance
 * - Preserves divergence lineage (route, actor, evidence, timing, category)
 * - A single unexplained divergence may matter more than 10,000 matches
 */
class SovereigntyConvergenceReport extends Command
{
    protected $signature = 'sovereignty:convergence-report
        {--since= : ISO 8601 start timestamp}
        {--until= : ISO 8601 end timestamp}
        {--json : Output as JSON instead of human-readable table}
        {--observations-path= : Path to observations JSONL file (for testing)}';

    protected $description = 'Generate D.0.2 sovereignty convergence certification report';

    public function handle(DivergenceTelemetryStore $store): int
    {
        if ($path = $this->option('observations-path')) {
            $store = new DivergenceTelemetryStore($path);
        }

        $entries = $store->all();

        if (empty($entries)) {
            $this->warn('No divergence observations found.');
            $this->newLine();
            $this->line('The store at ' . storage_path('logs/divergence.observations.jsonl') . ' is empty.');
            $this->line('No procedural-to-constitutional divergences have been recorded.');
            $this->newLine();
            $this->line('═══ Constitutional Status ═══');
            $this->line('  Convergence: N/A (no evaluations recorded)');
            $this->line('  D.0.3 retirement safety: See constitutional governance review.');
            $this->line('  (NOT automated — human authorization required.)');
            $this->newLine();

            return 0;
        }

        // Apply time filters
        if ($since = $this->option('since')) {
            $entries = array_values(array_filter(
                $entries,
                fn(array $e) => ($e['recorded_at'] ?? '') >= $since,
            ));
        }
        if ($until = $this->option('until')) {
            $entries = array_values(array_filter(
                $entries,
                fn(array $e) => ($e['recorded_at'] ?? '') <= $until,
            ));
        }

        if (empty($entries)) {
            $this->warn('No divergence observations match the specified time range.');
            return 0;
        }

        // Build the convergence report
        $total = count($entries);
        $divergences = $this->classifyDivergences($entries);
        $convergent = $total - count($divergences);
        $ratio = $total > 0 ? round(($convergent / $total) * 100, 1) : null;

        if ($this->option('json')) {
            return $this->outputJson($entries, $divergences, $convergent, $ratio);
        }

        return $this->outputHumanReadable($entries, $divergences, $convergent, $ratio);
    }

    private function classifyDivergences(array $entries): array
    {
        return array_values(array_filter(
            $entries,
            fn(array $e) => ($e['type'] ?? '') === 'sovereignty_leak',
        ));
    }

    private function outputHumanReadable(array $entries, array $divergences, int $convergent, ?float $ratio): int
    {
        $total = count($entries);

        $this->newLine();
        $this->line('═══════════════════════════════════════════════════════');
        $this->line('  D.0.2 — Sovereignty Convergence Report');
        $this->line('═══════════════════════════════════════════════════════');
        $this->newLine();

        // Period
        $recordedAt = array_column($entries, 'recorded_at');
        sort($recordedAt);
        $this->line('  Period:  ' . ($recordedAt[0] ?? 'N/A') . '  to  ' . ($recordedAt[$total - 1] ?? 'N/A'));
        $this->newLine();

        // Summary table
        $this->line('  ┌────────────────────────────┬────────────┐');
        $this->line('  │ Metric                     │ Value      │');
        $this->line('  ├────────────────────────────┼────────────┤');
        $this->line(sprintf('  │ Total evaluations          │ %-10d │', $total));
        $this->line(sprintf('  │ Convergent (matched)      │ %-10d │', $convergent));
        $this->line(sprintf('  │ Divergent (mismatched)    │ %-10d │', count($divergences)));
        $this->line(sprintf('  │ Convergence ratio         │ %-9s %% │', $ratio !== null ? number_format($ratio, 1) : 'N/A'));
        $this->line('  └────────────────────────────┴────────────┘');
        $this->newLine();

        // Divergence lineage
        if (!empty($divergences)) {
            $this->line('  ─── Divergence Lineage ───');
            $this->newLine();

            foreach ($divergences as $i => $d) {
                $this->line(sprintf(
                    '  %d. [%s] %s — user %s',
                    $i + 1,
                    $d['recorded_at'] ?? '?',
                    $d['type'] ?? 'unknown',
                    $d['user_id'] ?? '?',
                ));
                $this->line(sprintf(
                    '     Route: %s → IP mismatch (reg: %s vs cur: %s)',
                    $d['route'] ?? 'unknown',
                    $this->maskIp($d['registered_ip'] ?? '?'),
                    $this->maskIp($d['current_ip'] ?? '?'),
                ));
                $this->newLine();
            }
        }

        // Constitutional status
        $this->line('  ─── Constitutional Status ───');
        $this->newLine();

        if (count($divergences) === 0) {
            $this->line('  ✅ All observations converged.');
            $this->line('     No sovereignty divergence detected.');
        } else {
            $this->line('  ⚠️  ' . count($divergences) . ' divergence(s) detected.');
            $this->line('     Each requires human review before D.0.3 retirement.');
        }
        $this->newLine();

        $this->line('  D.0.3 retirement safety: See constitutional governance review.');
        $this->line('  (NOT automated — human authorization required.)');
        $this->newLine();

        return 0;
    }

    private function outputJson(array $entries, array $divergences, int $convergent, ?float $ratio): int
    {
        $recordedAt = array_column($entries, 'recorded_at');
        sort($recordedAt);

        $this->output->writeln(
            (string) json_encode([
                'report_type' => 'D.0.2_sovereignty_convergence',
                'period' => [
                    'from' => $recordedAt[0] ?? null,
                    'until' => $recordedAt[count($entries) - 1] ?? null,
                ],
                'summary' => [
                    'total_evaluations' => count($entries),
                    'convergent' => $convergent,
                    'divergent' => count($divergences),
                    'convergence_ratio' => $ratio,
                ],
                'divergences' => array_map(fn(array $d) => [
                    'recorded_at' => $d['recorded_at'] ?? null,
                    'type' => $d['type'] ?? null,
                    'user_id' => $d['user_id'] ?? null,
                    'route' => $d['route'] ?? null,
                    'registered_ip' => $this->maskIp($d['registered_ip'] ?? ''),
                    'current_ip' => $this->maskIp($d['current_ip'] ?? ''),
                    'source' => $d['source'] ?? null,
                ], $divergences),
                'constitutional_status' => count($divergences) === 0
                    ? 'all_converged'
                    : 'divergence_detected',
                'retirement_safety' => 'HUMAN_REVIEW_REQUIRED',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return 0;
    }

    /**
     * Mask IP for display: last octet replaced with 'x'
     */
    private function maskIp(?string $ip): string
    {
        if ($ip === null || $ip === '') {
            return '?';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = 'x';
            return implode('.', $parts);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // For IPv6, mask the last group
            $parts = explode(':', $ip);
            $last = array_key_last($parts);
            $parts[$last] = 'xxxx';
            return implode(':', $parts);
        }

            return substr($ip, 0, 8) . '...';
    }
}
