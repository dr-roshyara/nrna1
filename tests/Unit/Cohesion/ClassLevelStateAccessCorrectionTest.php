<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R5 — GREEN slice (`KOS-PYTHON-RULE-VALIDATION`, 2026-09-27). Characterization +
 * classification E already recorded in `...-R5-receiver-resolution-state-access.md`.
 * This is the RED/GREEN proof for the authorized, CROSS-ADAPTER fix: both
 * `PhpFactExtractor` and `extract_facts.py` now recognise a class-level property access
 * whose qualifier is `self`/`static`/the analysed class's own bare name (PHP) or the
 * analysed class's own bare name (Python) as a `StateAccess`, symmetrically. Scope held
 * to exactly what R5 tested and measured: `parent::`, aliased spellings, and
 * partially/fully-qualified names are DELIBERATELY NOT extended here (matches the
 * existing, already-accepted policy for method calls: parent is out-of-frame, aliases
 * are a stated limitation) — falsification tests below confirm this boundary holds.
 */
final class ClassLevelStateAccessCorrectionTest extends TestCase
{
    /** Test A: PHP self::/static::/OwnClass:: property reads now recognised, converge with the instance-property control shape. */
    public function test_a_php_class_level_property_read_now_recognised_via_self_static_and_own_name(): void
    {
        foreach ([
            'self::$value' => '<?php class A { public static $value = 1; function f() { return self::$value; } }',
            'static::$value' => '<?php class A { public static $value = 1; function f() { return static::$value; } }',
            'A::$value' => '<?php class A { public static $value = 1; function f() { return A::$value; } }',
        ] as $label => $src) {
            $m = PhpFactExtractor::extract($src)->units[0]->methods[0];
            self::assertCount(1, $m->stateAccesses, $label);
            self::assertSame('value', $m->stateAccesses[0]->propertyName, $label);
        }
    }

