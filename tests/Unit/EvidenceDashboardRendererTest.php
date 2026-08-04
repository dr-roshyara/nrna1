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
}
