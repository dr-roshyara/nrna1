<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Infrastructure;

use EngineeringKnowledge\Shared\Domain\Assessment;
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
    /** D-4 — the DA's formulation, printed in every Phase-0 report, verbatim. */
    public const NOT_CHECKED_STATEMENT =
        '⛔ NOT CHECKED (stated positively): mechanical assurance proves DECLARED '
        .'STRUCTURE — identifier uniqueness, intra-document reference resolution, '
        .'declared vocabulary, table shape, disposition labelling. It does NOT check '
        .'soundness, completeness, authority, or provenance — architecture review '
        .'discovers UNDECLARED ARCHITECTURAL CONTENT. A PASS here is mechanical, '
        .'not architectural, assurance.';

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
