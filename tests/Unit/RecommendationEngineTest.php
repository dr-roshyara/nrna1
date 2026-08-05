<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first (RED before implementation) for Recommendation Engine v1.
 *
 * The engine is PURE: facts + rules (data) → recommendation drafts.
 * It never decides, never applies, never blocks. Contracts per the approved
 * plan (20260804-1200): Recommendation ≠ Decision ≠ Rationale.
 */
final class RecommendationEngineTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/RecommendationEngine.php';
    }

    private function rules(): array
    {
        return [
            ['id' => 'R1', 'source' => 'lcom4', 'op' => '>', 'threshold' => 20,
             'recommend' => 'LCOM4 of {value} exceeds 20 — review aggregate cohesion of {subject}.'],
            ['id' => 'R2', 'source' => 'test_presence', 'op' => 'flag', 'field' => 'production_without_tests',
             'recommend' => 'Production behavior changed without tests in range {subject} — recommend writing tests.'],
            ['id' => 'R5', 'source' => 'warn_hotspot', 'op' => 'flag',
             'recommend' => '{subject} is WARN-banded AND a change hotspot — recommend architecture attention.'],
        ];
    }

    public function test_threshold_rule_fires_and_fills_template(): void
    {
        $facts = ['lcom4' => [['subject' => 'App\Models\Election', 'value' => 29]]];

        $recs = \RecommendationEngine::evaluate($facts, $this->rules());

        $this->assertCount(1, $recs);
        $this->assertSame('R1', $recs[0]['rule']);
        $this->assertSame('App\Models\Election', $recs[0]['subject']);
        $this->assertStringContainsString('LCOM4 of 29', $recs[0]['text']);
        $this->assertSame('issued', $recs[0]['status']);
    }

    public function test_below_threshold_stays_silent(): void
    {
        $facts = ['lcom4' => [['subject' => 'App\Models\Tidy', 'value' => 3]]];

        $this->assertSame([], \RecommendationEngine::evaluate($facts, $this->rules()));
    }

    public function test_flag_rules_fire_on_true_only(): void
    {
        $facts = [
            'test_presence' => [['subject' => 'HEAD~1..HEAD', 'production_without_tests' => true]],
            'warn_hotspot'  => [['subject' => 'App\Http\Controllers\VoteController', 'flag' => true]],
        ];

        $recs = \RecommendationEngine::evaluate($facts, $this->rules());

        $this->assertSame(['R2', 'R5'], array_column($recs, 'rule'));
    }

    public function test_ids_are_stable_for_dedup(): void
    {
        $facts = ['lcom4' => [['subject' => 'App\Models\Election', 'value' => 29]]];

        $a = \RecommendationEngine::evaluate($facts, $this->rules());
        $b = \RecommendationEngine::evaluate($facts, $this->rules());

        // same (rule, subject) => same id — the dedup key
        $this->assertSame($a[0]['id'], $b[0]['id']);
        $this->assertStringStartsWith('REC-', $a[0]['id']);
    }

    public function test_recommendations_are_advisory_records_only(): void
    {
        $facts = ['lcom4' => [['subject' => 'X', 'value' => 99]]];

        $rec = \RecommendationEngine::evaluate($facts, $this->rules())[0];

        foreach (['decision', 'applied', 'blocked', 'auto_fix', 'outcome'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $rec, 'recommendation must not carry decision/outcome concerns');
        }
        $this->assertArrayHasKey('evidence_refs', $rec);
        $this->assertArrayHasKey('ts', $rec);
    }

    public function test_missing_fact_sources_noop_gracefully(): void
    {
        $this->assertSame([], \RecommendationEngine::evaluate([], $this->rules()));
    }
}
