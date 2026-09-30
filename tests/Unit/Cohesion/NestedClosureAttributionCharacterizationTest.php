<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-5 -- nested closure attribution (2026-09-27). ORIGINALLY characterization only;
 * now HISTORICAL -- the finding this file recorded was corrected (both adapters now
 * prune nested `def`/`async def`/`lambda`/`function(){}` scopes; full RED/GREEN proof
 * in `NestedClosureAttributionCorrectionTest.php`). Preserved with updated assertions
 * rather than deleted, per this investigation's standing disclosure convention.
 *
 * Original finding (unchanged historical record): no pinned decision anywhere
 * addressed nested functions/closures. Grounded in REAL corpus evidence
 * (`contextlib.ContextDecorator.__call__` and five other stdlib examples, OWD-1
 * census): the dominant real-world shape is "define a closure, return it, never call it
 * here" (a decorator/wrapper factory) -- the enclosing method's OWN direct execution
 * never touched what the closure touched. CONFIRMED SYMMETRIC, not Python-specific:
 * both `extract_facts.py` (`ast.walk` recursing into nested `FunctionDef`/`Lambda`) and
 * `PhpFactExtractor` (a flat token scan over the enclosing method's brace-matched
 * range) misattributed the closure's own `self`/`$this` references to the OUTER
 * method. Classification: E (shared adapter-level limitation).
 *
 * See `2026-09-27-KOS-PYTHON-OWD5-nested-closure-attribution.md` for the full pre-fix
 * analysis.
 */
final class NestedClosureAttributionCharacterizationTest extends TestCase
{
    /**
     * Materiality, modeled directly on the real `contextlib.ContextDecorator.__call__`
     * shape, both languages: `__call__` defines and returns a closure touching
     * `_recreate_cm`, but never calls it itself. The current LCOM4 (2) is WRONG under
     * the "own declared body" principle -- the correct value, if the closure's
     * reference were NOT attributed to `__call__`, would be 3 (three genuinely
     * independent methods, none of which touch anything in their own direct
     * execution).
     */
    public function test_deferred_closure_reference_produces_a_material_false_edge_both_languages(): void
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

        self::assertSame(
            [],
            $phpObserved[0]['edges'],
            'CORRECTED (OWD-5 GREEN): __call__ never calls _recreate_cm() in its own direct execution -- no edge now'
        );
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);
        self::assertSame(3, $phpObserved[0]['value'], 'CORRECTED: was 2 pre-fix -- three genuinely independent methods');
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }
}
