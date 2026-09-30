<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-PROPERTY-EXPERIMENT (2026-09-27) -- semantic-neutrality
 * falsification experiment, not a general Python adapter. Exactly three cases: an
 * @property-backed attribute read, an explicit method invocation, and a plain instance
 * attribute read. Compares representation (the full observation, which carries L3-derived
 * nodes/edges/excluded) BEFORE accepting metric equality alone -- a matching LCOM4 with a
 * mismatching edge set would be reported as a failure here, not smoothed over.
 */
final class PythonPropertySemanticExperimentTest extends TestCase
{
    private function observePython(string $source): array
    {
        return AnalyseCohesion::observeFromSource(new PythonSemanticFactProvider(), $source);
    }

    private function observePhp(string $source): array
    {
        return AnalyseCohesion::observe(PhpFactExtractor::extract($source));
    }

    /**
     * Case A: Python's @property makes `self.value` (no parens) a real method
     * invocation, dressed as attribute syntax. The correct canonical semantics is
     * IDENTICAL to the PHP fixture where `f()` explicitly calls `value()`.
     */
    public function test_property_backed_attribute_read_matches_explicit_php_invocation(): void
    {
        $python = "class Example:\n    @property\n    def value(self):\n        return self._value\n\n    def f(self):\n        return self.value\n";
        $php = '<?php class Example { public function value() { return $this->_value; } public function f() { return $this->value(); } }';

        $pythonObserved = $this->observePython($python);
        $phpObserved = $this->observePhp($php);

        self::assertSame($phpObserved, $pythonObserved);
        // Representation-level assertions, not metric-only (the standing methodological rule):
        self::assertSame([['f', 'value', 'behaviour']], $pythonObserved[0]['edges']);
        self::assertSame(1, $pythonObserved[0]['value']);
    }

    /**
     * MR-1 (metamorphic): the SAME semantics expressed via an explicit method + explicit
     * call (no @property at all) must canonicalize identically to Case A. Different Python
     * surface syntax, same meaning, same facts -- exactly the invariant this whole
     * investigation has been testing for, now proven for Python-to-Python as well as
     * Python-to-PHP.
     */
    public function test_explicit_invocation_form_is_canonically_identical_to_the_property_form(): void
    {
        $propertyForm = "class Example:\n    @property\n    def value(self):\n        return self._value\n\n    def f(self):\n        return self.value\n";
        $explicitForm = "class Example:\n    def value(self):\n        return self._value\n\n    def f(self):\n        return self.value()\n";

        self::assertSame($this->observePython($propertyForm), $this->observePython($explicitForm));
    }

    /**
     * Case C, control: no property exists at all -- `self._value` in `f` must remain a
     * plain state access, never promoted to a behaviour reference merely because it looks
     * similar to Case A's `self.value`. Proves the property/attribute distinction is
     * genuinely name-sensitive, not applied indiscriminately to every `self.X`.
     */
    public function test_plain_attribute_read_with_no_property_stays_state_access(): void
    {
        $python = "class Example:\n    def f(self):\n        return self._value\n";
        $php = '<?php class Example { public function f() { return $this->_value; } }';

        $pythonObserved = $this->observePython($python);

        self::assertSame($this->observePhp($php), $pythonObserved);
        self::assertSame([], $pythonObserved[0]['edges']);
        self::assertSame([], $pythonObserved[0]['excluded']);
    }
}
