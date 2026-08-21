<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepDefinition;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * S2 — a step the document's mandated block DEFINES.
 *
 * The step register is the set of steps the normative enumeration (the fenced
 * "Phase 5, mandated internal order" block) defines. A `step N` reference RESOLVES iff
 * `N` is in that register; the AMD5 `step 5` references dangle because AMD5's block
 * defines `1 · 2 · 3 · 4` only.
 */
final class StepDefinitionTest extends TestCase
{
    public function test_it_carries_step_and_line(): void
    {
        $def = StepDefinition::fromStepLine('4', 471);

        self::assertSame('4', $def->step());
        self::assertSame(471, $def->line());
    }

    public function test_it_accepts_letter_variants(): void
    {
        self::assertSame('1b', StepDefinition::fromStepLine('1b', 601)->step());
    }

    public function test_it_rejects_a_non_integer_step(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StepDefinition::fromStepLine('five', 10);
    }

    public function test_it_rejects_a_zero_line(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StepDefinition::fromStepLine('2', 0);
    }
}
