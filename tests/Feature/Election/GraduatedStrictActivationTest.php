<?php

namespace Tests\Feature\Election;

use App\Application\Election\Deprecation\DeprecationAccessGuard;
use App\Application\Election\Deprecation\DeprecationPolicy;
use App\Application\Election\Deprecation\QueryPolicyGuard;
use Tests\TestCase;

/**
 * GraduatedStrictActivationTest: Verify graduated enforcement levels work correctly
 *
 * Tests the four enforcement levels:
 * - Level 0: warning mode (baseline, no blocking)
 * - Level 1: metrics strict (record violations, no blocking)
 * - Level 2: query guard strict (block queries, record violations)
 * - Level 3: lifecycle strict (block access, record violations)
 * - Level 4: full strict (all blocking, maximum enforcement)
 */
final class GraduatedStrictActivationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Level 1: Metrics Strict — Record violations, do not block
     *
     * Verifies that when STRICT_LEVEL = 1:
     * - Metrics are recorded for all violations
     * - Field access does NOT throw exceptions (Level 4 required for that)
     * - Queries do NOT throw exceptions (Level 2+ required for that)
     */
    public function test_level_1_metrics_strict_records_without_blocking(): void
    {
        $metrics = app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class);
        $accessGuard = app(\App\Application\Election\Deprecation\DeprecationAccessGuard::class);
        $queryGuard = app(\App\Application\Election\Deprecation\QueryPolicyGuard::class);

        // Reset metrics before test
        $metrics->reset();

        // Verify current level is what we expect
        $this->assertEquals(1, DeprecationPolicy::getCurrentLevel());
        $this->assertTrue(DeprecationPolicy::isEnforcementActive(1), 'Level 1 should be active');
        $this->assertFalse(DeprecationPolicy::isEnforcementActive(2), 'Level 2 should not be active at Level 1');

        // At Level 1, field access should NOT throw (no exception expected)
        try {
            $accessGuard->checkFieldAccess('status', 'ElectionController::listElections');
            $this->assertTrue(true, 'No exception thrown at Level 1 for field access');
        } catch (\Exception $e) {
            $this->fail("Level 1 should not throw for field access, but got: {$e->getMessage()}");
        }

        // At Level 1, query violations should NOT throw (no exception expected)
        try {
            $queryGuard->assertAllowedQuery(['status' => 'active'], 'ElectionRepository::findActive');
            $this->assertTrue(true, 'No exception thrown at Level 1 for query');
        } catch (\Exception $e) {
            $this->fail("Level 1 should not throw for query violations, but got: {$e->getMessage()}");
        }

        // Verify metrics were recorded despite no blocking
        $health = $metrics->getHealth();
        $this->assertGreaterThan(0, $health['violations_24h'], 'Metrics should record violations at Level 1');
    }

    /**
     * Enforcement levels are checked correctly via isEnforcementActive()
     */
    public function test_enforcement_level_checks_work_correctly(): void
    {
        $currentLevel = DeprecationPolicy::getCurrentLevel();

        // Level 0 (warning) is always satisfied
        $this->assertTrue(DeprecationPolicy::isEnforcementActive(0));

        // At current level, current and below are active, above are not
        for ($level = 0; $level <= 4; $level++) {
            if ($level <= $currentLevel) {
                $this->assertTrue(
                    DeprecationPolicy::isEnforcementActive($level),
                    "Level {$level} should be active when STRICT_LEVEL = {$currentLevel}"
                );
            } else {
                $this->assertFalse(
                    DeprecationPolicy::isEnforcementActive($level),
                    "Level {$level} should NOT be active when STRICT_LEVEL = {$currentLevel}"
                );
            }
        }
    }

    /**
     * Level names match expected strings
     */
    public function test_level_names_are_correct(): void
    {
        $this->assertEquals('warning mode', DeprecationPolicy::getLevelName(0));
        $this->assertEquals('metrics strict', DeprecationPolicy::getLevelName(1));
        $this->assertEquals('query guard strict', DeprecationPolicy::getLevelName(2));
        $this->assertEquals('lifecycle strict', DeprecationPolicy::getLevelName(3));
        $this->assertEquals('full strict', DeprecationPolicy::getLevelName(4));
    }

    /**
     * Metrics container works correctly
     */
    public function test_metrics_health_report_structure(): void
    {
        $metrics = app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class);
        $health = $metrics->getHealth();

        $this->assertIsArray($health);
        $this->assertArrayHasKey('is_healthy', $health);
        $this->assertArrayHasKey('violations_24h', $health);
        $this->assertArrayHasKey('violations_by_severity', $health);
        $this->assertArrayHasKey('lifecycle_evaluations', $health);
        $this->assertArrayHasKey('strict_mode_ready', $health);
        $this->assertArrayHasKey('report_timestamp', $health);

        $this->assertIsBool($health['is_healthy']);
        $this->assertIsInt($health['violations_24h']);
        $this->assertIsArray($health['violations_by_severity']);
    }
}
