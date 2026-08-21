<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\AssessesTableColumnCount;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableRow;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * S4 — the domain service for table column-count consistency.
 *
 * Structural rule (CAP-004-ADJACENT — the approved plan's S4 row; not DP-4, which
 * governs references, not tables): a Markdown table is a block of consecutive rows
 * whose SECOND row is a separator; every row of the block must carry the same number
 * of cells as its header row. A row whose cell count deviates is a RAGGED table — a
 * structural defect.
 *
 * ⛔ Ownership is stated honestly: no DP-1..DP-6 verbatim owns this rule. It extends
 *    the CAP-004 realization's document-structure reading (the package that already
 *    reads headings · references · enumerations) WITHOUT claiming DP-4 authority over
 *    tables. It creates no capability, no register, no policy (plan §5 boundary:
 *    "no new capability · no new CAP id · no new register · no new domain policy").
 *
 * Verdict mapping:
 *   at least one ragged row in a recognised table block  → FAIL
 *   at least one complete block, none ragged            → PASS
 *   no rows, or no header+separator pair               → INCONCLUSIVE (D-2: nothing
 *                                                        to evaluate; absence of
 *                                                        evidence is never PASS)
 *
 * ⛔ This service owns EXECUTION only — the rule is stated here as the plan scoped it,
 *    not invented beyond it.
 */
final class AssessesTableColumnCountTest extends TestCase
{
    private function assessor(): AssessesTableColumnCount
    {
        return new AssessesTableColumnCount();
    }

    /** @param  list<array{0: int, 1: int, 2: bool}>  $rows  [cellCount, line, isSeparator] */
    private function contents(array $rows = []): TableContents
    {
        return TableContents::of(array_map(
            static fn (array $r): TableRow => TableRow::of($r[0], $r[1], $r[2]),
            $rows,
        ));
    }

    /** ⛔ THE S4 RED BOUNDARY (plan §5, verbatim): tests assert a defect verdict on a
     *  fixture with a ragged table. Header row declares 2 columns; the last data row
     *  carries only 1. */
    public function test_a_ragged_data_row_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [2, 1, false], // header
                [2, 2, true],  // separator
                [2, 3, false], // data
                [1, 4, false], // ⛔ ragged: 1 column against a 2-column header
            ]),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertFalse($result->isClean());
        self::assertStringContainsString('ragged', $result->evidence());
        self::assertStringContainsString('line 4', $result->evidence());
        self::assertStringContainsString('header row (line 1)', $result->evidence());
    }

    /** A separator row whose own cell count deviates is as ragged as any data row. */
    public function test_a_separator_row_with_a_different_column_count_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [3, 1, false],
                [2, 2, true], // ⛔ separator declares 2 columns against a 3-column header
                [3, 3, false],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('separator row (line 2)', $result->evidence());
    }

    /** A table whose header, separator and every data row agree is structurally sound. */
    public function test_a_consistent_table_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [2, 1, false],
                [2, 2, true],
                [2, 3, false],
                [2, 4, false],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
        self::assertStringContainsString('structurally consistent', $result->evidence());
    }

    /** An empty table (header + separator, no data rows) is still structurally sound. */
    public function test_an_empty_table_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [2, 1, false],
                [2, 2, true],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Two consistent tables are both counted in the evidence. */
    public function test_two_consistent_tables_are_pass_with_count(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [2, 1, false], [2, 2, true], [2, 3, false],
                [3, 10, false], [3, 11, true], [3, 12, false], [3, 13, false],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('2 structurally consistent table(s)', $result->evidence());
    }

    /** D-2 / fail-closed: nothing to evaluate must be INCONCLUSIVE, never PASS. */
    public function test_a_document_with_no_tables_is_inconclusive(): void
    {
        $result = $this->assessor()->validate($this->contents(), 'fixture');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** A `|`-leading line with NO following separator is not a Markdown table — nothing
     *  was evaluated (the reader may still have observed the row; the block rule refuses
     *  it), so the verdict is INCONCLUSIVE, never PASS. */
    public function test_rows_without_a_separator_are_not_a_table_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents([
                [2, 1, false],
                [2, 2, false],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('no table', $result->evidence());
    }

    /** DP-5 / AP-8: every outcome states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $assessor = $this->assessor();

        $outcomes = [
            $assessor->validate($this->contents([[2, 1, false], [2, 2, true], [1, 3, false]]), 'fixture'),
            $assessor->validate($this->contents([[2, 1, false], [2, 2, true], [2, 3, false]]), 'fixture'),
            $assessor->validate($this->contents(), 'fixture'),
        ];

        foreach ($outcomes as $outcome) {
            self::assertNotSame('', $outcome->evidence(), "no evidence for {$outcome->verdict()->value}");
        }
    }
}
