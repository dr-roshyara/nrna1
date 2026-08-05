<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first (RED before implementation) for the LCOM4 observation collector.
 *
 * LCOM4 (Hitz & Montazeri): number of connected components in the graph
 * whose nodes are a class's methods, with edges between methods that share
 * an instance variable or where one calls the other. 1 = cohesive;
 * >1 = the class contains that many disconnected responsibility clusters.
 *
 * The collector emits OBSERVATIONS (metric·class·value·interpretation) —
 * verdict-free, exactly like TestPresenceCollector.
 */
final class Lcom4CollectorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/Lcom4Collector.php';
    }

    /** @return array<string,mixed> first observation for the given source */
    private function first(string $source): array
    {
        $observations = \Lcom4Collector::collect($source);
        $this->assertNotEmpty($observations);
        return $observations[0];
    }

    public function test_cohesive_class_has_lcom4_of_one(): void
    {
        $obs = $this->first('<?php class Cohesive {
            private $shared;
            public function a() { return $this->shared; }
            public function b() { $this->shared = 1; }
        }');

        $this->assertSame('LCOM4', $obs['metric']);
        $this->assertSame('Cohesive', $obs['class']);
        $this->assertSame(1, $obs['value']);
    }

    public function test_two_disjoint_responsibility_clusters_yield_two(): void
    {
        $obs = $this->first('<?php class Split {
            private $x; private $y;
            public function a() { return $this->x; }
            public function b() { $this->x = 2; }
            public function c() { return $this->y; }
            public function d() { $this->y = 3; }
        }');

        $this->assertSame(2, $obs['value']);
        $this->assertStringContainsString('2', $obs['interpretation']);
    }

    public function test_method_calls_connect_components(): void
    {
        $obs = $this->first('<?php class Chained {
            private $y;
            public function a() { return $this->b(); }
            public function b() { return $this->y; }
            public function c() { $this->y = 5; }
        }');

        // a—calls→b, b and c share $y: one component
        $this->assertSame(1, $obs['value']);
    }

    public function test_constructor_is_excluded_from_the_graph(): void
    {
        $obs = $this->first('<?php class CtorGlue {
            private $x; private $y;
            public function __construct() { $this->x = 1; $this->y = 2; }
            public function a() { return $this->x; }
            public function b() { return $this->y; }
        }');

        // Without excluding the constructor this would be 1 — constructors touch
        // everything and would mask real cohesion splits.
        $this->assertSame(2, $obs['value']);
    }

    public function test_isolated_methods_count_as_own_components(): void
    {
        $obs = $this->first('<?php class Loners {
            public function a() { return 1; }
            public function b() { return 2; }
        }');

        $this->assertSame(2, $obs['value']);
    }

    public function test_class_without_methods_yields_zero_with_interpretation(): void
    {
        $obs = $this->first('<?php class Bare { private $x; }');

        $this->assertSame(0, $obs['value']);
        $this->assertStringContainsString('no analyzable methods', $obs['interpretation']);
    }

    public function test_multiple_classes_produce_multiple_observations(): void
    {
        $observations = \Lcom4Collector::collect('<?php
            class A { public function a() {} }
            class B { public function b() {} }
        ');

        $this->assertCount(2, $observations);
        $this->assertSame(['A', 'B'], array_column($observations, 'class'));
    }

    public function test_observation_is_verdict_free(): void
    {
        $obs = $this->first('<?php class Anything { public function a() {} }');

        foreach (['violation', 'passed', 'blocked', 'warning', 'threshold'] as $forbidden) {
            $this->assertArrayNotHasKey($forbidden, $obs);
        }
    }
}
