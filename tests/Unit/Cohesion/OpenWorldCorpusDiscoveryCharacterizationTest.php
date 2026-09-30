<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-1 -- Open-World Python Corpus Discovery (2026-09-27). Characterization only. NO
 * production code touched by this file. Preserves, as executable evidence, the two
 * candidate semantic omissions the CPython 3.13.2 stdlib census surfaced (report:
 * `2026-09-27-KOS-PYTHON-OPEN-WORLD-CORPUS-DISCOVERY.md`). Neither is classified or
 * corrected here -- both are recorded as observed, real-corpus-motivated behaviour.
 */
final class OpenWorldCorpusDiscoveryCharacterizationTest extends TestCase
{
    /**
     * CANDIDATE OMISSION 1 (HIGH priority in the census report), CORRECTED (OWD-5,
     * 2026-09-27, full RED/GREEN proof in `NestedClosureAttributionCorrectionTest.php`):
     * a nested function/lambda inside a method was walked by `ast.walk(m)` along with
     * the method itself -- its own `self.`/`cls.` references were indistinguishable
     * from the outer method's. Affected ~2.2% of methods (99/4483) across the real
     * corpus census. `f` never touches `self` directly and now correctly shows zero
     * facts of its own; the nested closure `inner`'s reference to `self.y` is no longer
     * misattributed.
     */
    public function test_nested_closure_self_reference_is_misattributed_to_the_outer_method(): void
    {
        $python = <<<'PY'
            class A:
                def f(self):
                    def inner():
                        return self.y
                    return inner

                def g(self):
                    return self.x
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $f = $facts->units[0]->methods[0];

        self::assertSame('f', $f->methodIdentity);
        self::assertSame([], $f->stateAccesses, 'CORRECTED: f now correctly shows zero facts of its own');
    }

    /**
     * CANDIDATE OMISSION 2, ORIGINALLY: a `@property` getter and its `@x.setter` share
     * the same Python method NAME by language design -- something a PHP class cannot
     * express (duplicate method names are a PHP syntax error). The extractor emits TWO
     * separate `MethodFacts` entries with the IDENTICAL `methodIdentity` -- still true,
     * unchanged below, and correctly so (OWD-3: a node IS a declared method; two
     * declared methods sharing a name are still two methods).
     *
     * HISTORY, preserved rather than erased: this test originally asserted the pre-fix
     * `GraphBuilder` behaviour -- a literal duplicate node label (`['value','value',
     * 'other']`), traced through in full in `2026-09-27-KOS-PYTHON-OWD2-property-
     * identity.md` and resolved in `...-OWD3-identity-model-resolution.md` (Model A,
     * connectivity keyed by occurrence, not bare name). The GREEN correction is proven
     * in `PropertyIdentityCorrectionTest.php`; this test now asserts the corrected,
     * disambiguated label.
     */
    public function test_property_getter_and_setter_produce_duplicate_node_identity(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self._value

                @value.setter
                def value(self, v):
                    self._value = v

                def other(self):
                    return self.value
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);

        self::assertCount(3, $facts->units[0]->methods, 'getter and setter are two separate method entries, both named "value"');
        self::assertSame('value', $facts->units[0]->methods[0]->methodIdentity);
        self::assertSame('value', $facts->units[0]->methods[1]->methodIdentity);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['value#0', 'value#1', 'other'], $observed[0]['nodes'], 'disambiguated by occurrence now that they collide');
    }
}
