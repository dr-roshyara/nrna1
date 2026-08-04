<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Collector Verification Suite — conformance against the language-neutral
 * golden fixtures in scripts/observations/examples/lcom4/.
 *
 * Purpose (review 2026-08-04): "validate the metric before validating the
 * code." Any future implementation (Python/Java/Rust) must pass these same
 * fixtures with identical values — this suite IS the conformance contract.
 */
final class Lcom4VerificationSuiteTest extends TestCase
{
    private const EXAMPLES = __DIR__ . '/../../scripts/observations/examples/lcom4';

    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/Lcom4Collector.php';
    }

    public function test_collector_conforms_to_every_golden_fixture(): void
    {
        $expected = json_decode((string) file_get_contents(self::EXAMPLES . '/expected.json'), true);
        $this->assertIsArray($expected);

        $fixtures = array_filter(array_keys($expected), fn (string $k) => !str_starts_with($k, '_'));
        $this->assertNotEmpty($fixtures, 'suite must contain fixtures');

        foreach ($fixtures as $file) {
            $path = self::EXAMPLES . '/' . $file;
            $this->assertFileExists($path, "fixture listed in expected.json is missing: {$file}");

            $observations = \Lcom4Collector::collect((string) file_get_contents($path));
            $actual = array_map(
                fn (array $o) => ['class' => $o['class'], 'value' => $o['value']],
                $observations
            );

            $this->assertSame(
                $expected[$file],
                $actual,
                "conformance failure on {$file} — the collector diverges from the golden expectation"
            );
        }
    }

    public function test_every_fixture_on_disk_is_covered_by_an_expectation(): void
    {
        $expected = json_decode((string) file_get_contents(self::EXAMPLES . '/expected.json'), true);
        $onDisk = array_map('basename', glob(self::EXAMPLES . '/*.php') ?: []);

        foreach ($onDisk as $file) {
            $this->assertArrayHasKey($file, $expected, "fixture {$file} has no golden expectation — suites must never carry unasserted examples");
        }
    }
}
