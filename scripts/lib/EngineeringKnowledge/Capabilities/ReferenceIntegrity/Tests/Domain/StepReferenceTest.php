<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepReference;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * S2 — a `step N` reference found in a document.
 *
 * The corpus cites steps as `step 3`, `Phase 5 step 5`, `steps 3→5` — a step is an
 * integer optionally carrying a letter variant (`1b`). Whether the referenced step is
 * DEFINED by the mandated block is the domain service's job; this VO only carries it.
 */
final class StepReferenceTest extends TestCase
{
    public function test_it_carries_step_and_line(): void
    {
        $ref = StepReference::fromStepLine('5', 546);

        self::assertSame('5', $ref->step());
        self::assertSame(546, $ref->line());
    }

    public function test_it_accepts_letter_variants(): void
    {
        self::assertSame('1b', StepReference::fromStepLine('1b', 248)->step());
    }

    public function test_it_rejects_a_non_integer_step(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StepReference::fromStepLine('5.1', 10);
    }

    public function test_it_rejects_a_zero_line(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StepReference::fromStepLine('3', 0);
    }
}
