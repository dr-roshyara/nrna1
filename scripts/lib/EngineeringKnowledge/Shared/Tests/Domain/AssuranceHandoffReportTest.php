<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Tests\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\AssuranceHandoffReport;
use EngineeringKnowledge\Shared\Domain\HandoffContext;
use EngineeringKnowledge\Shared\Domain\Verdict;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * P1 — the handoff-assurance report aggregate (Track 2 · Phase 1).
 *
 * Domain rules under test: the fail-closed aggregate precedence (D-3,
 * FAIL > INCONCLUSIVE > WARN > PASS — an INCONCLUSIVE slice must keep the
 * aggregate from PASS), the findings filter, the author-side recommendation
 * vocabulary (D-4, never governance vocabulary), and the invariants — a report
 * never ships without at least one recorded check and never without the D-4
 * NOT-CHECKED statement.
 */
final class AssuranceHandoffReportTest extends TestCase
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

    public function test_aggregate_is_pass_only_when_every_slice_is_clean(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS), 'S2' => $this->assessment(Verdict::PASS)],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        self::assertSame(Verdict::PASS, $report->aggregateVerdict());
        self::assertSame(AssuranceHandoffReport::RECOMMEND_ATTACH_AS_EVIDENCE, $report->recommendation());
        self::assertSame([], $report->findings());
    }

    public function test_any_fail_dominates_the_aggregate(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            [
                'S1' => $this->assessment(Verdict::PASS),
                'S2' => $this->assessment(Verdict::FAIL),
                'S3' => $this->assessment(Verdict::INCONCLUSIVE),
                'S5' => $this->assessment(Verdict::WARN),
            ],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        self::assertSame(Verdict::FAIL, $report->aggregateVerdict());
        self::assertSame(AssuranceHandoffReport::RECOMMEND_FIX_BEFORE_HANDOFF, $report->recommendation());
    }

    public function test_inconclusive_beats_warn_and_never_pass(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            ['S1' => $this->assessment(Verdict::WARN), 'S3' => $this->assessment(Verdict::INCONCLUSIVE)],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        self::assertSame(Verdict::INCONCLUSIVE, $report->aggregateVerdict());
        self::assertSame(AssuranceHandoffReport::RECOMMEND_REVIEW_INCONCLUSIVE, $report->recommendation());
        self::assertNotSame(Verdict::PASS, $report->aggregateVerdict());
    }

    public function test_warn_survives_without_fail_or_inconclusive(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS), 'S5' => $this->assessment(Verdict::WARN)],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        self::assertSame(Verdict::WARN, $report->aggregateVerdict());
        self::assertSame(AssuranceHandoffReport::RECOMMEND_RESOLVE_WARNINGS, $report->recommendation());
    }

    public function test_findings_are_every_non_pass_slice_keyed_by_slice(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            [
                'S1' => $this->assessment(Verdict::PASS),
                'S2' => $this->assessment(Verdict::FAIL),
                'S5' => $this->assessment(Verdict::WARN),
            ],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        self::assertSame(['S2', 'S5'], array_keys($report->findings()));
    }

    public function test_the_report_carries_the_nine_required_elements(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS), 'S2' => $this->assessment(Verdict::PASS)],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
            ['S8 · OQ-1 — the undeclared-act class (human architecture review only)'],
            ['Mechanical assurance proves declared structure only.'],
        );

        // context (3 provenance elements) + per-slice checks + D-4 statement + named areas + limitations
        self::assertSame('knowledge-lint', $report->context()->checkerName());
        self::assertSame('1.0.0', $report->context()->checkerVersion());
        self::assertSame('8307beca', $report->context()->sourceCommit());
        self::assertArrayHasKey('S1', $report->perSlice());
        self::assertArrayHasKey('S2', $report->perSlice());
        self::assertStringContainsString('NOT CHECKED', $report->notCheckedStatement());
        self::assertCount(1, $report->notCheckedAreas());
        self::assertCount(1, $report->limitations());
    }

    public function test_it_rejects_an_empty_check_set(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AssuranceHandoffReport::of($this->context, [], AssuranceHandoffReport::NOT_CHECKED_STATEMENT);
    }

    public function test_it_rejects_a_blank_not_checked_statement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AssuranceHandoffReport::of(
            $this->context,
            ['S1' => $this->assessment(Verdict::PASS)],
            '   ',
        );
    }

    public function test_it_never_emits_governance_vocabulary(): void
    {
        $report = AssuranceHandoffReport::of(
            $this->context,
            ['S2' => $this->assessment(Verdict::FAIL)],
            AssuranceHandoffReport::NOT_CHECKED_STATEMENT,
        );

        $text = $report->recommendation().' '.$report->notCheckedStatement();

        foreach (['APPROVED', 'REJECTED', 'ACCEPTED'] as $word) {
            self::assertStringNotContainsString($word, $text);
        }
    }
}
