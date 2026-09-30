<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R5 — does R3's receiver-resolution correction generalize from `BehaviourReference`
 * (method calls) to `StateAccess` (attribute reads)? Characterization only
 * (`KOS-PYTHON-RULE-VALIDATION`, 2026-09-27). NO production code touched by this file.
 *
 * H1 (general limitation, affects both fact categories) vs. H2 (R3 was call-specific).
 *
 * DECISIVE, UNANTICIPATED FINDING (at characterization time): this was NEITHER purely H1
 * nor H2 as originally framed. `PhpFactExtractor` itself -- unmodified, the reference
 * implementation R3 measured Python against -- produced ZERO `StateAccess` facts for
 * `self::$v`/`static::$v`/`OwnClass::$v`. NOT a Python-specific deficiency the way R3's
 * method-call gap was (PHP already had a correct mechanism there) -- a SYMMETRIC gap,
 * present in BOTH adapters, for a construct neither was ever built to extract.
 * Classified E (analysis/pipeline limitation), not D.
 *
 * HISTORY, preserved rather than erased: the cross-adapter correction (both
 * `PhpFactExtractor::factsIn()` and `extract_facts.py`'s `_state_access_qualifier()`)
 * was authorized and implemented (grant/RED-GREEN proof in
 * `ClassLevelStateAccessCorrectionTest.php`), scoped to exactly `self`/`static`/the
 * unit's own unqualified name -- `parent::`, aliased spellings, and qualified names
 * deliberately remain excluded, matching the pre-existing policy for method calls. The
 * tests below now assert the CORRECTED behaviour; control/negative-control/falsification
 * assertions are unchanged since that behaviour was never meant to move.
 */
final class ReceiverResolutionStateAccessExperimentTest extends TestCase
{
    /** Control: `self.value`/`$this->value` already work -- confirms the fix, if any, must not disturb this. */
    public function test_control_instance_receiver_state_access_already_works_both_languages(): void
    {
        $php = '<?php class A { public $value = 1; function f() { return $this->value; } }';
        $python = "class A:\n    value = 1\n\n    def f(self):\n        return self.value\n";

        self::assertCount(1, PhpFactExtractor::extract($php)->units[0]->methods[0]->stateAccesses);
        self::assertCount(1, (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0]->stateAccesses);
    }

    /** CORRECTED: PHP self/static/own-name class-level property reads are now recognised. */
    public function test_php_class_level_property_read_via_self_static_and_own_name_is_now_recognised(): void
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

    /** CORRECTED: Python bare-class-name attribute read is now recognised, symmetric with PHP. */
    public function test_python_bare_class_name_state_access_is_now_recognised(): void
    {
        $python = "class A:\n    value = 1\n\n    def f(self):\n        return A.value\n";
        $m = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];
        self::assertCount(1, $m->stateAccesses);
        self::assertSame('value', $m->stateAccesses[0]->propertyName);
    }

    /** Negative control: a bare name denoting a genuinely DIFFERENT class must stay unrecognised, either language. */
    public function test_negative_control_other_class_attribute_read_stays_unrecognised(): void
    {
        $python = "class Other:\n    value = 1\n\nclass A:\n    def f(self):\n        return Other.value\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $units = [];
        foreach ($facts->units as $u) { $units[$u->identity->toString()] = $u; }
        self::assertSame([], $units['A']->methods[0]->stateAccesses);

        $php = '<?php class Other { public static $value = 1; } class A { function f() { return Other::$value; } }';
        $units2 = [];
        foreach (PhpFactExtractor::extract($php)->units as $u) { $units2[$u->identity->toString()] = $u; }
        self::assertSame([], $units2['A']->methods[0]->stateAccesses);
    }

    /**
     * CORRECTED: two methods sharing a class-level property via `self::$v`/`A.v` now
     * CONVERGE (identical shape, both languages). "Correct" describes cross-language
     * agreement, not a resolved claim that class-level sharing belongs in the cohesion
     * contract -- that admissibility question remains open (see the R5 report and R7).
     */
    public function test_materiality_both_languages_now_report_the_correct_connected_shape(): void
    {
        $php = '<?php class A { public static $v = 1; function f(){ return self::$v; } function g(){ return self::$v; } }';
        $python = "class A:\n    v = 1\n\n    def f(self):\n        return A.v\n\n    def g(self):\n        return A.v\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([['f', 'g', 'state']], $phpObserved[0]['edges']);
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame(1, $phpObserved[0]['value']);
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }

    /** CORRECTED: writes now recognised identically to reads, both languages -- no read/write distinction, as established. */
    public function test_write_access_via_the_tested_qualifier_now_works_the_same_way_both_languages(): void
    {
        $php = '<?php class A { public static $v; function f() { self::$v = 1; } }';
        $python = "class A:\n    v = 0\n    def f(self):\n        A.v = 1\n";

        self::assertCount(1, PhpFactExtractor::extract($php)->units[0]->methods[0]->stateAccesses);
        self::assertCount(1, (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0]->stateAccesses);

        // Control: instance-property writes already worked, identically to reads -- no
        // read/write distinction exists anywhere in this model, unaffected by this fix.
        $phpControl = '<?php class A { public $v; function f() { $this->v = 1; } }';
        $pythonControl = "class A:\n    v = 0\n    def f(self):\n        self.v = 1\n";
        self::assertCount(1, PhpFactExtractor::extract($phpControl)->units[0]->methods[0]->stateAccesses);
        self::assertCount(1, (new PythonSemanticFactProvider())->extract($pythonControl)->units[0]->methods[0]->stateAccesses);
    }

    /**
     * CORRECTED: the strongest candidate pattern (one method writes, another reads the
     * same class-level property) now converges to the same shape, both languages, that
     * the accepted `call-chain.php`/`self-call.php` golden fixtures already produce for
     * INSTANCE-level properties -- offered as a structural parallel, not proof that
     * class-level sharing is admitted under the same contract (still open, see R7).
     */
    public function test_write_then_read_materiality_both_languages_now_report_the_correct_connected_shape(): void
    {
        $php = '<?php class A { public static $v; function set(){ self::$v = 1; } function get(){ return self::$v; } }';
        $python = "class A:\n    v = 0\n\n    def set(self):\n        A.v = 1\n\n    def get(self):\n        return A.v\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame(1, $phpObserved[0]['value']);
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }
}
