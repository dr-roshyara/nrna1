<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Domain;

use InvalidArgumentException;

/**
 * The author-side handoff-assurance report (Track 2 · Phase 1).
 *
 * A cross-capability CONSUMER, not a capability: it composes the Assessment each
 * structural slice emits into the one artifact an author attaches to an
 * Architecture / Governance handoff. It carries the nine required elements
 * (D-2) and derives the aggregate verdict FAIL-CLOSED (D-3) —
 * FAIL > INCONCLUSIVE > WARN > PASS — because *absence of evidence is not PASS*
 * (CAP-001 / Phase-0 D-2).
 *
 * ⛔ The report confers no authority. Its recommendation is author-side practice
 *    (FIX BEFORE HANDOFF / REVIEW / RESOLVE / ATTACH AS EVIDENCE); it never says
 *    APPROVED, REJECTED or ACCEPTED (D-4) and it never converts a finding into
 *    PASS. Automation produces assurance evidence — never a decision.
 */
final readonly class AssuranceHandoffReport
{
    /** D-4 — the DA's NOT-CHECKED statement, ONE text for every report (never trimmed). */
    public const NOT_CHECKED_STATEMENT =
        '⛔ NOT CHECKED (stated positively): mechanical assurance proves DECLARED '
        .'STRUCTURE — identifier uniqueness, intra-document reference resolution, '
        .'declared vocabulary, table shape, disposition labelling. It does NOT check '
        .'soundness, completeness, authority, or provenance — architecture review '
        .'discovers UNDECLARED ARCHITECTURAL CONTENT. A PASS here is mechanical, '
        .'not architectural, assurance.';

    /** Author-side recommendation vocabulary (D-4) — never governance vocabulary. */
    public const RECOMMEND_FIX_BEFORE_HANDOFF = 'FIX BEFORE HANDOFF';
    public const RECOMMEND_REVIEW_INCONCLUSIVE = 'REVIEW BEFORE HANDOFF';
    public const RECOMMEND_RESOLVE_WARNINGS = 'RESOLVE WARNINGS BEFORE HANDOFF';
    public const RECOMMEND_ATTACH_AS_EVIDENCE = 'ATTACH AS EVIDENCE';

    private function __construct(
        private HandoffContext $context,
        /** @var array<string, Assessment> */
        private array $perSlice,
        private string $notCheckedStatement,
        /** @var list<string> */
        private array $notCheckedAreas,
        /** @var list<string> */
        private array $limitations,
    ) {
    }

    /**
     * @param array<string, Assessment> $perSlice
     * @param list<string>              $notCheckedAreas
     * @param list<string>              $limitations
     */
    public static function of(
        HandoffContext $context,
        array $perSlice,
        string $notCheckedStatement,
        array $notCheckedAreas = [],
        array $limitations = [],
    ): self {
        if ($perSlice === []) {
            throw new InvalidArgumentException('A handoff report must record at least one check.');
        }

        foreach ($perSlice as $slice => $assessment) {
            if (! $assessment instanceof Assessment) {
                throw new InvalidArgumentException("Slice '{$slice}' is not an Assessment.");
            }
        }

        if (trim($notCheckedStatement) === '') {
            throw new InvalidArgumentException('The NOT-CHECKED statement must never be empty (D-4).');
        }

        return new self($context, $perSlice, $notCheckedStatement, $notCheckedAreas, $limitations);
    }

    public function context(): HandoffContext
    {
        return $this->context;
    }

    /** @return array<string, Assessment> keyed by slice (S1..S5). */
    public function perSlice(): array
    {
        return $this->perSlice;
    }

    /** The D-4 NOT-CHECKED statement, verbatim. */
    public function notCheckedStatement(): string
    {
        return $this->notCheckedStatement;
    }

    /** @return list<string> named areas the mechanical checks do NOT cover. */
    public function notCheckedAreas(): array
    {
        return $this->notCheckedAreas;
    }

    /** @return list<string> known limitations of the run. */
    public function limitations(): array
    {
        return $this->limitations;
    }

    /**
     * Fail-closed aggregate (D-3): FAIL > INCONCLUSIVE > WARN > PASS.
     * Absence of evidence is not PASS — one INCONCLUSIVE slice keeps the
     * aggregate from PASS, so a report never reads as "clean" on silence.
     *
     * ⛔ The precedence is POSITION-INDEPENDENT: iteration order never decides the
     *    verdict. The scan collects the highest-precedence verdict over the whole
     *    check set, so an INCONCLUSIVE slice that merely precedes a FAIL can never
     *    mask it (fail-closed — a defect stays visible regardless of slice order).
     */
    public function aggregateVerdict(): Verdict
    {
        $highest = Verdict::PASS;

        foreach ($this->perSlice as $assessment) {
            $verdict = $assessment->verdict();

            if ($verdict === Verdict::FAIL) {
                return Verdict::FAIL;
            }

            if ($verdict === Verdict::INCONCLUSIVE) {
                $highest = Verdict::INCONCLUSIVE;
            } elseif ($verdict === Verdict::WARN && $highest === Verdict::PASS) {
                $highest = Verdict::WARN;
            }
        }

        return $highest;
    }

    /** Non-PASS slices: every assessment an author must look at before handoff. */
    public function findings(): array
    {
        return array_filter(
            $this->perSlice,
            static fn (Assessment $assessment): bool => ! $assessment->isClean(),
        );
    }

    /** Author-side recommendation (D-4) — warn-only, never an autonomous accept/reject. */
    public function recommendation(): string
    {
        return match ($this->aggregateVerdict()) {
            Verdict::FAIL => self::RECOMMEND_FIX_BEFORE_HANDOFF,
            Verdict::INCONCLUSIVE => self::RECOMMEND_REVIEW_INCONCLUSIVE,
            Verdict::WARN => self::RECOMMEND_RESOLVE_WARNINGS,
            Verdict::PASS => self::RECOMMEND_ATTACH_AS_EVIDENCE,
        };
    }
}
