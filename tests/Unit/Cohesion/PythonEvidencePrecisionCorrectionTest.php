<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\IndeterminateBehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-EVIDENCE-PRECISION-CORRECTION (2026-09-27). Narrowly scoped:
 * corrects the Python adapter's premature membership-based conversion of an explicitly
 * named self-call into NotDeterminable. NOT an inheritance resolver -- the adapter still
 * has zero knowledge of base classes; it simply stops PRE-JUDGING frame membership at
 * construction time, exactly as PhpFactExtractor already does, letting the existing
 * GraphBuilder node-matching fallback (TargetNotDeclaredHere) do that job.
 *
 * Four cases (A-D), isolating the causal variable precisely:
 */
final class PythonEvidencePrecisionCorrectionTest extends TestCase
{
    private function pythonRef(string $source, string $methodName): ?object
    {
        $unit = (new PythonSemanticFactProvider())->extract($source)->units[array_key_last((new PythonSemanticFactProvider())->extract($source)->units)];
        foreach ($unit->methods as $m) {
            if ($m->methodIdentity === $methodName) {
                return $m->behaviourReferences[0] ?? null;
            }
        }
        return null;
    }

    /** Case A -- ordinary local target: unaffected either way. */
    public function test_case_a_ordinary_local_target(): void
    {
        $python = "class A:\n    def helper(self):\n        pass\n\n    def f(self):\n        self.helper()\n";
        $ref = $this->pythonRef($python, 'f');

        self::assertSame('Determinable', $ref->determinability->name);
        self::assertSame('DenotesAnalysedUnit', $ref->targetUnitRelation->name);

        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        self::assertSame([['f', 'helper', 'behaviour']], $observed[0]['edges']);
    }

    /**
     * Case B -- inherited target, no override. THE CORRECTED CASE.
     * RED before the fix: determinability was NotDeterminable, reason NotDeterminable.
     * GREEN after: determinability Determinable, reason TargetNotDeclaredHere.
     */
    public function test_case_b_inherited_target_reports_determinable_and_excludes_as_target_not_declared_here(): void
    {
        $python = "class Base:\n    def helper(self):\n        pass\n\nclass Child(Base):\n    def f(self):\n        self.helper()\n";
        $ref = $this->pythonRef($python, 'f');

        self::assertSame('Determinable', $ref->determinability->name, 'target name is statically explicit -- determinable');
        self::assertSame('DenotesAnalysedUnit', $ref->targetUnitRelation->name, 'as WRITTEN, self. denotes this unit -- frame membership is GraphBuilder\'s job, not the adapter\'s');

        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        $child = $observed[array_key_last($observed)];
        self::assertSame([], $child['edges']);
        self::assertSame('TargetNotDeclaredHere', $child['excluded'][0]['reason']);
        self::assertNotSame('NotDeterminable', $child['excluded'][0]['reason']);
    }

    /** Case C -- inherited target WITH override: unaffected (already passing, confirmed still true). */
    public function test_case_c_override_still_creates_an_edge(): void
    {
        $python = "class Base:\n    def helper(self):\n        pass\n\nclass Child(Base):\n    def helper(self):\n        pass\n\n    def f(self):\n        self.helper()\n";
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        $child = $observed[array_key_last($observed)];

        self::assertSame([['f', 'helper', 'behaviour']], $child['edges']);
    }

    /**
     * Case D -- genuine D-1 control. ORIGINALLY asserted this construct produced NO fact
     * of any kind (the then-unrepresented D-1 boundary) -- now HISTORICAL -- corrected by
     * D-1's GREEN slice (2026-09-28): `getattr(self, name)()` is observed and excluded, not
     * silently unseen. Preserved with updated assertions rather than deleted, so this file's
     * own history stays legible. Full RED/GREEN proof:
     * `D1IndeterminateBehaviourReferenceCorrectionTest.php`.
     */
    public function test_case_d_genuine_d1_dynamic_target_is_now_observed_as_indeterminate(): void
    {
        $python = "class A:\n    def f(self, name):\n        getattr(self, name)()\n";
        $unit = (new PythonSemanticFactProvider())->extract($python)->units[0];
        $fMethod = $unit->methods[0];

        // getattr(self, name)() is still not a self./cls. receiver call -- it never matches
        // this adapter's receiver-qualifier check. It is now recognised by its own,
        // dedicated D-1 branch instead, producing exactly one IndeterminateBehaviourReference
        // -- observed and excluded, never a legal name, never confused with never-seen.
        self::assertCount(1, $fMethod->behaviourReferences);
        self::assertInstanceOf(IndeterminateBehaviourReference::class, $fMethod->behaviourReferences[0]);
        self::assertSame([], $fMethod->stateAccesses);
    }

    /** PHP independence check -- confirms convergence at the evidence level, not imitation. */
    public function test_php_independently_produces_the_same_evidence_for_case_b(): void
    {
        $php = '<?php class Base { public function helper() {} } class Child extends Base { public function f() { $this->helper(); } }';
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(
            "class Base:\n    def helper(self):\n        pass\n\nclass Child(Base):\n    def f(self):\n        self.helper()\n"
        ));

        self::assertSame($phpObserved[1]['excluded'][0]['reason'], $pythonObserved[1]['excluded'][0]['reason']);
        self::assertSame($phpObserved[1]['edges'], $pythonObserved[1]['edges']);
        self::assertSame($phpObserved[1]['value'], $pythonObserved[1]['value']);
    }
}
