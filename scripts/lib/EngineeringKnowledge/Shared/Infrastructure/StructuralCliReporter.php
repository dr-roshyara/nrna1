<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Infrastructure;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\AssuranceHandoffReport;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * Terminal rendering for the Track-2 structural reports (S7's CLI adapters).
 *
 * Presentation only — AP-8's vocabulary crosses unchanged, no verdict is invented
 * or softened (mirrors CAP-001's `CliOutputFormatter`, but SHARED so that D-4's
 * NOT-CHECKED statement is ONE text across every Phase-0 report, not a copy per
 * script: it is the mitigation for the plan's top risk — false assurance).
 *
 * ⛔ Shared MAY depend only on Shared types; it does — `Assessment` and `Verdict`.
 *    It knows no capability and adds no judgement.
 */
final readonly class StructuralCliReporter
{
    /**
     * D-4 — the DA's formulation, printed in every report, verbatim.
     *
     * ONE text, sourced from the Domain (Phase-1 D-1: report-content rules are
     * business rules): this Infrastructure constant is an alias, so the three
     * Phase-0 scripts and the Phase-1 handoff report can never drift apart.
     */
    public const NOT_CHECKED_STATEMENT = AssuranceHandoffReport::NOT_CHECKED_STATEMENT;

    /** One non-clean assessment: `[FAIL] S2 · path` then indented evidence. */
    public function sliceLine(string $slice, string $file, Assessment $assessment): string
    {
        return sprintf(
            "%s %s · %s\n       %s",
            $this->badge($assessment->verdict()),
            $slice,
            $file,
            $assessment->evidence(),
        );
    }

    public function notCheckedStatement(): string
    {
        return self::NOT_CHECKED_STATEMENT;
    }

    /**
     * The Phase-1 author-side handoff report — D-2's nine elements, D-3's
     * fail-closed aggregate, D-4's author-side recommendation. Presentation
     * only: every verdict and the D-4 statement are carried unchanged; nothing
     * is invented, softened, or accepted.
     */
    public function renderHandoffReport(AssuranceHandoffReport $report): string
    {
        $context = $report->context();
        $out = [];

        $out[] = 'Handoff assurance report — deterministic structural checks';
        $out[] = 'Artifact : ' . $context->target();
        $out[] = 'Checker  : ' . $context->checkerName() . ' · version ' . $context->checkerVersion();
        $out[] = 'Source   : ' . $context->sourceCommit();
        $out[] = 'Generated: ' . $context->generatedAt();
        $out[] = 'Command  : ' . $context->commandLine();
        $out[] = '';
        $out[] = 'Checks executed (per-slice verdicts):';
        foreach ($report->perSlice() as $slice => $assessment) {
            $out[] = '  ' . $this->badge($assessment->verdict()) . ' ' . $slice;
        }
        $out[] = '';
        $out[] = 'Findings — every non-PASS slice, with its evidence:';
        $findings = $report->findings();
        if ($findings === []) {
            $out[] = '  (none)';
        } else {
            foreach ($findings as $slice => $assessment) {
                $out[] = $this->sliceLine($slice, $context->target(), $assessment);
            }
        }
        $out[] = '';
        $out[] = $report->notCheckedStatement();
        if ($report->notCheckedAreas() !== []) {
            $out[] = 'NOT-CHECKED areas (named):';
            foreach ($report->notCheckedAreas() as $area) {
                $out[] = '  - ' . $area;
            }
        }
        if ($report->limitations() !== []) {
            $out[] = 'Known limitations:';
            foreach ($report->limitations() as $limitation) {
                $out[] = '  - ' . $limitation;
            }
        }
        $out[] = '';
        $out[] = 'Aggregate verdict: ' . $this->badge($report->aggregateVerdict());
        $out[] = 'Recommendation: ' . $report->recommendation();

        return implode("\n", $out) . "\n";
    }

    private function badge(Verdict $verdict): string
    {
        return match ($verdict) {
            Verdict::PASS => '[PASS]',
            Verdict::FAIL => '[FAIL]',
            Verdict::WARN => '[WARN]',
            Verdict::INCONCLUSIVE => '[INCONCLUSIVE]',
            default => '['.$verdict->value.']',
        };
    }
}
