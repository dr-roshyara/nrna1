<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant coverage: dynamic-attribute-interception falsification ladder
 * (`__getattr__` / `__getattribute__` / PHP `__get`). Adversarial by design -- unlike the
 * descriptor experiment, this ladder DOES find an observable LCOM4 difference between the
 * naive (current) representation and a hypothetically "fully resolved" one. Classified,
 * not silently accepted: see the accompanying report for the A-F classification and the
 * reasoning for why this is (B) intentional abstraction, not a canonical gap.
 *
 * Verified by REAL execution first, not assumed: PHP's `$a->x` genuinely returns 42 via
 * `__get`; Python's `A().f()` genuinely returns 42 via both `__getattr__` and
 * `__getattribute__`.
 */
final class DynamicAttributeInterceptionExperimentTest extends TestCase
{
    private const PHP_MAGIC_GET = <<<'PHP'
        <?php
        class A {
            public function __get($name) {
                return $this->helper();
            }
            public function helper() {
                return 42;
            }
            public function f() {
                return $this->x;
            }
        }
        PHP;

    private const PYTHON_GETATTR = <<<'PY'
        class A:
            def __getattr__(self, name):
                return self.helper()

            def helper(self):
                return 42

            def f(self):
                return self.x
        PY;

    private const PYTHON_GETATTRIBUTE = <<<'PY'
        class A:
            def __getattribute__(self, name):
                if name == "x":
                    return self.helper()
                return object.__getattribute__(self, name)

            def helper(self):
                return 42

            def f(self):
                return self.x
        PY;

    /**
     * The hidden dependency f -> (interception protocol) -> helper is NOT captured by
     * either binding. Both independently-implemented, pre-existing/current adapters agree:
     * f's `self.x`/`$this->x` is plain StateAccess; the magic method's OWN internal call to
     * helper() is captured (it's an ordinary self-call, syntactically), but nothing connects
     * f to it. Result: LCOM4=2 in all three cases -- the class reports as LESS cohesive than
     * its true runtime behaviour, by design, consistent with the pinned contract's existing
     * philosophy for dynamic/non-syntactically-apparent dependencies (D-1).
     */
    public function test_php_magic_get_hidden_dependency_is_not_captured(): void
    {
        $observed = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP_MAGIC_GET));
        self::assertSame([['__get', 'helper', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);
    }

    public function test_python_getattr_hidden_dependency_is_not_captured(): void
    {
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON_GETATTR));
        self::assertSame([['__getattr__', 'helper', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);
    }

    public function test_python_getattribute_hidden_dependency_is_not_captured(): void
    {
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON_GETATTRIBUTE));
        self::assertSame([['__getattribute__', 'helper', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);
    }

    /** Cross-mechanism convergence, made explicit: three different language mechanisms, same abstraction. */
    public function test_all_three_mechanisms_converge_on_the_same_lcom4_value(): void
    {
        $phpValue = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP_MAGIC_GET))[0]['value'];
        $getattrValue = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON_GETATTR))[0]['value'];
        $getattributeValue = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::PYTHON_GETATTRIBUTE))[0]['value'];

        self::assertSame($phpValue, $getattrValue);
        self::assertSame($phpValue, $getattributeValue);
    }
}
