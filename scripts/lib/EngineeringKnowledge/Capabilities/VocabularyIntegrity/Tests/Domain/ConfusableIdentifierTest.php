<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\ConfusableIdentifier;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** S3 — a single-glyph identifier token (`CASE B`, `CASE β`) the reader observed. */
final class ConfusableIdentifierTest extends TestCase
{
    public function test_it_carries_family_glyph_and_line(): void
    {
        $identifier = ConfusableIdentifier::of('CASE', 'β', 504);

        self::assertSame('CASE', $identifier->family());
        self::assertSame('β', $identifier->glyph());
        self::assertSame(504, $identifier->line());
    }

    public function test_lines_are_one_based(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ConfusableIdentifier::of('CASE', 'B', 0);
    }

    public function test_the_glyph_must_be_a_single_grapheme(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ConfusableIdentifier::of('CASE', 'BO', 1);
    }

    public function test_an_empty_family_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ConfusableIdentifier::of('', 'B', 1);
    }
}
