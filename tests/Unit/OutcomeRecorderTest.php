<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first (RED before implementation) for Outcome Recording (Phase 4).
 *
 * An outcome record is RAW EVIDENCE — baseline, current, delta, commits
 * since the decision. It carries NO judgment: "did the outcome support the
 * recommendation?" belongs to the ASSESSMENT stage (deterministic later,
 * statistical eventually), never to the recorder (review 2026-08-04).
 */
final class OutcomeRecorderTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/OutcomeRecorder.php';
    }

    public function test_records_raw_delta_between_baseline_and_current(): void
    {
        $outcome = \OutcomeRecorder::record(
            recommendationId: 'REC-abc',
            decisionRef: 'ACCEPTED@2026-08-04T15:11:18+00:00',
            metric: 'LCOM4',
            subject: 'Election',
            baseline: 29,
            current: 11,
            commitsSince: 5
        );

        $this->assertSame('REC-abc', $outcome['recommendation_id']);
        $this->assertSame('ACCEPTED@2026-08-04T15:11:18+00:00', $outcome['decision_ref']);
        $this->assertSame(29, $outcome['baseline']);
        $this->assertSame(11, $outcome['current']);
        $this->assertSame(-18, $outcome['delta']);
        $this->assertSame(5, $outcome['commits_since_decision']);
        $this->assertArrayHasKey('ts', $outcome);
    }

    public function test_outcome_is_assessment_free(): void
    {
        $outcome = \OutcomeRecorder::record('REC-x', 'd', 'LCOM4', 'S', 10, 3, 2);

        // Raw evidence only — assessment is a SEPARATE stage.
        foreach (['successful', 'status', 'improved', 'verdict', 'supported'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $outcome, 'the recorder must never judge');
        }
    }

    public function test_refuses_zero_commits_since_decision(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Recording an outcome before the subject could have changed is
        // premature evidence — the recorder refuses rather than logging noise.
        \OutcomeRecorder::record('REC-x', 'd', 'LCOM4', 'S', 10, 10, 0);
    }
}
