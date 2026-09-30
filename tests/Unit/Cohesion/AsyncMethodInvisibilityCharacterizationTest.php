<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-13 -- async method invisibility (2026-09-28). ORIGINALLY characterization only;
 * now HISTORICAL -- corrected (full RED/GREEN proof in
 * `AsyncMethodVisibilityCorrectionTest.php`). Preserved with updated assertions rather
 * than deleted, per this investigation's standing disclosure convention.
 *
 * Original finding (unchanged historical record): discovered via a Python-corpus
 * expansion beyond OWD-1's top-level-only scope. `extract_facts.py`'s `method_defs`
 * filter was `isinstance(n, ast.FunctionDef)` -- it never matched
 * `ast.AsyncFunctionDef`. Every `async def` method inside a class was invisible to the
 * adapter entirely. Real-corpus signal: `asyncio/base_events.py` (2,065 lines)
 * reported 100 methods by direct AST count but only 71 via the adapter -- 29 real
 * methods silently missing from a single real file. More foundational than any prior
 * finding in this investigation: a wholesale category exclusion, not a construct
 * nuance.
 *
 * See `2026-09-28-KOS-PYTHON-OWD13-async-method-invisibility.md` for the full pre-fix
 * analysis.
 */
final class AsyncMethodInvisibilityCharacterizationTest extends TestCase
{
    /** Case 1, CORRECTED: an async method touching self is now correctly visible in the extracted facts. */
    public function test_isolated_async_method_is_entirely_invisible(): void
    {
        $python = <<<'PY'
            class A:
                async def fetch(self):
                    return self.data

                def sync_method(self):
                    return self.data
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);

        self::assertCount(2, $facts->units[0]->methods, 'CORRECTED: was 1 pre-fix -- fetch now correctly visible');
    }

    /** Case 2, CORRECTED: a sync method's legitimate call to an async sibling now correctly connects. */
    public function test_sync_call_to_async_sibling_is_wrongly_excluded(): void
    {
        $python = <<<'PY'
            class A:
                def sync_caller(self):
                    return self.async_worker()

                async def async_worker(self):
                    return 1
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([], $observed[0]['excluded'], 'CORRECTED: was wrongly excluded as TargetNotDeclaredHere pre-fix');
        self::assertSame([['sync_caller', 'async_worker', 'behaviour']], $observed[0]['edges']);
        self::assertSame(1, $observed[0]['value']);
    }
}