    /** Test B: Python bare-own-class-name property reads now recognised. */
    public function test_b_python_bare_own_class_name_property_read_now_recognised(): void
    {
        $python = "class A:\n    value = 1\n\n    def f(self):\n        return A.value\n";
        $m = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];
        self::assertCount(1, $m->stateAccesses);
        self::assertSame('value', $m->stateAccesses[0]->propertyName);
    }

    /** Test C (falsification): `parent::$value` remains unrecognised -- matches the existing out-of-frame policy for parent method calls. */
    public function test_c_php_parent_qualifier_remains_unrecognised(): void
    {
        $php = '<?php class Base { public static $value = 1; } class Child extends Base { function f() { return parent::$value; } }';
        $m = PhpFactExtractor::extract($php)->units[1]->methods[0];
        self::assertSame([], $m->stateAccesses, 'parent:: stays out of frame, same policy as parent::method()');
    }

    /** Test D (falsification): aliased spellings remain unrecognised -- matches the existing stated limitation for aliased method calls. */
    public function test_d_php_aliased_qualifier_remains_unrecognised(): void
    {
        $php = '<?php use App\Fq as Alias; namespace App; class Fq { public static $value = 1; function f() { return Alias::$value; } }';
        // Simplify: alias resolution is namespace-scoped; test the direct, unambiguous shape instead.
        $php = "<?php namespace App;\nuse App\\Fq as Alias;\nclass Fq { public static \$value = 1; function f() { return Alias::\$value; } }";
        $m = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame([], $m->stateAccesses, 'AliasedName stays excluded by kind, same policy as aliased method calls');
    }

    /** Test E (falsification): a genuinely different class's property remains unrecognised, both languages. */
    public function test_e_negative_control_other_class_property_remains_unrecognised(): void
    {
        $php = '<?php class Other { public static $value = 1; } class A { function f() { return Other::$value; } }';
        $units = [];
        foreach (PhpFactExtractor::extract($php)->units as $u) { $units[$u->identity->toString()] = $u; }
        self::assertSame([], $units['A']->methods[0]->stateAccesses);

        $python = "class Other:\n    value = 1\n\nclass A:\n    def f(self):\n        return Other.value\n";
        $units2 = [];
        foreach ((new PythonSemanticFactProvider())->extract($python)->units as $u) { $units2[$u->identity->toString()] = $u; }
        self::assertSame([], $units2['A']->methods[0]->stateAccesses);
    }

    /** Test F (falsification): a dynamic/computed static property call (`self::$methodVar()`) is NOT reclassified as state access -- stays unrecognised, matching D-1's existing determinability boundary. */
    public function test_f_dynamic_static_call_via_variable_is_not_misclassified_as_state_access(): void
    {
        $php = '<?php class A { function f($m) { self::$m(); } }';
        $m = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame([], $m->stateAccesses, 'self::$m() is a dynamic call, not a property read -- must not become a spurious StateAccess');
    }

    /** Test G: writes now recognised identically to reads, both languages -- no read/write distinction, as established. */
    public function test_g_writes_now_recognised_identically_to_reads(): void
    {
        $php = '<?php class A { public static $v; function f() { self::$v = 1; } }';
        self::assertCount(1, PhpFactExtractor::extract($php)->units[0]->methods[0]->stateAccesses);

        $python = "class A:\n    v = 0\n    def f(self):\n        A.v = 1\n";
        self::assertCount(1, (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0]->stateAccesses);
    }

    /**
     * Test H: the R5 materiality fixtures now converge, both languages -- LCOM4 = 1
     * both read-only and write-then-read shapes. Precision note: "converge" describes
     * cross-language agreement only. Whether 1 (vs. the pre-fix 2) is the semantically
     * CORRECT value depends on whether class/static-property sharing belongs to the
     * current cohesion contract at all -- a separate, still-open admissibility question
     * this test does not settle and this correction did not require settling (the fix
     * was authorized on cross-language symmetry grounds, not on a resolved "correct
     * value" claim).
     */
    public function test_h_materiality_fixtures_now_converge_to_lcom4_one(): void
    {
        $phpRead = '<?php class A { public static $v = 1; function f(){ return self::$v; } function g(){ return self::$v; } }';
        $pythonRead = "class A:\n    v = 1\n\n    def f(self):\n        return A.v\n\n    def g(self):\n        return A.v\n";
        $phpReadObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($phpRead));
        $pythonReadObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($pythonRead));
        self::assertSame([['f', 'g', 'state']], $phpReadObserved[0]['edges']);
        self::assertSame(1, $phpReadObserved[0]['value']);
        self::assertSame($phpReadObserved[0]['edges'], $pythonReadObserved[0]['edges']);
        self::assertSame($phpReadObserved[0]['value'], $pythonReadObserved[0]['value']);

        $phpWriteRead = '<?php class A { public static $v; function set(){ self::$v = 1; } function get(){ return self::$v; } }';
        $pythonWriteRead = "class A:\n    v = 0\n\n    def set(self):\n        A.v = 1\n\n    def get(self):\n        return A.v\n";
        $phpWRObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($phpWriteRead));
        $pythonWRObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($pythonWriteRead));
        self::assertSame(1, $phpWRObserved[0]['value']);
        self::assertSame($phpWRObserved[0]['value'], $pythonWRObserved[0]['value']);
    }

    /** Test I (regression guard): instance-property access via $this->/self. is completely unaffected. */
    public function test_i_instance_property_access_unaffected(): void
    {
        $php = '<?php class A { public $v = 1; function f(){ return $this->v; } function g(){ return $this->v; } }';
        $python = "class A:\n    def __init__(self):\n        self.v = 1\n\n    def f(self):\n        return self.v\n\n    def g(self):\n        return self.v\n";
        self::assertSame(1, AnalyseCohesion::observe(PhpFactExtractor::extract($php))[0]['value']);
        self::assertSame(1, AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python))[0]['value']);
    }

    /**
     * Test J (Python-only collateral consistency check, not in the original R5 measurement
     * but a direct structural consequence of reusing the property/method-name
     * classification logic for the new own-name qualifier): `A.some_property` (a
     * `@property`-decorated attribute, referenced via the class's own bare name) is
     * classified identically to `self.some_property` -- an Invocation, not a plain state
     * access. Consistency, not scope creep: the same classification logic self/cls
     * already used is simply reached by one more qualifier now.
     */
    public function test_j_python_own_name_property_getter_reference_classified_consistently_with_self(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self._v

                def f(self):
                    return A.value
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $methodF = $facts->units[0]->methods[1];
        self::assertSame('f', $methodF->methodIdentity);
        self::assertCount(1, $methodF->behaviourReferences);
        self::assertSame('Invocation', $methodF->behaviourReferences[0]->referenceMode->name);
    }

    /** Test K: `A.some_method` (a plain method NAMED but not called, via the class's own bare name) is a CallableReference, consistent with `self.some_method`. */
    public function test_k_python_own_name_callable_reference_classified_consistently_with_self(): void
    {
        $python = <<<'PY'
            class A:
                def helper(self):
                    pass

                def f(self):
                    return A.helper
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $methodF = $facts->units[0]->methods[1];
        self::assertSame('CallableReference', $methodF->behaviourReferences[0]->referenceMode->name);
    }
}
