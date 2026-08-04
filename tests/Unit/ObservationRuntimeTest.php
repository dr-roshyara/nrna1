<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the Observation Runtime: run(ChangeSet) → observations +
 * recommendations, reusing the EXISTING collectors and engine unchanged.
 * The runtime never scans the repository — changed files only — and is
 * deterministic: identical ChangeSet + identical sources → identical
 * recommendations (the FileSave ≡ Commit acceptance criterion).
 */
final class ObservationRuntimeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        require_once dirname(__DIR__, 2) . '/scripts/observations/ChangeSet.php';
        require_once dirname(__DIR__, 2) . '/scripts/observations/ObservationRuntime.php';
    }

    private const LOW_COHESION_SOURCE = <<<'PHP'
<?php
class Tangled {
    private $a; private $b; private $c;
    public function one() { return $this->a; }
    public function two() { return $this->b; }
    public function three() { return $this->c; }
}
PHP;

    private function rules(): array
    {
        // rules are DATA — the test uses its own thresholds; production
        // thresholds live in recommendation-rules.yaml and are not consulted here
        return [
            ['id' => 'R1', 'source' => 'lcom4', 'op' => '>', 'threshold' => 2,
             'recommend' => 'LCOM4 of {value}: {subject} — consider reviewing.'],
            ['id' => 'R2', 'source' => 'test_presence', 'op' => 'flag', 'field' => 'production_without_tests',
             'recommend' => 'Production changed without tests in {subject}.'],
        ];
    }

    private function changeSet(): \ChangeSet
    {
        return new \ChangeSet(
            changedFiles: ['app/Models/Tangled.php'],
            source: 'file-save',
            timestamp: '2026-08-04T21:00:00+02:00',
            commitId: null
        );
    }

    public function test_analyzes_only_changed_files_via_injected_reader(): void
    {
        $requested = [];
        $reader = function (string $path) use (&$requested): ?string {
            $requested[] = $path;
            return self::LOW_COHESION_SOURCE;
        };

        \ObservationRuntime::run($this->changeSet(), $this->rules(), $reader);

        $this->assertSame(['app/Models/Tangled.php'], $requested, 'runtime must read changed files only');
    }

    public function test_low_cohesion_change_produces_lcom4_recommendation(): void
    {
        $result = \ObservationRuntime::run($this->changeSet(), $this->rules(), fn () => self::LOW_COHESION_SOURCE);

        $recs = array_values(array_filter($result['recommendations'], fn ($r) => $r['rule'] === 'R1'));
        $this->assertCount(1, $recs);
        $this->assertSame('Tangled', $recs[0]['subject']);
    }

    public function test_production_change_without_tests_is_flagged(): void
    {
        $result = \ObservationRuntime::run($this->changeSet(), $this->rules(), fn () => self::LOW_COHESION_SOURCE);

        $r2 = array_values(array_filter($result['recommendations'], fn ($r) => $r['rule'] === 'R2'));
        $this->assertCount(1, $r2);
    }

    public function test_reports_per_collector_timings(): void
    {
        // operational gold (review 2026-08-04): "which collector became slow?"
        // must be answerable from the record, not guessed
        $result = \ObservationRuntime::run($this->changeSet(), $this->rules(), fn () => self::LOW_COHESION_SOURCE);

        $this->assertArrayHasKey('timings_ms', $result);
        $this->assertArrayHasKey('lcom4', $result['timings_ms']);
        $this->assertArrayHasKey('test_presence', $result['timings_ms']);
        foreach ($result['timings_ms'] as $ms) {
            $this->assertIsInt($ms);
            $this->assertGreaterThanOrEqual(0, $ms);
        }
    }

    public function test_identical_changesets_produce_identical_recommendations(): void
    {
        $a = \ObservationRuntime::run($this->changeSet(), $this->rules(), fn () => self::LOW_COHESION_SOURCE);
        $b = \ObservationRuntime::run($this->changeSet(), $this->rules(), fn () => self::LOW_COHESION_SOURCE);

        $strip = fn (array $recs) => array_map(fn ($r) => array_diff_key($r, ['ts' => 0]), $recs);
        $this->assertSame($strip($a['recommendations']), $strip($b['recommendations']),
            'trigger-independence: same code, same recommendations — regardless of how the run was triggered');
    }
}
