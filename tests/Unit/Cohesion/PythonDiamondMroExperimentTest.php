<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-DIAMOND-MRO-EXPERIMENT (2026-09-27) -- "Test the diamond/MRO
 * case." CHARACTERIZATION ONLY (RED-A): pins the CURRENT, UNMODIFIED adapter's output. This
 * test does NOT prescribe a fix -- it exists to make the discovered tension explicit and
 * regression-visible, per the STOP-and-report requirement below.
 *
 * Findings, in order:
 *
 * 1. PHP cannot express this construct at all: `class D extends B, C` is a PHP PARSE ERROR
 *    (verified directly: `php -l` on that source). Multiple class inheritance does not
 *    exist in PHP. There is no PHP fixture to compare against -- this is a genuine
 *    language-semantic difference (classification F), not a missing PHP test.
 *
 * 2. Python's REAL runtime MRO (verified by executing the code, not by reading the AST):
 *    for `class D(B, C)` where both B and C extend A, `D.__mro__` is
 *    `[D, B, C, A, object]`, and `D().helper()` genuinely resolves to `B.helper` --
 *    not `A` (the common ancestor), not an arbitrary choice.
 *
 * 3. The current adapter's `super()` recognition does not inspect `class_node.bases` at
 *    all, so it produces the IDENTICAL representation for this diamond case as for the
 *    single-inheritance case: `ParentKeyword`/`Undetermined`/`Determinable`. The
 *    `Determinable` label is not actually earned here -- resolving which of B/C is the
 *    true target requires real C3 linearization, which this adapter does not perform.
 *
 * 4. STOP, not fixed: switching `determinability` to `NotDeterminable` (the more
 *    epistemically honest choice) would NOT be a safe, isolated change. `EdgeRules`
 *    checks `determinability===NotDeterminable` (line 32-35) BEFORE
 *    `qualifierKind===ParentKeyword` (line 38) -- so the reported exclusion reason would
 *    silently flip from `OutOfFrame` to `NotDeterminable`, a LESS semantically apt label
 *    for something that is categorically "ancestor behaviour, out of frame" regardless of
 *    which ancestor MRO actually selects. This is a genuine L3/L4 field-interaction found
 *    BY this experiment, not a simple bug -- it is reported, not silently resolved, per
 *    the grant's explicit "no EdgeRules change without STOP-and-report first."
 */
final class PythonDiamondMroExperimentTest extends TestCase
{
    private const PYTHON_DIAMOND = <<<'PY'
        class A:
            def helper(self):
                pass

        class B(A):
            def helper(self):
                pass

        class C(A):
            def helper(self):
                pass

        class D(B, C):
            def helper(self):
                super().helper()
        PY;

    public function test_characterization_current_adapter_does_not_distinguish_single_from_multi_parent_super(): void
    {
        $unit = (new PythonSemanticFactProvider())->extract(self::PYTHON_DIAMOND)->units[3];
        self::assertSame('D', $unit->identity->toString());

        $ref = $unit->methods[0]->behaviourReferences[0];
        self::assertSame('ParentKeyword', $ref->qualifierKind->name);
        self::assertSame('Undetermined', $ref->targetUnitRelation->name);
        // NOT (yet) refined for the diamond case -- same as single inheritance. Characterized,
        // not endorsed: see class docblock finding 3.
        self::assertSame('Determinable', $ref->determinability->name);
    }

    public function test_characterization_the_observable_graph_outcome_is_unaffected_either_way(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_DIAMOND);
        $observed = AnalyseCohesion::observe($facts);

        self::assertSame([], $observed[3]['edges']);
        self::assertSame('OutOfFrame', $observed[3]['excluded'][0]['reason']);
        self::assertSame(1, $observed[3]['value']);
    }
}
