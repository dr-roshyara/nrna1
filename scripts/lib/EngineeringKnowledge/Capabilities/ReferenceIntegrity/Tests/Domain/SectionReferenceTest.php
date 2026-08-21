<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\SectionReference;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * S2 — a `§N` reference found in a document.
 *
 * A pure value object: the referenced number (`4.1`, `18`) and the line it appears
 * on, so an assessment can state WHERE a reference is. Whether the reference RESOLVES
 * is the domain service's job — this VO only carries the reference.
 *
 * The number grammar is the same as the heading grammar (DP-4's "target" must be a
 * section identifier the document could name), so a `§N` can be matched against the
 * heading register the S1 reader produced.
 */
final class SectionReferenceTest extends TestCase
{
    public function test_it_carries_number_and_line(): void
    {
        $ref = SectionReference::fromNumberLine('4.1', 349);

        self::assertSame('4.1', $ref->number());
        self::assertSame(349, $ref->line());
    }

    public function test_it_accepts_single_level_and_leading_zero_numbers(): void
    {
        self::assertSame('18', SectionReference::fromNumberLine('18', 148)->number());
        self::assertSame('0.6.5', SectionReference::fromNumberLine('0.6.5', 159)->number());
    }

    public function test_it_rejects_a_non_numeric_reference(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SectionReference::fromNumberLine('CASE A', 5);
    }

    public function test_it_rejects_a_zero_line(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SectionReference::fromNumberLine('4.1', 0);
    }
}
