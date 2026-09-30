<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\TestCase;

/**
 * OWD-10 -- PHP arrow function (`fn() => expr`) attribution (2026-09-28). ORIGINALLY
 * characterization only; now HISTORICAL -- corrected (full RED/GREEN proof in
 * `ArrowFunctionAttributionCorrectionTest.php`). Preserved with updated assertions
 * rather than deleted, per this investigation's standing disclosure convention.
 *
 * Original finding (unchanged historical record): PHP arrow functions (7.4+)
 * auto-capture the enclosing scope BY VALUE, including `$this`, without an explicit
 * `use()` clause -- otherwise the same closure semantics OWD-5 already characterized
 * and fixed for `function(){}`. Grounded in REAL corpus evidence, this project's own
 * actual application code: `grep` of `app/` found 298 arrow-function occurrences, 57
 * referencing `$this->` on the same line -- e.g. `app/Models/Election.php:389:
 * $base = fn () => $this->memberships()->withoutGlobalScopes();`.
 *
 * See `2026-09-28-KOS-PHP-OWD10-arrow-function-attribution.md` for the full pre-fix
 * analysis.
 */
final class ArrowFunctionAttributionCharacterizationTest extends TestCase
{
    /** Materiality, modeled directly on the real app/Models/Election.php:389 shape. */
    public function test_arrow_function_self_reference_is_misattributed_to_the_outer_method(): void
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
        $byName = [];
        foreach ($facts->units[0]->methods as $m) {
            $byName[$m->methodIdentity] = $m;
        }

        // CORRECTED: scopeBase itself never calls memberships() in its own direct
        // execution -- only the arrow function it constructs and returns does, if and
        // when it is later invoked -- and now correctly shows zero facts of its own.
        self::assertSame([], $byName['scopeBase']->behaviourReferences);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([], $observed[0]['edges'], 'CORRECTED: scopeBase is no longer wrongly connected to memberships');
        self::assertSame(3, $observed[0]['value'], 'CORRECTED: was 2 pre-fix');
    }
}
