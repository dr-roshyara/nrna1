<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodRole;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R4 — `magic_methods`. Characterization only (`KOS-PYTHON-RULE-VALIDATION`, 2026-09-27).
 * NO production code touched by this file.
 *
 * Recovered meaning, separated as four distinct things, not assumed:
 * 1. HISTORICAL DECISION (`expected.json`): "no special handling beyond
 *    constructor/destructor exclusion."
 * 2. ACTUAL CURRENT IMPLEMENTATION (grepped directly, this session): confirmed true --
 *    no magic/dunder name other than the R1 lifecycle pair (`__construct`/`__destruct`
 *    PHP, `__init__`/`__del__` Python) is referenced anywhere in `Domain` or either
 *    adapter. Every other magic method is `MethodRole::Ordinary` and is walked exactly
 *    like any user-named method.
 * 3. ANALYTICAL MEANING: the contract was never modeling INVOCATION MECHANISM (how or
 *    when a method gets called) at all -- only EXPLICIT SOURCE-LEVEL REFERENCES written
 *    inside a method's own body ever become `BehaviourReference`/`StateAccess` facts.
 *    A magic method's runtime-triggered call site (Python's implicit `__next__` call in
 *    a `for` loop; PHP's implicit `__toString()` in a string context) is always a
 *    CALLER-side event, outside the analysed unit's own body -- structurally the same
 *    "class analysed in isolation" boundary R2 already generalized. Already used once,
 *    with real corpus measurement (2 magic-method definitions / 1,629 PHP files), as the
 *    explicit grounds for classifying `__getattr__`/`__getattribute__`/`__get`
 *    interception as an intentional boundary
 *    (`2026-09-27-KOS-D1-analytical-definition-decision.md`) -- NOT re-run here.
 * 4. THEORETICAL JUSTIFICATION: the metric measures a method's OWN declared structure
 *    (shared state / explicit intra-unit calls), never the mechanism by which a caller
 *    invokes it. This is language-neutral by construction, not by enumerating every
 *    magic method in every language.
 *
 * Two genuinely new categories tested here (iterator protocol, context-manager
 * protocol) -- chosen because their RUNTIME-triggered relationship (the interpreter
 * calls `__next__`/`__exit__` implicitly) is structurally different from both the
 * already-closed lifecycle case (R1) and the already-closed interception case, so they
 * can actually falsify the theory above if the current adapter treated them specially
 * or produced a surprising graph.
 */
final class MagicMethodRuntimeSemanticsExperimentTest extends TestCase
{
    /** Regression control only (R1 is frozen, not reopened): confirms lifecycle role is unaffected here. */
    public function test_regression_control_lifecycle_role_unaffected(): void
    {
        $python = "class A:\n    def __init__(self):\n        self.x = 1\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        self::assertSame(MethodRole::Lifecycle, $facts->units[0]->methods[0]->methodRole);
    }

    /**
     * Category B -- iterator protocol. `__iter__` returns bare `self` (no attribute
     * access at all -- zero facts); `__next__` and `f` both read `self.value` (state
     * access) -- an ordinary shared-state edge, exactly as if these were arbitrarily
     * named methods. No implicit `__iter__`-calls-`__next__` edge is modeled, because
     * nothing in the SOURCE says so -- the runtime relationship exists only outside this
     * unit's own body, at the caller's `for` statement.
     */
    public function test_category_b_iterator_protocol_is_analysed_as_ordinary_methods(): void
    {
        $python = <<<'PY'
            class A:
                def __iter__(self):
                    return self

                def __next__(self):
                    return self.value

                def f(self):
                    return self.value
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        foreach ($facts->units[0]->methods as $m) {
            self::assertSame(MethodRole::Ordinary, $m->methodRole, $m->methodIdentity);
        }

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['__iter__', '__next__', 'f'], $observed[0]['nodes']);
        self::assertSame([['__next__', 'f', 'state']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value'], '{__iter__} isolated, {__next__,f} connected -- two components');
    }

    /**
     * Category D -- context-manager protocol. `__exit__` explicitly calls
     * `self.cleanup()` -- an ordinary, already-handled self-call, included as a normal
     * behaviour edge. `__enter__` returns bare `self` -- zero facts, isolated. No
     * special "protocol pairing" is modeled between `__enter__`/`__exit__`, matching the
     * theory: only explicit source-level references matter.
     */
    public function test_category_d_context_manager_protocol_is_analysed_as_ordinary_methods(): void
    {
        $python = <<<'PY'
            class A:
                def __enter__(self):
                    return self

                def __exit__(self, exc_type, exc, tb):
                    self.cleanup()

                def cleanup(self):
                    pass
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        foreach ($facts->units[0]->methods as $m) {
            self::assertSame(MethodRole::Ordinary, $m->methodRole, $m->methodIdentity);
        }

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([['__exit__', 'cleanup', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value'], '{__enter__} isolated, {__exit__,cleanup} connected -- two components');
    }
}
