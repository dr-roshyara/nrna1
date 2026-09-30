<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-2 -- Python-native property getter/setter identity investigation (2026-09-27).
 * ORIGINALLY characterization only; now HISTORICAL -- the finding this file recorded
 * was corrected (OWD-3, `GraphBuilder` node identity keyed by occurrence, not bare
 * name; full RED/GREEN proof in `PropertyIdentityCorrectionTest.php`). Preserved with
 * updated assertions rather than deleted, per this investigation's standing
 * disclosure convention.
 *
 * Original finding (unchanged historical record): `Lcom4::compute()`'s
 * `array_combine($nodes, $nodes)` silently collapsed nodes sharing a name into one
 * union-find key, and `GraphBuilder`'s name-keyed `$propertyOwner` tracking silently
 * treated a same-named method's own state touch as "already owned by itself" -- neither
 * was a deliberate design choice (undocumented anywhere), both were undocumented
 * invariants that held for free on any PHP input (duplicate method names are a PHP
 * fatal error) and were never exercised before Python's `@property`/`@x.setter` idiom.
 * See `2026-09-27-KOS-PYTHON-OWD2-property-identity.md` for the full pre-fix analysis.
 */
final class PropertyIdentityCharacterizationTest extends TestCase
{
    /** Fixture 3 -- the decisive case: getter/setter share ZERO state and produce ZERO edges, yet LCOM4 = 1, not 2. */
    public function test_getter_and_setter_with_disjoint_state_are_still_forced_into_one_component(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self._a

                @value.setter
                def value(self, v):
                    self._b = v
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([], $observed[0]['edges'], 'no real edge connects them');
        self::assertSame(2, $observed[0]['value'], 'CORRECTED (OWD-3): was 1 pre-fix -- two genuinely unrelated bodies now correctly separate');
    }

    /** Fixture 6 -- two entirely empty same-named nodes still collapse into one component with no edge. */
    public function test_two_empty_same_named_nodes_collapse_into_one_component(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return 1

                @value.setter
                def value(self, v):
                    pass

                def unrelated(self):
                    return self._z
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame([], $observed[0]['edges']);
        self::assertSame(3, $observed[0]['value'], 'CORRECTED (OWD-3): was 2 pre-fix -- three genuinely independent bodies now correctly separate');
    }

    /** Fixture 7 -- a third method sharing the backing field connects to only ONE edge, not two: the setter's own touch is suppressed by the name-keyed ownership check. */
    public function test_third_method_sharing_the_backing_field_produces_only_one_edge_not_two(): void
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
                    return self._value
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertCount(2, $observed[0]['edges'], 'CORRECTED (OWD-3): was 1 pre-fix -- the setters own claim is no longer silently absorbed');
        self::assertSame([['value#0', 'value#1', 'state'], ['value#0', 'other', 'state']], $observed[0]['edges']);
    }
}
