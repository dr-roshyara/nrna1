<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownTableReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S4 — the markdown adapter for table rows.
 *
 * Observes `|`-leading non-fence lines as table rows with their cell count, separator
 * flag and 1-based line number, so the domain service can reconstruct blocks and judge
 * column-count consistency. Reads the DOCUMENT ITSELF (DR-1 / AP-2). Read-only (AP-7).
 *
 * It must reproduce the corpus shapes exactly:
 *   • a row is a SEPARATOR when every cell is dashes/colons (`| --- | --- |`);
 *   • an escaped pipe (`\|`) inside a cell does NOT split the row (count stays honest);
 *   • table lines INSIDE fenced code are code, not tables — they must be skipped;
 *   • line numbers must ignore NEL (U+0085), the corpus's in-line separator.
 *
 * NOT-CHECKED (D-4, stated so the report does not overclaim): a pipe inside an inline
 * code span is not excluded (it would inflate a cell count); blockquote-prefixed tables
 * (`> | … |`) are not observed.
 */
final class MarkdownTableReaderTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir().'/s4-'.bin2hex(random_bytes(6)).'.md';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    private function reader(): MarkdownTableReader
    {
        return new MarkdownTableReader();
    }

    public function test_it_observes_a_table_with_line_numbers(): void
    {
        file_put_contents($this->file, "| Col A | Col B |\n|-------|-------|\n| one   | two   |\n");

        $rows = $this->reader()->read($this->file)->rows();

        self::assertCount(3, $rows);
        self::assertSame([2, 2, 2], array_map(static fn ($r) => $r->cellCount(), $rows));
        self::assertSame([1, 2, 3], array_map(static fn ($r) => $r->line(), $rows));
        self::assertSame([false, true, false], array_map(static fn ($r) => $r->isSeparator(), $rows));
    }

    public function test_it_flags_the_separator_even_with_alignment_colons(): void
    {
        file_put_contents($this->file, "| a | b |\n|:--|--:|\n");

        $rows = $this->reader()->read($this->file)->rows();

        self::assertSame([false, true], array_map(static fn ($r) => $r->isSeparator(), $rows));
    }

    /** A ragged data row is observed honestly — one cell against a two-column header. */
    public function test_it_observes_a_ragged_row_honestly(): void
    {
        file_put_contents($this->file, "| Col A | Col B |\n|-------|-------|\n| three |\n");

        $rows = $this->reader()->read($this->file)->rows();

        self::assertSame([2, 2, 1], array_map(static fn ($r) => $r->cellCount(), $rows));
    }

    public function test_an_escaped_pipe_does_not_split_the_row(): void
    {
        file_put_contents($this->file, "| a \\| b | c |\n|---|---|\n| x | y |\n");

        $rows = $this->reader()->read($this->file)->rows();

        // The header cell "a \| b" holds a literal pipe; the header still has 2 columns.
        self::assertSame(2, $rows[0]->cellCount());
    }

    /** A `|`-leading line inside a fenced code block is code, not a table. */
    public function test_table_lines_inside_a_fence_are_skipped(): void
    {
        file_put_contents($this->file, "Before.\n\n```\n| a | b |\n| c | d |\n```\n\n| a | b |\n|---|---|\n| x | y |\n");

        $rows = $this->reader()->read($this->file)->rows();

        // Only the three rows AFTER the fence are tables (lines 8 · 9 · 10).
        self::assertCount(3, $rows);
        self::assertSame([2, 2, 2], array_map(static fn ($r) => $r->cellCount(), $rows));
        self::assertSame([8, 9, 10], array_map(static fn ($r) => $r->line(), $rows));
    }

    /** NEL (U+0085) is an in-line separator in the corpus, never a line break — a NEL
     *  inside a cell must not split the row or inflate its line number. */
    public function test_line_numbers_ignore_nel(): void
    {
        $nel = "\u{85}";
        file_put_contents($this->file, "| a | b{$nel} c |\n|---|---|\n| x | y |\n");

        $rows = $this->reader()->read($this->file)->rows();

        self::assertCount(3, $rows);
        self::assertSame([2, 2, 2], array_map(static fn ($r) => $r->cellCount(), $rows));
        self::assertSame([1, 2, 3], array_map(static fn ($r) => $r->line(), $rows));
    }

    public function test_an_unreadable_document_throws(): void
    {
        $this->expectException(RuntimeException::class);

        $this->reader()->read('/no/such/file-'.bin2hex(random_bytes(4)).'.md');
    }
}
