<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-DESCRIPTOR-EXPERIMENT (2026-09-27). Research question,
 * sharpened per review: not "does Python support descriptors" but "can descriptor-backed
 * attribute access be represented using the existing CONSUMED L3 contract without losing
 * information relevant to the current cohesion analysis?" No PHP analogue attempted --
 * PHP has no direct equivalent to `__get__`-based descriptors, and forcing one would not
 * be meaningful (consistent with the diamond/MRO experiment's own discipline).
 *
 * Verified separately, by real execution (not asserted): `Descriptor.__get__` genuinely
 * fires and can run arbitrary code (`print` + return, confirmed to execute) when
 * `self.x` is read on an instance of `A` where `x = Descriptor()` at the class body level.
 */
final class PythonDescriptorExperimentTest extends TestCase
{
    private const ORDINARY = <<<'PY'
        class A:
            def f(self):
                return self.x

            def g(self):
                return self.x
        PY;

    private const DESCRIPTOR_BACKED = <<<'PY'
        class Descriptor:
            def __get__(self, obj, owner):
                return 42

        class A:
            x = Descriptor()

            def f(self):
                return self.x

            def g(self):
                return self.x
        PY;

    /**
     * The current bounded adapter does not track class-body-level attribute assignments
     * at all (it only walks `ast.FunctionDef` bodies) -- it therefore cannot and does not
     * distinguish "self.x reads a plain instance slot" from "self.x invokes Descriptor.__get__".
     * Both produce StateAccess('x') for A's methods. This test proves that choice has NO
     * observable effect on anything the current L4/L5 pipeline consumes: both fixtures
     * produce a BYTE-IDENTICAL observation for unit A.
     */
    public function test_descriptor_backed_and_ordinary_attribute_access_produce_identical_observation_for_unit_a(): void
    {
        $provider = new PythonSemanticFactProvider();

        $ordinary = AnalyseCohesion::observe($provider->extract(self::ORDINARY));
        $descriptorBacked = AnalyseCohesion::observe($provider->extract(self::DESCRIPTOR_BACKED));

        $unitAOrdinary = self::unitNamed($ordinary, 'A');
        $unitADescriptor = self::unitNamed($descriptorBacked, 'A');

        self::assertSame($unitAOrdinary, $unitADescriptor);
        self::assertSame([['f', 'g', 'state']], $unitAOrdinary['edges']);
        self::assertSame(1, $unitAOrdinary['value']);
    }

    /** The Descriptor class itself is analysed as its own, independent unit -- unsurprising, but confirmed. */
    public function test_the_descriptor_class_is_analysed_as_its_own_independent_unit(): void
    {
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract(self::DESCRIPTOR_BACKED));
        $descriptorUnit = self::unitNamed($observed, 'Descriptor');

        self::assertSame(['__get__'], $descriptorUnit['nodes']);
        self::assertSame(1, $descriptorUnit['value']);
    }

    private static function unitNamed(array $observed, string $name): array
    {
        foreach ($observed as $unit) {
            if ($unit['unit'] === $name) {
                return $unit;
            }
        }

        throw new \RuntimeException("Unit {$name} not found");
    }
}
