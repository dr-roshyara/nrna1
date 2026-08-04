<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the deterministic Assessment stage: did the OUTCOME support
 * the RECOMMENDATION? Assessment is interpretation — the stage Outcome
 * deliberately refused to be. Verdicts form a CLOSED set, scoped to
 * recommendation-effectiveness (distinct from the parked Engineering
 * Assessment Commission's code-quality ground, and from ES-003/CAP-001
 * verdict sets — a scoped vocabulary, not a third collision).
 *
 * INCONCLUSIVE exists because the first REAL outcome record (Election,
 * Δ+0 after 1 commit) fits neither supported nor unsupported — the
 * evidence demanded the fourth verdict before any code was written.
 */
final class AssessmentServiceTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/AssessmentService.php';
    }

    private function outcome(int $baseline, int $current, int $commits): array
    {
        return [
            'recommendation_id' => 'REC-x', 'decision_ref' => 'ACCEPTED@t',
            'metric' => 'LCOM4', 'subject' => 'S',
            'baseline' => $baseline, 'current' => $current,
            'delta' => $current - $baseline, 'commits_since_decision' => $commits,
        ];
    }

    public function test_strong_improvement_is_supported(): void
    {
        $a = \AssessmentService::evaluate($this->outcome(29, 11, 5)); // -62%

        $this->assertSame('SUPPORTED', $a['verdict']);
        $this->assertSame('REC-x', $a['recommendation_id']);
        $this->assertArrayHasKey('improvement_ratio', $a);
    }

    public function test_small_improvement_is_partially_supported(): void
    {
        $a = \AssessmentService::evaluate($this->outcome(29, 27, 5)); // -7%

        $this->assertSame('PARTIALLY_SUPPORTED', $a['verdict']);
    }

    public function test_worsening_is_not_supported(): void
    {
        $a = \AssessmentService::evaluate($this->outcome(29, 35, 5));

        $this->assertSame('NOT_SUPPORTED', $a['verdict']);
    }

    public function test_no_change_is_inconclusive(): void
    {
        // The FIRST REAL RECORD's shape: Δ+0 — nothing to judge yet.
        $a = \AssessmentService::evaluate($this->outcome(29, 29, 1));

        $this->assertSame('INCONCLUSIVE', $a['verdict']);
    }

    public function test_too_few_commits_is_inconclusive_even_with_change(): void
    {
        $a = \AssessmentService::evaluate($this->outcome(29, 11, 1)); // improved, but 1 commit

        $this->assertSame('INCONCLUSIVE', $a['verdict']);
        $this->assertStringContainsString('commits', $a['basis']);
    }

    public function test_verdict_set_is_closed(): void
    {
        foreach ([[29, 11, 9], [29, 27, 9], [29, 35, 9], [29, 29, 9], [10, 5, 1]] as [$b, $c, $n]) {
            $a = \AssessmentService::evaluate($this->outcome($b, $c, $n));
            $this->assertContains(
                $a['verdict'],
                ['SUPPORTED', 'PARTIALLY_SUPPORTED', 'NOT_SUPPORTED', 'INCONCLUSIVE'],
                'assessment verdicts form a CLOSED set'
            );
        }
    }
}
