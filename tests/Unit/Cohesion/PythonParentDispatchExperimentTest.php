<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-PARENT-DISPATCH-EXPERIMENT (2026-09-27). Single inheritance
 * only. Isolates the receiver/dispatch mechanism: call_instance() (self.) vs
 * call_parent() (super()/parent::) as SEPARATE methods, so instance dispatch and parent
 * dispatch are not entangled in one recursive body.
 *
 * Step 1 (characterized before this file existed, real unmodified PhpFactExtractor):
 * `parent::helper()` -> qualifierKind=ParentKeyword, targetUnitRelation=Undetermined,
 * determinability=Determinable, excluded reason=OutOfFrame. EdgeRules excludes ParentKeyword
 * unconditionally (line 37-40), independent of targetUnitRelation/determinability.
 *
 * Step 2 (characterized before this file existed, current unmodified Python adapter):
 * `super().helper()` produces NOTHING -- not StateAccess, not BehaviourReference, complete
 * silence (the receiver is a Call to `super`, not a Name, so the existing self/cls check
 * never matches it at all).
 */
final class PythonParentDispatchExperimentTest extends TestCase
{
    private const PHP = <<<'PHP'
        <?php
        class Base {
            public function helper() {}
        }
        class Child extends Base {
            public function helper() {}
            public function call_instance() { $this->helper(); }
            public function call_parent() { parent::helper(); }
        }
        PHP;

    private const PYTHON = <<<'PY'
        class Base:
            def helper(self):
                pass

        class Child(Base):
            def helper(self):
                pass

            def call_instance(self):
                self.helper()

            def call_parent(self):
                super().helper()
        PY;

    private function childUnit(bool $python): object
    {
        $facts = $python
            ? (new PythonSemanticFactProvider())->extract(self::PYTHON)
            : PhpFactExtractor::extract(self::PHP);

        return $facts->units[1];
    }

    /** RED-A characterization, PHP side (already true, no PHP change): parent:: is excluded, out of frame. */
    public function test_php_parent_dispatch_is_out_of_frame(): void
    {
        $child = $this->childUnit(python: false);
        $ref = self::behaviourRefOf($child, 'call_parent');

        self::assertNotNull($ref);
        self::assertSame('ParentKeyword', $ref->qualifierKind->name);
        self::assertSame('Undetermined', $ref->targetUnitRelation->name);
        self::assertSame('Determinable', $ref->determinability->name);

        $observed = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        self::assertNotContains('call_parent', array_column($observed[1]['edges'], 0));
        $excludedForParent = array_values(array_filter($observed[1]['excluded'], fn (array $e) => $e['method'] === 'call_parent'));
        self::assertSame('OutOfFrame', $excludedForParent[0]['reason']);
    }

    /** H1/H2: Python must represent parent dispatch, AND must not collapse it into instance dispatch. */
    public function test_python_parent_dispatch_matches_php_and_is_distinct_from_instance_dispatch(): void
    {
        $child = $this->childUnit(python: true);

        $instanceRef = self::behaviourRefOf($child, 'call_instance');
        $parentRef = self::behaviourRefOf($child, 'call_parent');

        self::assertNotNull($instanceRef, 'self.helper() must produce a BehaviourReference.');
        self::assertNotNull($parentRef, 'super().helper() must produce a BehaviourReference -- not silence.');

        self::assertSame('InstanceReceiver', $instanceRef->qualifierKind->name);
        self::assertSame('ParentKeyword', $parentRef->qualifierKind->name);
        self::assertNotSame($instanceRef->qualifierKind->name, $parentRef->qualifierKind->name);

        // Field-by-field match against the real, unmodified PHP characterization:
        $phpParentRef = self::behaviourRefOf($this->childUnit(python: false), 'call_parent');
        self::assertSame($phpParentRef->qualifierKind->name, $parentRef->qualifierKind->name);
        self::assertSame($phpParentRef->targetUnitRelation->name, $parentRef->targetUnitRelation->name);
        self::assertSame($phpParentRef->determinability->name, $parentRef->determinability->name);
    }

    /** H4: graph must follow L3 -- instance dispatch gets an edge, parent dispatch is excluded, in both languages. */
    public function test_h4_graph_reflects_the_l3_distinction_in_both_languages(): void
    {
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON));

        self::assertSame($phpObserved[1]['edges'], $pythonObserved[1]['edges']);
        self::assertContains(['call_instance', 'helper', 'behaviour'], $pythonObserved[1]['edges']);

        $pythonExcludedMethods = array_column($pythonObserved[1]['excluded'], 'method');
        self::assertContains('call_parent', $pythonExcludedMethods);
        self::assertNotContains('call_instance', $pythonExcludedMethods);
    }

    /** H5: metric compared last, as confirmation only. */
    public function test_h5_metric_matches_after_l3_and_l4_already_did(): void
    {
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON));

        self::assertSame($phpObserved[1]['value'], $pythonObserved[1]['value']);
    }

    private static function behaviourRefOf(object $unit, string $methodName): ?object
    {
        foreach ($unit->methods as $m) {
            if ($m->methodIdentity === $methodName) {
                return $m->behaviourReferences[0] ?? null;
            }
        }

        return null;
    }
}
