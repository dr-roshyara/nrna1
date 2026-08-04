<?php

declare(strict_types=1);

/**
 * Evidence Dashboard renderer — a PROJECTION generator.
 *
 * Renders one regenerable markdown page from observation-stream data:
 * what the evidence shows so far, and — deliberately — HONEST EMPTY CELLS
 * for every outcome question that has no data yet, each naming the source
 * that will fill it. Visible gaps drive collection.
 *
 * Scope guard: reports and asks; never judges. The page is derived and
 * non-authoritative; regenerate at will (corrected I-4 discipline).
 */
final class EvidenceDashboardRenderer
{
    /** @param array<string,mixed> $d */
    public static function render(array $d): string
    {
        $m = $d['metrics'] ?? null;
        $tp = $d['test_presence'] ?? null;
        $lc = $d['lcom4'] ?? null;
        $oe = (int) ($d['oe_entries'] ?? 0);

        $out = [];
        $out[] = '# Engineering Evidence Dashboard';
        $out[] = '';
        $out[] = '> **PROJECTION — derived, non-authoritative. Regenerate at will:** `php scripts/observations/dashboard-renderer.php`';
        $out[] = '> Measured subject: **engineering behavior and outcomes — never platform activity.**';
        $out[] = '';
        $out[] = '## Observation streams';
        $out[] = '';
        if ($m !== null) {
            $out[] = sprintf(
                '- **Static metrics:** %d snapshots · mean CBO: %s · WARN classes: %s · hotspots: %s · latest commit: `%s`',
                $m['snapshots'], $m['mean_cbo'], $m['warn'] ?? '—', $m['hotspots'] ?? '—', $m['commit'] ?? '—'
            );
        } else {
            $out[] = '- **Static metrics:** no snapshots yet';
        }
        if ($tp !== null) {
            $out[] = sprintf(
                '- **Test presence:** %d observations · production-without-tests events: %d',
                $tp['observations'], $tp['production_without_tests']
            );
        } else {
            $out[] = '- **Test presence:** no snapshots yet';
        }
        if ($lc !== null && ($lc['worst'] ?? []) !== []) {
            $worst = [];
            foreach ($lc['worst'] as $class => $value) {
                $worst[] = sprintf('%s LCOM4=%d', $class, $value);
            }
            $out[] = sprintf('- **LCOM4:** %d runs · worst: %s', $lc['runs'], implode(' · ', $worst));
        } else {
            $out[] = '- **LCOM4:** no snapshots yet';
        }
        $out[] = sprintf('- **Operational evidence:** OE entries: %d', $oe);
        $out[] = '';
        $out[] = '## Recommendations (advisory — the developer decides)';
        $out[] = '';
        $rec = $d['recommendations'] ?? null;
        if ($rec !== null && ($rec['issued'] ?? 0) > 0) {
            $out[] = sprintf(
                '- issued: %d · decided: %d (accepted %d · ignored %d · deferred %d) · **open: %d**',
                $rec['issued'],
                $rec['decided'] ?? 0,
                $rec['accepted'] ?? 0,
                $rec['ignored'] ?? 0,
                $rec['deferred'] ?? 0,
                $rec['issued'] - ($rec['decided'] ?? 0)
            );
            $out[] = '- *acceptance is measured, never assumed — issuance counts alone are vanity*';
        } else {
            $out[] = '- no recommendations yet';
        }
        $out[] = '';
        $funnel = $d['funnel'] ?? null;
        if ($funnel !== null) {
            $out[] = '## Recommendation Lifecycle (the KPI — where does the pipeline break?)';
            $out[] = '';
            $out[] = sprintf(
                '**issued %d → decided %d → outcomes %d → assessments %d** *(unique recommendations per stage; drop-offs are findings, not failures — an organizational mirror)*',
                $funnel['issued'],
                $funnel['decided'],
                $funnel['outcomes'],
                $funnel['assessments']
            );
            $out[] = '';
        }
        $lead = $d['lead_times'] ?? null;
        if ($lead !== null && $lead !== []) {
            $out[] = '### Lead times (measurements only — sample sizes shown; no causal conclusions)';
            $out[] = '';
            foreach ($lead as $stage => $v) {
                $out[] = sprintf('- %s: **%.1fh** (n=%d)', str_replace('_', ' ', $stage), $v['mean_hours'], $v['n']);
            }
            $out[] = '';
        }
        $vel = $d['evidence_velocity'] ?? null;
        if ($vel !== null) {
            $out[] = '### Evidence Velocity (the operational KPI — completed recommendation cycles per week)';
            $out[] = '';
            $line = sprintf(
                '- completed cycles: %d · observation window: %.1f days',
                $vel['completed_cycles'],
                $vel['window_days']
            );
            if (($vel['per_week'] ?? null) !== null) {
                $line .= sprintf(' · **%.1f cycles/week**', $vel['per_week']);
            } else {
                $line .= ' · *rate withheld — window under 7 days is never extrapolated into a weekly rate*';
            }
            $out[] = $line;
            $out[] = '- *a completed cycle = recommendation → decision → outcome → assessment; everything downstream, including learning, depends on this rate*';
            $out[] = '';
        }
        $loop = $d['loop_completion'] ?? null;
        if ($loop !== null) {
            $out[] = '### Loop Completion (the bottleneck monitor — Evidence Velocity rises only when these gaps close)';
            $out[] = '';
            $out[] = sprintf('- needs decision: **%d**', $loop['needs_decision']);
            $out[] = sprintf('- needs outcome: **%d** *(ACCEPTED decisions only)*', $loop['needs_outcome']);
            $out[] = sprintf('- needs assessment: **%d**', $loop['needs_assessment']);
            $out[] = sprintf('- complete: **%d**', $loop['complete']);
            $out[] = sprintf('- closed by IGNORED decision: **%d** *(lifecycle ends at the decision — no outcome expected)*', $loop['closed_ignored']);
            $out[] = sprintf('- deferred: **%d** *(awaiting re-decision)*', $loop['deferred']);
            $out[] = '';
        }
        $eff = $d['effectiveness'] ?? null;
        if ($eff !== null && $eff !== []) {
            $out[] = '## Recommendation Effectiveness (per rule)';
            $out[] = '';
            $out[] = '| Rule | Issued | Accepted | Ignored | Deferred | SUPPORTED | PARTIALLY_SUPPORTED | NOT_SUPPORTED | INCONCLUSIVE |';
            $out[] = '|---|---:|---:|---:|---:|---:|---:|---:|---:|';
            foreach ($eff as $rule => $r) {
                $out[] = sprintf(
                    '| %s | %d | %d | %d | %d | %d | %d | %d | %d |',
                    $rule,
                    $r['issued'] ?? 0,
                    $r['accepted'] ?? 0,
                    $r['ignored'] ?? 0,
                    $r['deferred'] ?? 0,
                    $r['SUPPORTED'] ?? 0,
                    $r['PARTIALLY_SUPPORTED'] ?? 0,
                    $r['NOT_SUPPORTED'] ?? 0,
                    $r['INCONCLUSIVE'] ?? 0
                );
            }
            $out[] = '';
        }
        $out[] = '## Outcome questions — the loop is measured HERE';
        $out[] = '';
        $out[] = '| Question | Evidence | Fills from |
|---|---|---|
| Are recommendations accepted? | **NO DATA YET** | AI workflow log |
| Which recommendations are ignored? | **NO DATA YET** | AI workflow log |
| Do warnings change code (behavioral milestone)? | **NO DATA YET** | usage log (spike plan) |
| Which collectors never influence decisions? | **NO DATA YET** | usage log (spike plan) |
| Do recommendations prevent defects? | **NO DATA YET** | outcome tracking (Run 2 design) |
| Is delivery becoming faster? | **NO DATA YET** | trend across commits |
| Does engineering confidence increase? | **NO DATA YET** | workflow log (confidence field, staged) |
| Which governance rules actually matter? | **NO DATA YET** | OE register recurrence |';
        $out[] = '';
        $out[] = '*Empty cells are deliberate: the questions stand visible until real work fills them. The dashboard reports and asks; it never judges.*';
        return implode("\n", $out) . "\n";
    }
}
