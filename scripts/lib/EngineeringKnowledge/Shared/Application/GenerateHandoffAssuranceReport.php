<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Application;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\AssuranceHandoffReport;
use EngineeringKnowledge\Shared\Domain\HandoffContext;

/**
 * Application service — assemble an author-side handoff-assurance report.
 *
 * A cross-capability CONSUMER (Phase-1 D-1): it composes the per-slice
 * Assessment the structural checks emitted into the one report an author
 * attaches to a handoff, and it supplies the structural profile's named
 * NOT-CHECKED areas and known limitations. It contains no policy of its own —
 * the aggregate rules live in `AssuranceHandoffReport` (D-3) and the verdicts
 * come from the capabilities.
 *
 * ⛔ It never manufactures a verdict: the report's aggregate is derived from
 *    the given assessments, and an empty check set is REJECTED, never reported
 *    as PASS. Automation produces evidence; it does not produce acceptance.
 */
final readonly class GenerateHandoffAssuranceReport
{
    /** Named NOT-CHECKED areas for the structural profile (back-test §5). */
    public const DEFAULT_NOT_CHECKED_AREAS = [
        'The undeclared-act class — an act never declared in the plan (RD-1, RD-7, RD-3·b) is discoverable only by human architecture review; DI-3/DI-6 has no catalogued capability (OQ-1, S8).',
        'RC mechanical defect classes not yet enumerated as deterministic subclasses (back-test surface 3).',
        'Grant/aggregate identity and amendment lineage across the migration (back-test surface 4).',
    ];

    /** Known limitations of a structural-profile handoff run. */
    public const DEFAULT_LIMITATIONS = [
        'Mechanical assurance proves declared structure only — it cannot discover undeclared architectural content (D-4).',
        'S3 evaluates declared vocabulary only when a vocabulary configuration is supplied (--vocabulary); without one it reports INCONCLUSIVE, never PASS.',
    ];

    /**
     * @param array<string, Assessment> $perSlice        keyed by slice (S1..S5)
     * @param list<string>|null         $notCheckedAreas null = the structural profile's defaults
     */
    public function handle(
        HandoffContext $context,
        array $perSlice,
        ?array $notCheckedAreas = null,
    ): AssuranceHandoffReport {
        return AssuranceHandoffReport::of(
            $context,
            $perSlice,
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
            $notCheckedAreas ?? self::DEFAULT_NOT_CHECKED_AREAS,
            self::DEFAULT_LIMITATIONS,
        );
    }
}
