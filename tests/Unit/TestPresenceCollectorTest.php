<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first (RED before implementation) for the test-presence collector:
 * an ADVISORY observer that classifies a change set and emits the
 * observation "production changed without accompanying tests" — evidence,
 * never a gate. (Layer-5 slice of the verification-institutionalization
 * analysis, 2026-08-03.)
 */
final class TestPresenceCollectorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/TestPresenceCollector.php';
    }

    public function test_production_change_without_tests_is_observed(): void
    {
        $obs = \TestPresenceCollector::classify([
            'app/Contexts/Demo/Domain/Thing.php',
            'app/Models/Other.php',
        ]);

        $this->assertSame(2, $obs['production_changed']);
        $this->assertSame(0, $obs['tests_changed']);
        $this->assertTrue($obs['production_without_tests']);
        $this->assertContains('app/Contexts/Demo/Domain/Thing.php', $obs['production_files']);
    }

    public function test_mixed_change_is_not_flagged(): void
    {
        $obs = \TestPresenceCollector::classify([
            'app/Contexts/Demo/Domain/Thing.php',
            'tests/Unit/ThingTest.php',
        ]);

        $this->assertSame(1, $obs['production_changed']);
        $this->assertSame(1, $obs['tests_changed']);
        $this->assertFalse($obs['production_without_tests']);
    }

    public function test_tests_only_change_is_not_flagged(): void
    {
        $obs = \TestPresenceCollector::classify(['tests/Unit/SomethingTest.php']);

        $this->assertSame(0, $obs['production_changed']);
        $this->assertFalse($obs['production_without_tests']);
    }

    public function test_docs_config_and_scripts_are_ignored(): void
    {
        $obs = \TestPresenceCollector::classify([
            'docs/plans/some-plan.md',
            'composer.json',
            'scripts/metrics/metrics-report.php',
            'app/Views/thing.blade.php', // views are not production BEHAVIOR
        ]);

        $this->assertSame(0, $obs['production_changed']);
        $this->assertSame(0, $obs['tests_changed']);
        $this->assertFalse($obs['production_without_tests']);
    }

    public function test_observation_is_verdict_free(): void
    {
        $obs = \TestPresenceCollector::classify(['app/Models/Other.php']);

        // The collector observes; it never judges or blocks.
        $this->assertArrayNotHasKey('violation', $obs);
        $this->assertArrayNotHasKey('passed', $obs);
        $this->assertArrayNotHasKey('blocked', $obs);
    }
}
