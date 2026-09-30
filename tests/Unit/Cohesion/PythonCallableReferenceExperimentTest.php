<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-CALLABLE-REFERENCE-EXPERIMENT (2026-09-27). Opposite
 * prediction direction from the @property experiment: SIMILAR surface syntax
 * (`self.method` vs `self.method()`), DIFFERENT semantics, must produce DIFFERENT
 * canonical facts -- proving the adapter does not collapse invocation and reference
 * merely because both start with `self.`.
 */
final class PythonCallableReferenceExperimentTest extends TestCase
{
    private function observePython(string $source): array
    {
        return AnalyseCohesion::observeFromSource(new PythonSemanticFactProvider(), $source);
    }

    private function observePhp(string $source): array
    {
        return AnalyseCohesion::observe(PhpFactExtractor::extract($source));
    }

    public function test_case_a_explicit_invocation_matches_php(): void
    {
        $python = "class Example:\n    def method(self):\n        pass\n\n    def f(self):\n        self.method()\n";
        $php = '<?php class Example { public function method() {} public function f() { $this->method(); } }';

        $observed = $this->observePython($python);
        self::assertSame($this->observePhp($php), $observed);
        self::assertSame([['f', 'method', 'behaviour']], $observed[0]['edges']);
    }

    public function test_case_b_callable_reference_without_invocation_matches_php_first_class_callable(): void
    {
        $python = "class Example:\n    def method(self):\n        pass\n\n    def f(self):\n        callback = self.method\n";
        $php = '<?php class Example { public function method() {} public function f() { $callback = $this->method(...); } }';

        $observed = $this->observePython($python);
        self::assertSame($this->observePhp($php), $observed);
        // A callable reference must be EXCLUDED (CallableNotInvocation), never a graph edge --
        // referencing a method is not depending on its behaviour.
        self::assertSame([], $observed[0]['edges']);
        self::assertSame('CallableNotInvocation', $observed[0]['excluded'][0]['reason']);
    }

    public function test_case_c_ordinary_attribute_with_no_matching_method_stays_state_access(): void
    {
        $python = "class Example:\n    def f(self):\n        value = self.value\n";
        $php = '<?php class Example { public function f() { $value = $this->value; } }';

        $observed = $this->observePython($python);
        self::assertSame($this->observePhp($php), $observed);
        self::assertSame([], $observed[0]['edges']);
        self::assertSame([], $observed[0]['excluded']);
    }

    /** Metamorphic: near-identical syntax, opposite semantics -- must NOT canonicalize the same. */
    public function test_invocation_and_callable_reference_do_not_canonicalize_identically(): void
    {
        $invocation = "class Example:\n    def method(self):\n        pass\n\n    def f(self):\n        self.method()\n";
        $reference = "class Example:\n    def method(self):\n        pass\n\n    def f(self):\n        callback = self.method\n";

        self::assertNotSame($this->observePython($invocation), $this->observePython($reference));
    }
}
