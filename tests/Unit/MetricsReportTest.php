<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Characterization tests for the frozen metrics runner (spike plan
 * 20260803-2130). They pin the tool's OBSERVED behaviour so future
 * bug fixes cannot silently change it. No Laravel boot required.
 */
final class MetricsReportTest extends TestCase
{
    private string $tmpTrendDir;

    protected function setUp(): void
    {
        $this->tmpTrendDir = sys_get_temp_dir() . '/metrics-test-' . uniqid();
        mkdir($this->tmpTrendDir, 0777, true);
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->tmpTrendDir . '/*') ?: []);
        @rmdir($this->tmpTrendDir);
    }

    private function runTool(): string
    {
        $root = dirname(__DIR__, 2);
        $fixture = __DIR__ . '/fixtures/pdepend-summary-fixture.xml';
        putenv('METRICS_TREND_DIR=' . $this->tmpTrendDir); // inherited by the child process (Windows-safe)
        $cmd = sprintf(
            'cd %s && php scripts/metrics/metrics-report.php %s 3 2>&1',
            escapeshellarg($root),
            escapeshellarg($fixture)
        );
        $out = (string) shell_exec($cmd);
        putenv('METRICS_TREND_DIR');
        return $out;
    }

    public function test_report_bands_and_stereotypes(): void
    {
        $out = $this->runTool();

        // stereotype grouping happened
        $this->assertStringContainsString('[domain]', $out);
        $this->assertStringContainsString('[controller]', $out);
        $this->assertStringContainsString('[provider]', $out);

        // per-stereotype banding: CBO=30 is WARN for a domain class...
        $this->assertMatchesRegularExpression('/WARN.+HeavyAggregate/', $out);
        // ...but CBO=30 is OK for a provider (bands ok<=40)
        $this->assertMatchesRegularExpression('/OK.+WiringServiceProvider/', $out);

        // advisory contract: the tool must say so and never claim enforcement
        $this->assertStringContainsString('advisory', $out);
        $this->assertStringContainsString('never fails the build', $out);
    }

    public function test_trend_snapshot_appended_with_v3_schema(): void
    {
        $this->runTool();

        $trendFile = $this->tmpTrendDir . '/trend.jsonl';
        $this->assertFileExists($trendFile);

        $lines = array_filter(explode("\n", trim((string) file_get_contents($trendFile))));
        $this->assertCount(1, $lines);

        $snap = json_decode($lines[0], true);
        $this->assertSame(3, $snap['v']);
        foreach (['ts', 'commit', 'classes', 'mean_cbo', 'bands', 'st_mean', 'hotspots', 'watch'] as $key) {
            $this->assertArrayHasKey($key, $snap);
        }
        // watchlist floor: HeavyAggregate (CBO 30) is in, TinyValueObject (CBO 2) is out
        $watchNames = implode(' ', array_keys($snap['watch']));
        $this->assertStringContainsString('HeavyAggregate', $watchNames);
        $this->assertStringNotContainsString('TinyValueObject', $watchNames);
    }

    public function test_second_run_reports_deltas_against_previous_snapshot(): void
    {
        $this->runTool();
        $out = $this->runTool();

        $this->assertStringContainsString('changes since', $out);
        $this->assertStringContainsString('none — watchlist unchanged', $out);

        $lines = array_filter(explode("\n", trim((string) file_get_contents($this->tmpTrendDir . '/trend.jsonl'))));
        $this->assertCount(2, $lines, 'trend file is append-only: two runs, two lines');
    }

    public function test_exit_code_2_on_missing_input(): void
    {
        $root = dirname(__DIR__, 2);
        exec(sprintf('cd %s && php scripts/metrics/metrics-report.php missing.xml 2>nul', escapeshellarg($root)), $o, $code);
        $this->assertSame(2, $code);
    }
}
