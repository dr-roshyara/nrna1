<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-INHERITANCE-EXPERIMENT (2026-09-27). Adversarial: parity is
 * NOT assumed. RED-A tests characterize existing behaviour (PHP unchanged, Python's
 * pre-existing "target in method_names" logic once it can see the second class); they do
 * not prescribe a theory. RED-B tests demonstrate the specific new capability
 * (multi-class visibility) the Python adapter currently lacks.
 */
final class InheritanceCrossLanguageExperimentTest extends TestCase
{
    private const PHP = <<<'PHP'
        <?php
        class Base {
            public function helper() {}
        }
        class Child extends Base {
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
            def f(self):
                self.helper()
        PY;

    /**
     * RED-A (characterization, not prescription): pins the REAL, ALREADY-EXISTING
     * PhpFactExtractor behaviour on inherited-method invocation. Observed directly before
     * this test was written (Step 1): the binding does NOT check whether the called name is
     * declared in the analysed unit's own body -- it unconditionally labels any `$this->X()`
     * call DenotesAnalysedUnit/Determinable, and the phantom edge is caught only
     * incidentally, by GraphBuilder's node-matching fallback (TargetNotDeclaredHere), not by
     * any inheritance-aware reasoning at L3.
     */
    public function test_red_a_characterizes_existing_unmodified_php_inheritance_behaviour(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP);
        $child = $facts->units[1];
        $ref = $child->methods[0]->behaviourReferences[0];

        self::assertSame('Child', $child->identity->toString());
        self::assertSame('helper', $ref->targetMethodName);
        self::assertSame('InstanceReceiver', $ref->qualifierKind->name);
        self::assertSame('DenotesAnalysedUnit', $ref->targetUnitRelation->name);
        self::assertSame('Determinable', $ref->determinability->name);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([], $observed[1]['edges']);
        self::assertSame('TargetNotDeclaredHere', $observed[1]['excluded'][0]['reason']);
        self::assertSame(1, $observed[1]['value']);
    }

    /**
     * RED-B (falsification of the current adapter's known, disclosed boundary): the Python
     * provider is scoped to a single class today and must see BOTH Base and Child to say
     * anything about inheritance at all.
     */
    public function test_red_b_python_adapter_must_see_both_classes(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON);
        self::assertCount(2, $facts->units, 'Python adapter must report both Base and Child, not only the first class.');
    }

    /**
     * HISTORY, preserved rather than erased (ES-004.3-style disclosure): this test
     * originally asserted that Python's pre-existing membership check
     * ("target in method_names") produced Undetermined/NotDeterminable here, genuinely
     * DIFFERENT L3 facts from PHP's DenotesAnalysedUnit/Determinable, while the graph/metric
     * outcome coincided (both excluded, both LCOM4=1) -- and reported that divergence
     * honestly rather than forcing artificial parity.
     *
     * Later analysis (2026-09-27, `2026-09-27-KOS-inheritance-and-reflection-classification.md`)
     * corrected the interpretation: `helper`'s target NAME is statically explicit here --
     * there is nothing genuinely indeterminate about it, unlike real D-1. The prior
     * Python behaviour was conflating "target not in this unit's frame" with "target
     * identity unknown" -- exactly the kind of merge `ExclusionReason`'s own docblock warns
     * against. Grant G-KOS-CONTRACT-PYTHON-EVIDENCE-PRECISION-CORRECTION (2026-09-27)
     * authorized the minimal fix. This test now asserts the CORRECTED convergence:
     * PHP and Python now agree at every L3 field, not just at the graph/metric level.
     */
    public function test_python_and_php_l3_facts_now_converge_after_the_evidence_precision_correction(): void
    {
        $phpRef = PhpFactExtractor::extract(self::PHP)->units[1]->methods[0]->behaviourReferences[0];

        $pythonFacts = (new PythonSemanticFactProvider())->extract(self::PYTHON);
        $pythonChild = $pythonFacts->units[1];
        $pythonRef = $pythonChild->methods[0]->behaviourReferences[0];

        self::assertSame('Determinable', $phpRef->determinability->name);
        self::assertSame('Determinable', $pythonRef->determinability->name);
        self::assertSame($phpRef->targetUnitRelation->name, $pythonRef->targetUnitRelation->name);

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP));
        $pythonObserved = AnalyseCohesion::observe($pythonFacts);

        self::assertSame('TargetNotDeclaredHere', $phpObserved[1]['excluded'][0]['reason']);
        self::assertSame('TargetNotDeclaredHere', $pythonObserved[1]['excluded'][0]['reason']);
        self::assertSame([], $pythonObserved[1]['edges']);
        self::assertSame(1, $pythonObserved[1]['value']);
        self::assertSame($phpObserved[1]['value'], $pythonObserved[1]['value']);
    }
}
