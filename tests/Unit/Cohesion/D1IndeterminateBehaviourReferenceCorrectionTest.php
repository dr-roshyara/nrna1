<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\EdgeRules;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ExclusionReason;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\IndeterminateBehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * D-1 — GREEN slice (2026-09-28). Implements the adopted, characterized representation
 * for a computed method-name dispatch (`$this->$m()` / `getattr(self, name)()`): a
 * distinct L3 fact kind (`IndeterminateBehaviourReference`), no target-name field,
 * always excluded as `NotDeterminable` by a new, unconditional `EdgeRules` branch.
 * Contract: `2026-09-28-KOS-D1-implementation-contract.md`. Full RED/GREEN + real-corpus
 * evidence: `2026-09-28-KOS-D1-implementation-results.md`.
 */
final class D1IndeterminateBehaviourReferenceCorrectionTest extends TestCase
{
    public function test_indeterminate_behaviour_reference_is_always_excluded_as_not_determinable(): void
    {
        $verdict = EdgeRules::verdict(new IndeterminateBehaviourReference());

        self::assertFalse($verdict->isIncluded());
        self::assertSame(ExclusionReason::NotDeterminable, $verdict->reason());
    }

    public function test_php_this_dollar_method_is_now_observed_and_excluded_not_silently_unseen(): void
    {
        $php = '<?php class A { function caller() { $m = "target"; return $this->$m(); } function target() { return 1; } }';

        $observed = AnalyseCohesion::observe(PhpFactExtractor::extract($php));

        self::assertSame(['caller', 'target'], $observed[0]['nodes']);
        self::assertSame([], $observed[0]['edges'], 'a computed target must never accidentally become a concrete edge');
        self::assertSame(
            [['method' => 'caller', 'target' => null, 'reason' => 'NotDeterminable']],
            $observed[0]['excluded'],
            'seen-and-excluded, not never-seen — this is the exact V-3 finding this slice closes',
        );
    }

    public function test_python_getattr_self_name_is_now_observed_and_excluded_not_silently_unseen(): void
    {
        $python = "class A:\n    def caller(self):\n        m = 'target'\n        return getattr(self, m)()\n\n    def target(self):\n        return 1\n";

        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame(['caller', 'target'], $observed[0]['nodes']);
        self::assertSame([], $observed[0]['edges'], 'a computed target must never accidentally become a concrete edge');
        self::assertSame(
            [['method' => 'caller', 'target' => null, 'reason' => 'NotDeterminable']],
            $observed[0]['excluded'],
        );
    }

    public function test_cross_language_parity_php_and_python_converge_on_the_same_canonical_outcome(): void
    {
        $php = '<?php class A { function caller() { $m = "target"; return $this->$m(); } function target() { return 1; } }';
        $python = "class A:\n    def caller(self):\n        m = 'target'\n        return getattr(self, m)()\n\n    def target(self):\n        return 1\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame($phpObserved[0]['nodes'], $pythonObserved[0]['nodes']);
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame($phpObserved[0]['excluded'], $pythonObserved[0]['excluded']);
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }

    public function test_negative_control_an_ordinary_determinable_call_is_unaffected_both_languages(): void
    {
        $php = '<?php class A { function foo() { return $this->bar(); } function bar() { return 1; } }';
        $python = "class A:\n    def foo(self):\n        return self.bar()\n\n    def bar(self):\n        return 1\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([['foo', 'bar', 'behaviour']], $phpObserved[0]['edges']);
        self::assertSame([], $phpObserved[0]['excluded']);
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame($phpObserved[0]['excluded'], $pythonObserved[0]['excluded']);
        self::assertSame(1, $phpObserved[0]['value']);
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }

    public function test_metric_neutrality_a_dynamic_dispatch_site_does_not_change_lcom4_relative_to_no_reference_at_all(): void
    {
        // caller1 has ONLY a dynamic-dispatch reference (contributes no edge); caller2
        // has an ordinary determinable edge to bar. If the dynamic site accidentally
        // connected, LCOM4 would drop below 2 (fewer isolated components). It must not.
        $php = '<?php class A {
            function caller1() { $m = "target"; return $this->$m(); }
            function target() { return 1; }
            function caller2() { return $this->bar(); }
            function bar() { return 1; }
        }';

        $observed = AnalyseCohesion::observe(PhpFactExtractor::extract($php));

        self::assertSame([['caller2', 'bar', 'behaviour']], $observed[0]['edges']);
        self::assertSame(3, $observed[0]['value'], 'caller1, target, and the {caller2,bar} pair — three isolated components');
    }
}
