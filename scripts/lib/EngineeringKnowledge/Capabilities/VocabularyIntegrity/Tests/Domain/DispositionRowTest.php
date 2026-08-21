<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionRow;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * The value object that carries one observed disposition construct (S5).
 *
 * A disposition construct is the OBSERVATION side of the S5 heuristic: a row whose
 * non-first cells state a normative remedy for a trigger. `labelled` and `twoBranch`
 * are classed by the reader against the superseded-marker and two-branch families;
 * whether either makes the row a defect is the domain service's call.
 */
final class DispositionRowTest extends TestCase
{
    public function test_constructor_guards_an_empty_trigger(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DispositionRow::of('', 'remedy', 1, '8', false, false);
    }

    public function test_constructor_guards_an_empty_remedy(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DispositionRow::of('trigger', '  ', 1, '8', false, false);
    }

    public function test_constructor_guards_a_non_positive_line(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DispositionRow::of('trigger', 'remedy', 0, '8', false, false);
    }

    public function test_constructor_guards_an_empty_section(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DispositionRow::of('trigger', 'remedy', 1, '', false, false);
    }

    public function test_exposes_its_parts(): void
    {
        $row = DispositionRow::of('trigger', 'remedy', 7, '8', true, true);

        self::assertSame('trigger', $row->trigger());
        self::assertSame('remedy', $row->remedy());
        self::assertSame(7, $row->line());
        self::assertSame('8', $row->section());
        self::assertTrue($row->labelled());
        self::assertTrue($row->twoBranch());
    }

    public function test_defaults_are_unlabelled_single_branch(): void
    {
        $row = DispositionRow::of('trigger', 'remedy', 1, '8');

        self::assertFalse($row->labelled());
        self::assertFalse($row->twoBranch());
    }
}
