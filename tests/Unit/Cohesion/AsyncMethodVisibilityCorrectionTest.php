<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodRole;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-13 -- GREEN slice (2026-09-28). Characterization + classification D already
 * recorded in `...-OWD13-async-method-invisibility.md`. RED/GREEN proof for the
 * authorized fix: `extract_facts.py`'s `method_defs` filter now recognises
 * `ast.AsyncFunctionDef` alongside `ast.FunctionDef` -- an async method is a
 * completely ordinary node once visible at all; nothing about `GraphBuilder`,
 * `EdgeRules`, or `Lcom4` needed to change (classification D confirmed: purely a
 * recognition gap, canonical vocabulary already sufficient).
 */
final class AsyncMethodVisibilityCorrectionTest extends TestCase
{
    /** Test A: an isolated async method touching self is now visible and correctly analysed. */
    public function test_a_isolated_async_method_is_now_visible(): void
    {
        $python = <<<'PY'
            class A:
                async def fetch(self):
                    return self.data

                def sync_method(self):
                    return self.data
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);

        self::assertCount(2, $facts->units[0]->methods, 'was 1 pre-fix -- fetch now correctly visible');
        $byName = [];
        foreach ($facts->units[0]->methods as $m) { $byName[$m->methodIdentity] = $m; }
        self::assertSame(['data'], array_map(fn ($s) => $s->propertyName, $byName['fetch']->stateAccesses));

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([['fetch', 'sync_method', 'state']], $observed[0]['edges'], 'now correctly connected via shared state');
        self::assertSame(1, $observed[0]['value']);
    }

    /** Test B: a sync method's call to an async sibling now correctly resolves, no longer wrongly excluded. */
    public function test_b_sync_call_to_async_sibling_now_correctly_connects(): void
    {
        $python = <<<'PY'
            class A:
                def sync_caller(self):
                    return self.async_worker()

                async def async_worker(self):
                    return 1
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([], $observed[0]['excluded'], 'was excluded as TargetNotDeclaredHere pre-fix');
        self::assertSame([['sync_caller', 'async_worker', 'behaviour']], $observed[0]['edges']);
        self::assertSame(1, $observed[0]['value']);
    }

    /** Falsification: an async method decorated with @property is still classified via the property mechanism, not broken by the type change. */
    public function test_c_async_method_with_property_decorator_still_classified_correctly(): void
    {
        $python = <<<'PY'
            class A:
                @property
                async def value(self):
                    return self._value
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        self::assertCount(1, $facts->units[0]->methods);
        self::assertSame(MethodRole::Ordinary, $facts->units[0]->methods[0]->methodRole);
    }

    /** Falsification: `await self.other()` inside an async method resolves as an ordinary call, not specially or incorrectly. */
    public function test_d_await_expression_is_an_ordinary_call_inside_an_async_method(): void
    {
        $python = <<<'PY'
            class A:
                async def caller(self):
                    return await self.other()

                async def other(self):
                    return 1
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        self::assertSame([['caller', 'other', 'behaviour']], $observed[0]['edges']);
        self::assertSame(1, $observed[0]['value']);
    }

    /** Regression guard: a class with no async methods at all is completely unaffected. */
    public function test_e_regression_ordinary_class_unaffected(): void
    {
        $python = "class A:\n    def __init__(self):\n        self.x = 1\n\n    def f(self):\n        return self.x\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        self::assertCount(2, $facts->units[0]->methods);
        self::assertSame(MethodRole::Lifecycle, $facts->units[0]->methods[0]->methodRole);
    }
}
