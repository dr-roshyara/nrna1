<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\TermOccurrence;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** S3 — a retired-term occurrence the reader observed, with its cited/live class. */
final class TermOccurrenceTest extends TestCase
{
    public function test_it_carries_term_line_and_citation_class(): void
    {
        $occurrence = TermOccurrence::of('Phase 2b', 469, false);

        self::assertSame('Phase 2b', $occurrence->term());
        self::assertSame(469, $occurrence->line());
        self::assertFalse($occurrence->cited());
    }

    public function test_lines_are_one_based(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TermOccurrence::of('Phase 2b', 0, false);
    }

    public function test_an_empty_term_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TermOccurrence::of('   ', 5, true);
    }
}
