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
