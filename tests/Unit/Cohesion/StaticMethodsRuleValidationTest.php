<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R6 — `static_methods`. Characterization only (`KOS-PYTHON-RULE-VALIDATION`,
 * 2026-09-27). NO production code touched by this file.
 *
 * Pinned decision, recovered verbatim (`expected.json`): "INCLUDED as nodes; a static
 * that neither touches $this NOR participates in an internal behavioural relationship is
 * an isolated component (inflates LCOM4 -- known, pinned, revisitable)." Golden fixtures:
 * `static-methods.php` (isolated static, LCOM4=2) and `static-call-chain.php` (two
 * statics linked via `self::`, LCOM4=1).
 *
 * The existing `PythonClassStaticDispatchExperimentTest.php` (pre-R1) already covers a
 * `@staticmethod` being CALLED via `self.`/`cls.` -- but never in ISOLATION, and never a
 * true `@staticmethod` (no `self`/`cls` parameter at all) calling ANOTHER method. That
 * second case matters specifically because of R3: a real Python `@staticmethod` has no
 * receiver whatsoever -- the ONLY way it can reference a sibling method is via the bare
 * class name (`ClassName.other()`), which did not work at all before R3's correction.
 * This test file is therefore the first place `static_methods` is validated against a
 * GENUINE Python static method, not a classmethod/instance method standing in for one.
 */
final class StaticMethodsRuleValidationTest extends TestCase
{
    /** Case 1: an isolated static (touches nothing) inflates LCOM4 identically, both languages -- matches `static-methods.php` exactly. */
    public function test_case_1_isolated_static_method_inflates_lcom4_both_languages(): void
    {
        $php = '<?php class A { private $data; function instance() { return $this->data; } static function helper() { return 42; } }';
        $python = "class A:\n    def __init__(self):\n        self.data = 1\n\n    def instance(self):\n        return self.data\n\n    @staticmethod\n    def helper():\n        return 42\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame(2, $phpObserved[0]['value'], 'matches the golden static-methods.php value exactly');
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
        self::assertSame($phpObserved[0]['nodes'], $pythonObserved[0]['nodes']);
    }

    /**
     * Case 2: two GENUINE static methods (Python: no `self`/`cls` parameter at all -- a
     * true `@staticmethod`), one calling the other via the bare class name -- its only
     * possible mechanism. Matches `static-call-chain.php` exactly. This specific
     * construct was UNREACHABLE before R3 (a true staticmethod has no receiver to
     * express a sibling call through), making this the first genuine validation of
     * `static_methods` for a real Python static method calling another.
     */
    public function test_case_2_true_static_to_static_chain_via_bare_class_name_converges(): void
    {
        $php = '<?php class A { public static function alpha() { return self::beta(); } public static function beta() { return 42; } }';
        $python = "class A:\n    @staticmethod\n    def alpha():\n        return A.beta()\n\n    @staticmethod\n    def beta():\n        return 42\n";

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([['alpha', 'beta', 'behaviour']], $phpObserved[0]['edges']);
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame(1, $phpObserved[0]['value'], 'matches the golden static-call-chain.php value exactly');
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }
}
