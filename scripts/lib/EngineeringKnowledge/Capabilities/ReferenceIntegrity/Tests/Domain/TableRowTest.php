<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableRow;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** S4 — the observed shape of one Markdown table row. */
final class TableRowTest extends TestCase
{
    public function test_a_valid_row_exposes_its_observation(): void
    {
        $row = TableRow::of(3, 17, false);

        self::assertSame(3, $row->cellCount());
        self::assertSame(17, $row->line());
        self::assertFalse($row->isSeparator());
    }

    public function test_a_separator_row_is_flagged(): void
    {
        $row = TableRow::of(2, 2, true);

        self::assertTrue($row->isSeparator());
    }

    public function test_a_cell_count_below_one_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TableRow::of(0, 1, false);
    }

    public function test_a_line_below_one_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TableRow::of(1, 0, false);
    }
}
