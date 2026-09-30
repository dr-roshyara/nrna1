<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Control experiment (Case B), same grant as InheritanceCrossLanguageExperimentTest (Case A
 * -- inherited-only, already found to DIVERGE). Isolates the causal variable: does `Child`
 * itself declare `helper`? H1: hypothesis, tested here, not assumed -- override converges.
 * H2: if it does, the earlier divergence is localized to "target not declared in the
 * analysed unit", not to inheritance in general.
 */
final class InheritanceOverrideControlExperimentTest extends TestCase
{
    private const PHP = <<<'PHP'
        <?php
        class Base {
            public function helper() {}
        }
        class Child extends Base {
            public function helper() {}
            public function f() {
                $this->helper();
            }
        }
        PHP;

    private const PYTHON = <<<'PY'
        class Base:
            def helper(self):
                pass

        class Child(Base):
            def helper(self):
                pass

            def f(self):
                self.helper()
        PY;

    /**
     * H1 (hypothesis, tested not assumed): with `helper` genuinely declared in `Child`,
     * PHP's unconditional assumption and Python's membership check should agree -- PHP's
     * assumption happens to be correct this time, and Python's check now finds a real match.
     */
    public function test_h1_override_case_converges_at_l3_field_by_field(): void
    {
        $phpChild = PhpFactExtractor::extract(self::PHP)->units[1];
        $pythonChild = (new PythonSemanticFactProvider())->extract(self::PYTHON)->units[1];

        $phpRef = self::behaviourRefOf($phpChild, 'f');
        $pythonRef = self::behaviourRefOf($pythonChild, 'f');

        self::assertNotNull($phpRef);
        self::assertNotNull($pythonRef);
        self::assertSame($phpRef->targetMethodName, $pythonRef->targetMethodName);
        self::assertSame($phpRef->qualifierKind->name, $pythonRef->qualifierKind->name);
        self::assertSame($phpRef->targetUnitRelation->name, $pythonRef->targetUnitRelation->name);
        self::assertSame($phpRef->referenceMode->name, $pythonRef->referenceMode->name);
        self::assertSame($phpRef->determinability->name, $pythonRef->determinability->name);
        self::assertSame('DenotesAnalysedUnit', $phpRef->targetUnitRelation->name);
        self::assertSame('Determinable', $phpRef->determinability->name);
    }

    /** H4: graph must follow L3 -- since L3 converges here, the graph must too (an edge, this time). */
    public function test_h4_graph_converges_given_l3_convergence(): void
    {
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON));

        self::assertSame($phpObserved[1]['edges'], $pythonObserved[1]['edges']);
        self::assertSame([['f', 'helper', 'behaviour']], $pythonObserved[1]['edges']);
        self::assertSame([], $pythonObserved[1]['excluded']);
    }

    /** H5: metric reported last, and only as confirmation, not as the primary evidence. */
    public function test_h5_metric_converges_after_l3_and_l4_already_did(): void
    {
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON));

        self::assertSame($phpObserved[1]['value'], $pythonObserved[1]['value']);
        self::assertSame(1, $pythonObserved[1]['value']);
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
