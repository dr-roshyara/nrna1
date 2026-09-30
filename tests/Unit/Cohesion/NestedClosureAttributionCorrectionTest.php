<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-5 -- GREEN slice (2026-09-27). Characterization + classification E already
 * recorded in `...-OWD5-nested-closure-attribution.md`. This is the RED/GREEN proof for
 * the authorized, CROSS-ADAPTER fix: neither `extract_facts.py` nor `PhpFactExtractor`
 * now attribute a nested closure's own `self`/`$this` references to the enclosing
 * method. Scope held to exactly what was measured and grounded in real corpus evidence
 * (`contextlib.py`): `def`/`async def`/`lambda` (Python) and `function(...) { ... }`
 * (PHP) are pruned as scope boundaries. Arrow functions (PHP `fn() => ...`) are
 * DELIBERATELY NOT addressed -- a separate, smaller, explicitly out-of-scope gap.
 * Comprehensions/generator expressions are DELIBERATELY NOT scope boundaries here (their
 * body executes immediately as part of the enclosing method, unlike a nested def) --
 * falsification test below confirms this distinction is preserved.
 */
final class NestedClosureAttributionCorrectionTest extends TestCase
{
    /** Test A: the real, materiality-confirmed fixture (modeled on contextlib.ContextDecorator.__call__) now converges to the correct LCOM4, both languages. */
    public function test_a_deferred_closure_reference_no_longer_produces_a_false_edge(): void
    {
        $php = <<<'PHP'
            <?php
            class A {
                private $data;
                public function __call__() {
                    return function() {
                        return $this->_recreate_cm();
                    };
                }
                public function unrelated() {
                    return $this->data;
                }
                public function _recreate_cm() { return 1; }
            }
            PHP;

        $python = <<<'PY'
            class A:
                def __init__(self):
                    self.data = 1

                def __call__(self):
                    def inner():
                        return self._recreate_cm()
                    return inner

                def unrelated(self):
                    return self.data

                def _recreate_cm(self):
                    return 1
            PY;

        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        $pythonObserved = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([], $phpObserved[0]['edges'], '__call__ never calls _recreate_cm() in its own direct execution -- no edge');
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame(3, $phpObserved[0]['value'], 'was 2 pre-fix -- three genuinely independent methods');
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }

    /** Test B: the simplest characterizing fixture from OWD-1/5 -- f no longer shows a state access belonging to its nested closure. */
    public function test_b_simple_nested_closure_state_access_no_longer_misattributed(): void
    {
        $php = '<?php class A { function f() { return function() { return $this->y; }; } function g() { return $this->x; } }';
        $python = "class A:\n    def f(self):\n        def inner():\n            return self.y\n        return inner\n\n    def g(self):\n        return self.x\n";

        $phpF = PhpFactExtractor::extract($php)->units[0]->methods[0];
        $pythonF = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];

        self::assertSame([], $phpF->stateAccesses, 'f itself touches nothing directly');
        self::assertSame([], $pythonF->stateAccesses);
    }

    /** Falsification: a comprehension is NOT a scope boundary -- self.x inside a list comprehension still correctly belongs to the enclosing method. */
    public function test_falsification_comprehension_is_not_a_scope_boundary(): void
    {
        $python = "class A:\n    def f(self):\n        return [self.transform(i) for i in range(3)]\n\n    def transform(self, i):\n        return i\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $f = $facts->units[0]->methods[0];

        self::assertNotSame([], $f->behaviourReferences, 'the comprehension body executes immediately as part of f -- must still be attributed to f');
        self::assertSame('transform', $f->behaviourReferences[0]->targetMethodName);
    }

    /** Falsification: an async function/coroutine is also pruned as a scope boundary, Python only (PHP has no async-closure equivalent to test here). */
    public function test_falsification_async_nested_function_is_also_pruned(): void
    {
        $python = "class A:\n    def f(self):\n        async def inner():\n            return self.y\n        return inner\n";
        $f = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];
        self::assertSame([], $f->stateAccesses);
    }

    /** Falsification: a lambda is also pruned as a scope boundary. */
    public function test_falsification_lambda_is_also_pruned(): void
    {
        $python = "class A:\n    def f(self):\n        return lambda: self.y\n";
        $f = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];
        self::assertSame([], $f->stateAccesses);
    }

    /** Regression guard: a method with no nested closure at all is completely unaffected. */
    public function test_regression_ordinary_method_unaffected(): void
    {
        $php = '<?php class A { private $x; function f() { return $this->x; } }';
        $python = "class A:\n    def __init__(self):\n        self.x = 1\n\n    def f(self):\n        return self.x\n";
        self::assertCount(1, PhpFactExtractor::extract($php)->units[0]->methods[0]->stateAccesses);
        self::assertCount(1, (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[1]->stateAccesses);
    }
}
