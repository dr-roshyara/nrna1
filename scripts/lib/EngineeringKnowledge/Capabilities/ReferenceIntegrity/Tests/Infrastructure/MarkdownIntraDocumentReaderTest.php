<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownIntraDocumentReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S2 — the markdown adapter for the intra-document reference register.
 *
 * Parses a document's §-references, step-references and mandated-block step definitions
 * with correct 1-based line numbers, so an assessment can state WHERE a defect is.
 *
 * ⛔ Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection).
 * ⛔ Read-only (AP-7 / DR-4): nothing here authors or edits.
 *
 * It must reproduce the corpus shapes exactly:
 *   • §- and step-references are extracted from blockquotes too (AMD5's DI-5 step-5
 *     references at lines 552 and 808 are blockquotes — skipping them would MISS the
 *     defect);
 *   • step DEFINITIONS come only from numeric-leading lines INSIDE fenced code blocks
 *     (the "Phase 5, mandated internal order" block);
 *   • references inside fenced code are code, not references — they must be skipped;
 *   • line numbers must ignore NEL (U+0085), the corpus's in-line separator.
 */
final class MarkdownIntraDocumentReaderTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir().'/s2-'.bin2hex(random_bytes(6)).'.md';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    private function reader(): MarkdownIntraDocumentReader
    {
        return new MarkdownIntraDocumentReader();
    }

    public function test_it_extracts_section_references_with_line_numbers(): void
    {
        file_put_contents($this->file, "See §4.1 and §18.\n\nLater, §4.1 again.\n");

        $refs = $this->reader()->read($this->file)->sectionReferences();

        self::assertCount(3, $refs);
        self::assertSame(['4.1', '18', '4.1'], array_map(
            static fn ($ref) => $ref->number(),
            $refs,
        ));
        self::assertSame([1, 1, 3], array_map(
            static fn ($ref) => $ref->line(),
            $refs,
        ));
    }

    public function test_it_extracts_multiple_references_from_one_line(): void
    {
        file_put_contents($this->file, "implementation design `ae451db9` §3.2/§4.1\n");

        $refs = $this->reader()->read($this->file)->sectionReferences();

        self::assertCount(2, $refs);
        self::assertSame(['3.2', '4.1'], array_map(
            static fn ($ref) => $ref->number(),
            $refs,
        ));
    }

    /** AMD5's DI-5 references live in table rows AND blockquotes — both must yield refs. */
    public function test_it_extracts_step_references_from_blockquotes(): void
    {
        file_put_contents(
            $this->file,
            "> ⭐ **Where each AMD5 correction bites:** `RD-7` at Phase 5 step 5.\n",
        );

        $refs = $this->reader()->read($this->file)->stepReferences();

        self::assertSame(['5'], array_map(
            static fn ($ref) => $ref->step(),
            $refs,
        ));
        self::assertSame(1, $refs[0]->line());
    }

    public function test_it_extracts_a_step_range_as_both_endpoints(): void
    {
        file_put_contents($this->file, "an interruption INSIDE Phase 5 steps 3→5\n");

        $refs = $this->reader()->read($this->file)->stepReferences();

        self::assertSame(['3', '5'], array_map(
            static fn ($ref) => $ref->step(),
            $refs,
        ));
    }

    /** The mandated block: numeric-leading lines inside a fence are step DEFINITIONS. */
    public function test_it_extracts_step_definitions_from_inside_fences(): void
    {
        file_put_contents(
            $this->file,
            "```\nPhase 5, mandated internal order\n"
            ."    1  RE-HASH        source vs frozen manifest\n"
            ."    2  READERS        P-2 (session-resolve)\n"
            ."    3  ⭐ THE WRITER   P-1 (workflow-state)\n"
            ."    4  MARKERS        runtime copy = DEMOTED\n"
            ."```\n",
        );

        $defs = $this->reader()->read($this->file)->stepDefinitions();

        self::assertSame(['1', '2', '3', '4'], array_map(
            static fn ($def) => $def->step(),
            $defs,
        ));
        self::assertSame([3, 4, 5, 6], array_map(
            static fn ($def) => $def->line(),
            $defs,
        ));
    }

    /** A `step 3` inside a code fence is CODE, not a reference. */
    public function test_it_skips_references_inside_fenced_code_blocks(): void
    {
        file_put_contents(
            $this->file,
            "before step 4\n\n```php\n// the writer runs at step 3\n```\n\n",
        );

        $contents = $this->reader()->read($this->file);

        self::assertSame(['4'], array_map(
            static fn ($ref) => $ref->step(),
            $contents->stepReferences(),
        ));
    }

    /** Quoted lines can never be step definitions (`> ` opens the line). */
    public function test_it_never_treats_blockquote_numeric_lines_as_definitions(): void
    {
        file_put_contents($this->file, ">     1  RE-HASH in a quoted table?\n");

        self::assertSame([], $this->reader()->read($this->file)->stepDefinitions());
    }

    /** §-references resolve against the SAME heading register S1 produces. */
    public function test_it_reuses_the_cap001_heading_register(): void
    {
        file_put_contents(
            $this->file,
            "## 4.1 ⭐ Phase 5\n\nSee §4.1.\n",
        );

        $contents = $this->reader()->read($this->file);

        self::assertSame('4.1', $contents->headings()->identifiers()[0]->number());
        self::assertSame(1, $contents->headings()->identifiers()[0]->line());
    }

    /** The corpus uses NEL (U+0085) as an in-line separator — line numbers must track
     *  ACTUAL newlines only, so a later reference keeps its true line number. */
    public function test_line_numbers_ignore_exotic_unicode_linebreaks(): void
    {
        file_put_contents(
            $this->file,
            "separator\x85continues on the same visual line\nstep 5\n",
        );

        $refs = $this->reader()->read($this->file)->stepReferences();

        self::assertSame(2, $refs[0]->line());
    }

    /** Fail-closed: an unreadable document is an error, not an empty PASS. */
    public function test_a_missing_document_throws(): void
    {
        $this->expectException(RuntimeException::class);

        $this->reader()->read($this->file.'-absent');
    }
}
