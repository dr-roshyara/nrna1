<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Combined OWD-4/OWD-7 correction (2026-09-28). Characterization already recorded in
 * `...-OWD4-property-target-resolution.md` and `...-OWD7-property-call-form.md`;
 * `...-CANONICAL-L3-SUFFICIENCY-AUDIT.md` established the two are linked (same
 * read/write axis). RED/GREEN proof for the authorized, unified fix: the Python
 * adapter now resolves BOTH the decorator idiom (`@property`/`@x.setter`) and the
 * call-form idiom (`NAME = property(getter, setter)`) to the SPECIFIC underlying
 * method a read or write actually invokes -- no more "connect to every candidate"
 * fallback for the decorator collision case, and the call-form idiom is now
 * recognised at all. Zero `GraphBuilder`/`Lcom4` changes required: the decorator
 * idiom's occurrence numbering (Python's own syntax guarantees getter always precedes
 * setter) exactly matches OWD-3's existing `name#0`/`name#1` disambiguation scheme,
 * computed independently on the PHP side from the same declaration order.
 */
final class PropertyTargetPrecisionCorrectionTest extends TestCase
{
    /** OWD-7 primary case: the real calendar.Calendar shape now converges to the correct LCOM4. */
    public function test_a_call_form_property_now_resolves_reads_to_the_real_getter(): void
    {
        $python = <<<'PY'
            class Calendar:
                def __init__(self):
                    self.firstweekday = 0

                def getfirstweekday(self):
                    return self._firstweekday % 7

                def setfirstweekday(self, firstweekday):
                    self._firstweekday = firstweekday

                firstweekday = property(getfirstweekday, setfirstweekday)

                def iterweekdays(self):
                    for i in range(self.firstweekday, self.firstweekday + 7):
                        yield i % 7
            PY;

        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));

        // iterweekdays reads self.firstweekday TWICE in its own source
        // (range(self.firstweekday, self.firstweekday + 7)) -- two distinct read
        // sites, two behaviour references, two edges. GraphBuilder has never
        // deduplicated repeated identical references; this is pre-existing behaviour,
        // not introduced by this fix, and does not affect connectivity.
        self::assertSame(
            [
                ['getfirstweekday', 'setfirstweekday', 'state'],
                ['iterweekdays', 'getfirstweekday', 'behaviour'],
                ['iterweekdays', 'getfirstweekday', 'behaviour'],
            ],
            $observed[0]['edges'],
        );
        self::assertSame(1, $observed[0]['value'], 'was 2 pre-fix -- all three now correctly connected');
    }

    /** OWD-4 precision: a decorator-form READ resolves ONLY to the getter occurrence, not both. */
    public function test_b_decorator_property_read_resolves_only_to_the_getter(): void
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
        self::assertSame([['reader', 'value#0', 'behaviour']], $readerEdges, 'exactly the getter, not the setter too');
    }

    /** OWD-4 precision: a decorator-form WRITE resolves ONLY to the setter occurrence, not both. */
    public function test_c_decorator_property_write_resolves_only_to_the_setter(): void
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
        self::assertSame([['writer', 'value#1', 'behaviour']], $writerEdges, 'exactly the setter, not the getter too');
    }

    /** Regression: a getter-only decorator property, read, still resolves with a bare (non-suffixed) label -- no collision, no change. */
    public function test_d_getter_only_property_read_unaffected(): void
    {
        $python = "class A:\n    @property\n    def value(self):\n        return self._value\n\n    def reader(self):\n        return self.value\n";
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        self::assertSame([['reader', 'value', 'behaviour']], $observed[0]['edges']);
    }

    /**
     * Falsification: writing to a getter-only property (no setter exists) is not valid
     * Python at runtime (raises AttributeError) -- no fact is emitted, rather than
     * wrongly connecting the write to the getter (the pre-fix behaviour).
     */
    public function test_e_write_to_a_getter_only_property_emits_no_fact(): void
    {
        $python = "class A:\n    @property\n    def value(self):\n        return self._value\n\n    def writer(self):\n        self.value = 5\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $writer = $facts->units[0]->methods[1];
        self::assertSame('writer', $writer->methodIdentity);
        self::assertSame([], $writer->behaviourReferences);
        self::assertSame([], $writer->stateAccesses);
    }

    /** Falsification: a call-form property with only fget (no fset) -- write emits no fact, read still resolves. */
    public function test_f_call_form_getter_only_write_emits_no_fact(): void
    {
        $python = <<<'PY'
            class A:
                def get_v(self):
                    return self._v

                v = property(get_v)

                def reader(self):
                    return self.v

                def writer(self):
                    self.v = 1
            PY;
        $facts = (new PythonSemanticFactProvider())->extract($python);
        $byName = [];
        foreach ($facts->units[0]->methods as $m) { $byName[$m->methodIdentity] = $m; }

        self::assertNotSame([], $byName['reader']->behaviourReferences);
        self::assertSame('get_v', $byName['reader']->behaviourReferences[0]->targetMethodName);
        self::assertSame([], $byName['writer']->behaviourReferences);
        self::assertSame([], $byName['writer']->stateAccesses);
    }

    /** Falsification: a lambda-valued call-form property remains unrecognised (deliberately excluded scope). */
    public function test_g_lambda_valued_call_form_remains_unrecognised(): void
    {
        $python = "class A:\n    v = property(fget=lambda self: self._v)\n\n    def reader(self):\n        return self.v\n";
        $reader = (new PythonSemanticFactProvider())->extract($python)->units[0]->methods[0];
        self::assertSame([], $reader->behaviourReferences);
        self::assertSame([['propertyName' => 'v', 'accessMode' => 'Direct']], array_map(
            fn ($s) => ['propertyName' => $s->propertyName, 'accessMode' => $s->accessMode->name],
            $reader->stateAccesses,
        ));
    }

    /** Regression: ordinary (non-property) instance attribute read/write completely unaffected. */
    public function test_h_ordinary_attribute_unaffected(): void
    {
        $python = "class A:\n    def __init__(self):\n        self.x = 1\n\n    def f(self):\n        return self.x\n\n    def g(self):\n        self.x = 2\n";
        $facts = (new PythonSemanticFactProvider())->extract($python);
        foreach ($facts->units[0]->methods as $m) {
            if (in_array($m->methodIdentity, ['f', 'g'], true)) {
                self::assertCount(1, $m->stateAccesses, $m->methodIdentity);
                self::assertSame('x', $m->stateAccesses[0]->propertyName);
            }
        }
    }
}
