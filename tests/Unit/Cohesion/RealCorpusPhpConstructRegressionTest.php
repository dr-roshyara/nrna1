<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\TestCase;

/**
 * OWD-12 -- audit of the real PHP corpus (`app/`, this project's own production code,
 * 1,629 files) for constructs never specifically stress-tested in this investigation:
 * PHP 8.1 backed enums with methods, `match` expressions referencing `$this`, and real
 * (not hand-built) trait usage. Confirmed CORRECT, not a new defect -- preserved here
 * as regression coverage since these are genuinely new real-corpus-grounded scenarios,
 * even though nothing needed fixing. See
 * `2026-09-28-KOS-PHP-OWD12-real-corpus-audit.md` for the full audit, including the
 * extreme-LCOM4-outlier legitimacy check (three real classes at LCOM4=32/33/33, all
 * explained as genuine "fat controller"/"fat model" Laravel patterns, not bugs).
 */
final class RealCorpusPhpConstructRegressionTest extends TestCase
{
    /**
     * Modeled on the real app/Application/Election/Capabilities/
     * CapabilityDenialReason.php: a backed enum whose only method uses `match ($this)`
     * with `self::Case` arms. Neither a bare `$this` (no `->` follows) nor a
     * `self::CaseName` reference (no `(` follows -- not a call) produces any fact --
     * correctly so, since neither is a property access or a method invocation.
     */
    public function test_backed_enum_with_match_this_produces_no_spurious_facts(): void
    {
        $php = <<<'PHP'
            <?php
            enum Reason: string {
                case A = 'a';
                case B = 'b';

                public function label(): string {
                    return match ($this) {
                        self::A => 'Alpha',
                        self::B => 'Beta',
                    };
                }
            }
            PHP;
        $facts = PhpFactExtractor::extract($php);
        $method = $facts->units[0]->methods[0];
        self::assertSame([], $method->stateAccesses);
        self::assertSame([], $method->behaviourReferences);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(UnitKind::EnumUnit->name, $observed[0]['unitKind']);
        self::assertSame(1, $observed[0]['value']);
    }

    /**
     * Modeled on the real app/Shared/Domain/Concerns/RecordsEvents.php: a trait with
     * an array-append property write (`$this->events[] = $x;`, NOT a method call
     * despite the trailing `(` -- er, `[`) and a plain read+reset. Both methods
     * correctly connect via the shared property.
     */
    public function test_real_trait_shape_array_append_and_reset_connects_correctly(): void
    {
        $php = <<<'PHP'
            <?php
            trait RecordsEvents {
                private array $events = [];
                protected function record(object $e): void {
                    $this->events[] = $e;
                }
                public function release(): array {
                    $events = $this->events;
                    $this->events = [];
                    return $events;
                }
            }
            PHP;
        $facts = PhpFactExtractor::extract($php);
        $byName = [];
        foreach ($facts->units[0]->methods as $m) { $byName[$m->methodIdentity] = $m; }

        self::assertSame(['events'], array_map(fn ($s) => $s->propertyName, $byName['record']->stateAccesses));
        self::assertSame([], $byName['record']->behaviourReferences, 'array-append via property is a state access, not a call');

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(UnitKind::TraitUnit->name, $observed[0]['unitKind']);
        self::assertSame(1, $observed[0]['value']);
    }
}
