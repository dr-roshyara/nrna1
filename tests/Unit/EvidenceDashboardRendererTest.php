<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the evidence dashboard renderer: a PROJECTION generator that
 * turns observation-stream data into one regenerable markdown page answering
 * "what does the evidence show so far?" — including HONEST EMPTY CELLS for
 * outcome questions that have no data yet.
 */
final class EvidenceDashboardRendererTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/EvidenceDashboardRenderer.php';
    }

    private function sample(): array
    {
        return [
            'metrics' => ['snapshots' => 3, 'mean_cbo' => 3.98, 'warn' => 17, 'hotspots' => 19, 'commit' => 'c3409d69f'],
            'test_presence' => ['observations' => 1, 'production_without_tests' => 0],
            'lcom4' => ['runs' => 2, 'worst' => ['Election' => 29, 'Committee' => 16]],
            'oe_entries' => 3,
        ];
    }

    public function test_renders_observation_stream_sections(): void
    {
        $md = \EvidenceDashboardRenderer::render($this->sample());

        $this->assertStringContainsString('mean CBO: 3.98', $md);
        $this->assertStringContainsString('hotspots: 19', $md);
        $this->assertStringContainsString('Election', $md);
        $this->assertStringContainsString('LCOM4=29', $md);
        $this->assertStringContainsString('OE entries: 3', $md);
    }

    public function test_outcome_questions_render_as_honest_empty_cells(): void
    {
        $md = \EvidenceDashboardRenderer::render($this->sample());

        // The eight outcome questions must appear even with zero data,
        // each naming the source that will eventually fill it.
        $this->assertStringContainsString('recommendations accepted', strtolower($md));
        $this->assertStringContainsString('NO DATA YET', $md);
        $this->assertStringContainsString('workflow log', strtolower($md));
        $this->assertStringContainsString('usage log', strtolower($md));
    }

    public function test_dashboard_is_a_projection_and_verdict_free(): void
    {
        $md = \EvidenceDashboardRenderer::render($this->sample());

        $this->assertStringContainsString('PROJECTION', $md);
        $this->assertStringContainsString('regenerate', strtolower($md));
        // it reports and asks; it never judges
        foreach (['PASSED', 'FAILED THE GATE', 'VIOLATION'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $md);
        }
    }

    public function test_handles_missing_streams_gracefully(): void
    {
        $md = \EvidenceDashboardRenderer::render(['oe_entries' => 0]);

        $this->assertStringContainsString('no snapshots yet', strtolower($md));
        $this->assertStringContainsString('OE entries: 0', $md);
    }

    public function test_lifecycle_funnel_shows_where_the_pipeline_breaks(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'funnel' => ['issued' => 100, 'decided' => 80, 'outcomes' => 5, 'assessments' => 5],
        ]);

        // the funnel is the KPI: each stage count visible, drop-offs computable at a glance
        $this->assertStringContainsString('issued 100', $md);
        $this->assertStringContainsString('decided 80', $md);
        $this->assertStringContainsString('outcomes 5', $md);
        $this->assertStringContainsString('assessments 5', $md);
    }

    public function test_stage_lead_times_render_as_measurements_only(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'lead_times' => [
                'recommendation_to_decision' => ['mean_hours' => 6.5, 'n' => 1],
                'decision_to_outcome'        => ['mean_hours' => 2.0, 'n' => 1],
                'outcome_to_assessment'      => ['mean_hours' => 0.1, 'n' => 1],
            ],
        ]);

        $this->assertStringContainsString('Lead times', $md);
        $this->assertStringContainsString('6.5h', $md);
        $this->assertStringContainsString('n=1', $md); // sample size always shown — measurements, never conclusions
    }

    public function test_evidence_velocity_renders_rate_when_window_is_a_week_or_more(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'evidence_velocity' => ['completed_cycles' => 12, 'window_days' => 21.0, 'per_week' => 4.0],
        ]);

        $this->assertStringContainsString('Evidence Velocity', $md);
        $this->assertStringContainsString('completed cycles: 12', $md);
        $this->assertStringContainsString('4.0 cycles/week', $md);
    }

    public function test_evidence_velocity_withholds_rate_for_short_windows(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'evidence_velocity' => ['completed_cycles' => 1, 'window_days' => 0.6, 'per_week' => null],
        ]);

        $this->assertStringContainsString('completed cycles: 1', $md);
        // a sub-week window must never be extrapolated into a weekly rate
        $this->assertStringContainsString('rate withheld', strtolower($md));
        $this->assertStringNotContainsString('cycles/week**', $md);
    }

    public function test_loop_completion_section_shows_stage_gaps(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'loop_completion' => [
                'needs_decision'   => 7,
                'needs_outcome'    => 3,
                'needs_assessment' => 2,
                'complete'         => 4,
                'closed_ignored'   => 1,
                'deferred'         => 2,
            ],
        ]);

        $this->assertStringContainsString('Loop Completion', $md);
        $this->assertStringContainsString('needs decision: **7**', $md);
        $this->assertStringContainsString('needs outcome: **3**', $md);
        $this->assertStringContainsString('needs assessment: **2**', $md);
        $this->assertStringContainsString('complete: **4**', $md);
    }

    public function test_loop_completion_never_counts_ignored_as_missing_outcome(): void
    {
        // an IGNORED recommendation's lifecycle ends at its decision — the
        // projection must show it as closed, not as an outcome gap
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'loop_completion' => [
                'needs_decision'   => 0,
                'needs_outcome'    => 0,
                'needs_assessment' => 0,
                'complete'         => 0,
                'closed_ignored'   => 5,
                'deferred'         => 0,
            ],
        ]);

        $this->assertStringContainsString('closed by IGNORED decision: **5**', $md);
        $this->assertStringContainsString('no outcome expected', strtolower($md));
    }

    public function test_per_rule_effectiveness_table_renders(): void
    {
        $md = \EvidenceDashboardRenderer::render([
            'oe_entries' => 0,
            'effectiveness' => [
                'R1' => ['issued' => 1, 'accepted' => 1, 'ignored' => 0, 'deferred' => 0,
                         'SUPPORTED' => 0, 'PARTIALLY_SUPPORTED' => 0, 'NOT_SUPPORTED' => 0, 'INCONCLUSIVE' => 1],
            ],
        ]);

        $this->assertStringContainsString('| R1 |', $md);
        $this->assertStringContainsString('Effectiveness', $md);
        // verdict columns present
        $this->assertStringContainsString('INCONCLUSIVE', $md);
    }
}
