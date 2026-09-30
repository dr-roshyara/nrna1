<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\TestCase;

/**
 * OWD-10 -- GREEN slice (2026-09-28). Characterization + classification E already
 * recorded in `...-OWD10-arrow-function-attribution.md`. RED/GREEN proof for the
 * authorized fix: `PhpFactExtractor::factsIn()` now skips a `fn() => expr` arrow
 * function's entire body, mirroring OWD-5's `T_FUNCTION` handling in intent, but using
 * a different algorithm since an arrow function's body is a single EXPRESSION with no
 * matching braces -- the end is found by tracking bracket/paren depth and stopping at
 * the first comma/semicolon/closing-bracket that belongs to an OUTER context.
 */
final class ArrowFunctionAttributionCorrectionTest extends TestCase
{
    /** Test A: the real Election.php-modeled fixture now converges to the correct LCOM4. */
    public function test_a_arrow_function_in_a_statement_no_longer_misattributes(): void
    {
        $php = <<<'PHP'
            <?php
            class Election {
                private $data;
                public function scopeBase() {
                    $base = fn () => $this->memberships()->withoutGlobalScopes();
                    return $base;
                }
                public function unrelated() {
                    return $this->data;
                }
                public function memberships() { return 1; }
            }
            PHP;

        $facts = PhpFactExtractor::extract($php);
        $scopeBase = $facts->units[0]->methods[0];
        self::assertSame('scopeBase', $scopeBase->methodIdentity);
        self::assertSame([], $scopeBase->behaviourReferences, 'scopeBase itself calls nothing directly');
        self::assertSame([], $scopeBase->stateAccesses);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([], $observed[0]['edges']);
        self::assertSame(3, $observed[0]['value'], 'was 2 pre-fix -- three genuinely independent methods');
    }

    /** Test B: an arrow function passed directly as a call argument (array_map(fn($x) => ..., $arr)) -- body ends at the comma. */
    public function test_b_arrow_function_as_a_call_argument_ends_at_the_comma(): void
    {
        $php = '<?php class A { private $data; function f() { return array_map(fn($x) => $this->helper($x), [1,2,3]); } function helper($x) { return $x; } function unrelated() { return $this->data; } }';
        $facts = PhpFactExtractor::extract($php);
        $f = $facts->units[0]->methods[0];
        self::assertSame('f', $f->methodIdentity);
        self::assertSame([], $f->behaviourReferences, 'f itself never calls helper() directly -- only the arrow function does, if invoked');
    }

    /** Test C: an arrow function with an explicit return-type declaration (fn(): int => ...) is still skipped correctly. */
    public function test_c_arrow_function_with_return_type_declaration(): void
    {
        $php = '<?php class A { function f() { $g = fn(): int => $this->x; return $g; } function unrelated() { return $this->x; } }';
        $f = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame([], $f->stateAccesses, 'f itself never touches $this->x directly -- only the arrow function does');
    }

    /** Test D: a nested arrow function (fn returning fn) is fully skipped by the outer skip, not double-processed. */
    public function test_d_nested_arrow_functions_fully_skipped(): void
    {
        $php = '<?php class A { function f() { $g = fn($x) => fn($y) => $this->combine($x, $y); return $g; } function combine($x, $y) { return $x + $y; } function unrelated() { return 1; } }';
        $f = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame('f', $f->methodIdentity);
        self::assertSame([], $f->behaviourReferences);
    }

    /** Test E: an arrow function as the LAST statement in a method body (no trailing comma/semicolon issue at the boundary). */
    public function test_e_arrow_function_as_the_final_expression_in_a_return_statement(): void
    {
        $php = '<?php class A { private $data; function f() { return fn() => $this->data; } function unrelated() { return 1; } }';
        $f = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame([], $f->stateAccesses);
    }

    /** Falsification/regression: ordinary code with no arrow function at all is completely unaffected. */
    public function test_f_regression_ordinary_method_unaffected(): void
    {
        $php = '<?php class A { private $x; function f() { return $this->x; } function g() { return $this->x; } }';
        $observed = AnalyseCohesion::observe(PhpFactExtractor::extract($php));
        self::assertSame([['f', 'g', 'state']], $observed[0]['edges']);
        self::assertSame(1, $observed[0]['value']);
    }

    /** Falsification: a regular (non-arrow) closure inside the same method is still handled by OWD-5's own mechanism, unaffected by this change. */
    public function test_g_regular_closure_still_handled_by_owd5(): void
    {
        $php = '<?php class A { function f() { return function() { return $this->y; }; } function g() { return $this->x; } }';
        $f = PhpFactExtractor::extract($php)->units[0]->methods[0];
        self::assertSame([], $f->stateAccesses);
    }
}
