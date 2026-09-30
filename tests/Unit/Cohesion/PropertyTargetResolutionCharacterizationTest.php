<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-4 -- property target resolution (Q2), 2026-09-27. ORIGINALLY characterization
 * only; now HISTORICAL -- corrected as part of the combined OWD-4/OWD-7 fix
 * (2026-09-28), full RED/GREEN proof in `PropertyTargetPrecisionCorrectionTest.php`.
 * Preserved with updated assertions rather than deleted, per this investigation's
 * standing disclosure convention.
 *
 * Original finding (unchanged historical record): Python's descriptor protocol is
 * fully deterministic -- a plain read (`self.value`) invokes only `fget`; an
 * assignment (`self.value = x`) invokes only `fset`. The adapter did not yet track
 * read (Load) vs. write (Store) context, so OWD-3's "connect to every candidate"
 * fallback produced factually FALSE edges. See
 * `2026-09-27-KOS-PYTHON-OWD4-property-target-resolution.md` for the full pre-fix
 * analysis.
 */
final class PropertyTargetResolutionCharacterizationTest extends TestCase
{
    /** A plain READ of a colliding property name wrongly connects to the setter too -- a read never invokes fset. */
    public function test_a_read_of_the_property_wrongly_connects_to_the_setter_as_well(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self._value

                @value.setter
                def value(self, v):
                    self._value = v

                def reader(self):
                    return self.value
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        $readerEdges = array_values(array_filter($observed[0]['edges'], static fn (array $e): bool => $e[0] === 'reader'));
        self::assertSame([['reader', 'value#0', 'behaviour']], $readerEdges, 'CORRECTED: only the getter, not the setter too');
    }

    /** A plain WRITE to a colliding property name wrongly connects to the getter too -- a write never invokes fget. */
    public function test_a_write_to_the_property_wrongly_connects_to_the_getter_as_well(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self._value

                @value.setter
                def value(self, v):
                    self._value = v

                def writer(self):
                    self.value = 5
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        $writerEdges = array_values(array_filter($observed[0]['edges'], static fn (array $e): bool => $e[0] === 'writer'));
        self::assertSame([['writer', 'value#1', 'behaviour']], $writerEdges, 'CORRECTED: only the setter, not the getter too');
    }

    /** Regression/control: outgoing calls FROM the getter/setter's own bodies are never ambiguous -- only incoming references to a colliding name are affected. */
    public function test_outgoing_calls_from_getter_and_setter_are_never_ambiguous(): void
    {
        $python = <<<'PY'
            class A:
                @property
                def value(self):
                    return self.helper_g()

                @value.setter
                def value(self, v):
                    self.helper_s()

                def helper_g(self):
                    return 1

                def helper_s(self):
                    pass
            PY;
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        self::assertSame(
            [['value#0', 'helper_g', 'behaviour'], ['value#1', 'helper_s', 'behaviour']],
            $observed[0]['edges'],
        );
    }
}
