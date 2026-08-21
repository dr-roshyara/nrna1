<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownDocumentContentsReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S1 — the markdown adapter for the document-local register.
 *
 * Parses a document's numbered section headings (the register = the document) with
 * correct 1-based line numbers, so an assessment can state WHERE a defect is.
 *
 * ⛔ Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection).
 * ⛔ Read-only (AP-7 / DR-4): nothing here authors or edits.
 *
 * It must also be QUIET on the same constructs the corpus uses: headings inside
 * fenced code blocks, and quoted headings — those are not normative section
 * identifiers.
 */
final class MarkdownDocumentContentsReaderTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir().'/s1-'.bin2hex(random_bytes(6)).'.md';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    private function reader(): MarkdownDocumentContentsReader
    {
        return new MarkdownDocumentContentsReader();
    }

    public function test_it_extracts_numbered_section_headings_in_document_order(): void
    {
        file_put_contents($this->file, "# 4 · Migration phases\n\n## 4.1 ⭐ Phase 5\n\n## 4.2 ⭐ Phase 7\n\n# 5 · Verification\n");

        $sequence = $this->reader()->read($this->file);

        self::assertSame(4, $sequence->count());
        self::assertSame(['4', '4.1', '4.2', '5'], array_map(
            static fn ($id) => $id->number(),
            $sequence->identifiers(),
        ));
    }

    public function test_it_reports_correct_one_based_line_numbers(): void
    {
        file_put_contents($this->file, "front matter\n\n## 4.1 ⭐ Phase 5\n\ntext\n## 4.2 ⭐ Phase 7\n");

        $ids = $this->reader()->read($this->file)->identifiers();

        self::assertSame(3, $ids[0]->line());
        self::assertSame(6, $ids[1]->line());
    }

    public function test_it_ignores_headings_without_a_numeric_identifier(): void
    {
        file_put_contents($this->file, "### CASE A — strict extension\n\n### Axis 1\n\n## Results\n");

        $sequence = $this->reader()->read($this->file);

        self::assertSame(0, $sequence->count());
    }

    public function test_it_ignores_headings_inside_fenced_code_blocks(): void
    {
        file_put_contents($this->file, "## 4.0 Real heading\n\n```\n## 4.9 not a heading\n```\n\n## 4.1 Real heading\n");

        $sequence = $this->reader()->read($this->file);

        self::assertSame(['4.0', '4.1'], array_map(
            static fn ($id) => $id->number(),
            $sequence->identifiers(),
        ));
    }

    public function test_it_ignores_quoted_headings_in_blockquotes(): void
    {
        file_put_contents($this->file, "## 4.0 Real heading\n\n> ## 9.9 quoted heading — not normative\n\n## 4.1 Real heading\n");

        $sequence = $this->reader()->read($this->file);

        self::assertSame(['4.0', '4.1'], array_map(
            static fn ($id) => $id->number(),
            $sequence->identifiers(),
        ));
    }

    public function test_it_strips_trailing_atx_closing_hashes(): void
    {
        file_put_contents($this->file, "## 4.1 ⭐ Phase 5 ##\n");

        $sequence = $this->reader()->read($this->file);

        self::assertSame('4.1', $sequence->identifiers()[0]->number());
    }

    /** Fail-closed: an unreadable document is an error, not an empty PASS. */
    /**
     * The corpus uses NEL (U+0085) as an in-line separator. `\R` treats it as a line
     * break, so a `preg_split('/\R/')` reader would inflate every later line number.
     * Line numbers must track ACTUAL newlines only.
     */
    public function test_line_numbers_ignore_exotic_unicode_linebreaks(): void
    {
        file_put_contents($this->file, "## 4.1 ⭐ Phase 5\nseparator\x85continues on the same visual line\n## 4.2 ⭐ Phase 7\n");

        $ids = $this->reader()->read($this->file)->identifiers();

        self::assertSame(1, $ids[0]->line());
        self::assertSame(3, $ids[1]->line());
    }

    public function test_a_missing_document_throws(): void
    {
        $this->expectException(RuntimeException::class);

        $this->reader()->read($this->file.'-absent');
    }
}
