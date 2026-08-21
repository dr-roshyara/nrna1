<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Tests\Application;

use EngineeringKnowledge\Shared\Application\GenerateHandoffAssuranceReport;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\AssuranceHandoffReport;
use EngineeringKnowledge\Shared\Domain\HandoffContext;
use EngineeringKnowledge\Shared\Domain\Verdict;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * P2 — the handoff-report application service (Track 2 · Phase 1).
 *
 * The service composes the per-slice Assessments into the report and supplies
 * the structural profile's named NOT-CHECKED areas + known limitations. It has
 * no policy of its own: the aggregate and its fail-closed precedence live in
 * the Domain VO (D-3), so this test asserts composition, not re-derived rules.
 */
final class GenerateHandoffAssuranceReportTest extends TestCase
{
    private HandoffContext $context;

    protected function setUp(): void
    {
        $this->context = HandoffContext::of(
            'docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md',
            'knowledge-lint',
            '1.0.0',
            '8307beca',
            '2026-08-21T16:00:00+00:00',
            'php scripts/knowledge-lint.php --report=handoff --document=…',
        );
    }

    private function assessment(Verdict $verdict): Assessment
    {
        return Assessment::of($verdict, 'evidence for '.$verdict->value, 'S');
    }

    public function test_it_assembles_the_report_from_slice_assessments(): void
    {
        $report = (new GenerateHandoffAssuranceReport())->handle(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS), 'S2' => $this->assessment(Verdict::PASS)],
        );

        self::assertSame($this->context, $report->context());
        self::assertSame(['S1', 'S2'], array_keys($report->perSlice()));
        self::assertSame(Verdict::PASS, $report->aggregateVerdict());
        self::assertSame(AssuranceHandoffReport::NOT_CHECKED_STATEMENT, $report->notCheckedStatement());
    }

    public function test_the_named_not_checked_areas_are_supplied_by_default(): void
    {
        $report = (new GenerateHandoffAssuranceReport())->handle(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS)],
        );

        foreach (GenerateHandoffAssuranceReport::DEFAULT_NOT_CHECKED_AREAS as $area) {
            self::assertContains($area, $report->notCheckedAreas());
        }
    }

    public function test_caller_supplied_areas_win_over_the_defaults(): void
    {
        $report = (new GenerateHandoffAssuranceReport())->handle(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS)],
            ['A hand-authored NOT-CHECKED area.'],
        );

        self::assertSame(['A hand-authored NOT-CHECKED area.'], $report->notCheckedAreas());
    }

    public function test_known_limitations_are_supplied(): void
    {
        $report = (new GenerateHandoffAssuranceReport())->handle(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS)],
        );

        self::assertSame(GenerateHandoffAssuranceReport::DEFAULT_LIMITATIONS, $report->limitations());
    }

    public function test_it_preserves_the_fail_closed_aggregate(): void
    {
        $report = (new GenerateHandoffAssuranceReport())->handle(
            $this->context,
            ['S3' => $this->assessment(Verdict::INCONCLUSIVE)],
        );

        self::assertSame(Verdict::INCONCLUSIVE, $report->aggregateVerdict());
        self::assertNotSame(Verdict::PASS, $report->aggregateVerdict());
    }

    public function test_it_rejects_an_empty_check_set_rather_than_reporting_pass(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new GenerateHandoffAssuranceReport())->handle($this->context, []);
    }
}
