<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-7 -- `property(fget, fset)` call-form idiom (2026-09-27). ORIGINALLY
 * characterization only; now HISTORICAL -- corrected as part of the combined
 * OWD-4/OWD-7 fix (2026-09-28), full RED/GREEN proof in
 * `PropertyTargetPrecisionCorrectionTest.php`. Preserved with updated assertions
 * rather than deleted, per this investigation's standing disclosure convention.
 *
 * Original finding (unchanged historical record): `NAME = property(getter, setter)`
 * constructs a property descriptor identically, at runtime, to the
 * `@property`/`@x.setter` decorator sugar. Grounded in REAL corpus evidence, not
 * assumed rare: found within the SAME 153-file top-level stdlib corpus used throughout
 * this investigation -- `calendar.Calendar.firstweekday = property(getfirstweekday,
 * setfirstweekday)`, `enum.py`'s `redirect = property()`, and monkey-patched cases in
 * `ast.py`.
 *
 * See `2026-09-27-KOS-PYTHON-OWD7-property-call-form.md` for the full pre-fix
 * analysis.
 */
final class PropertyCallFormCharacterizationTest extends TestCase
{
    /** Materiality, modeled directly on the real calendar.Calendar shape. */
    public function test_property_call_form_produces_a_material_false_isolation(): void
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

        $facts = (new PythonSemanticFactProvider())->extract($python);
        $byName = [];
        foreach ($facts->units[0]->methods as $m) {
            $byName[$m->methodIdentity] = $m;
        }

        // CORRECTED: iterweekdays' reads of self.firstweekday now resolve to a real
        // BehaviourReference targeting getfirstweekday, not a disconnected StateAccess.
        self::assertSame([], $byName['iterweekdays']->stateAccesses);
        self::assertCount(2, $byName['iterweekdays']->behaviourReferences, 'two read sites in the source, both targeting the getter');

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(
            [
                ['getfirstweekday', 'setfirstweekday', 'state'],
                ['iterweekdays', 'getfirstweekday', 'behaviour'],
                ['iterweekdays', 'getfirstweekday', 'behaviour'],
            ],
            $observed[0]['edges'],
            'CORRECTED: iterweekdays now correctly connects to the getter/setter pair it depends on'
        );
        self::assertSame(1, $observed[0]['value'], 'CORRECTED: was 2 pre-fix');
    }
}
