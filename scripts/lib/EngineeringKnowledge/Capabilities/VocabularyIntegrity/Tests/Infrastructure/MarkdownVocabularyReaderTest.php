<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownVocabularyReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S3 — the markdown adapter for the vocabulary scan.
 *
 * Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection). Read-only (AP-7 / DR-4).
 *
 * It must reproduce the corpus shapes exactly:
 *   • a retired term used PLAIN is LIVE (`committed at Phase 2b`) — the AMD4 defect;
 *   • a retired term QUOTED (`*"Phase 2b"*`), §-SCOPED (`§4 Phase 2b — …`, the recap
 *     catalog row) or in a `Traceability (` block is CITED — the AMD5/AMD6 quiet shape;
 *   • the confusable identifiers `CASE <single glyph>` are extracted with 1-based lines;
 *   • the collision declaration is the backticked integrity label (`` `DI-7` ``);
 *   • fences are code, not vocabulary — skipped;
 *   • line numbers ignore NEL (U+0085), the corpus's in-line separator.
 */
final class MarkdownVocabularyReaderTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir().'/s3-'.bin2hex(random_bytes(6)).'.md';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    private function reader(): MarkdownVocabularyReader
    {
        return new MarkdownVocabularyReader();
    }

    private function vocabulary(): DeclaredVocabulary
    {
        return DeclaredVocabulary::of(['Phase 2b'], 'DI-7');
    }

    public function test_it_extracts_latin_confusable_identifiers_with_lines(): void
    {
        file_put_contents($this->file, "CASE A / CASE B\n");

        $identifiers = $this->reader()->read($this->file, $this->vocabulary())->confusableIdentifiers();

        self::assertCount(2, $identifiers);
        self::assertSame('CASE', $identifiers[0]->family());
        self::assertSame(['A', 'B'], array_map(
            static fn ($id) => $id->glyph(),
            $identifiers,
        ));
        self::assertSame([1, 1], array_map(
            static fn ($id) => $id->line(),
            $identifiers,
        ));
    }

    public function test_it_extracts_greek_confusable_identifiers(): void
    {
        file_put_contents($this->file, "> CASE β (post-demotion write)\n");

        $identifiers = $this->reader()->read($this->file, $this->vocabulary())->confusableIdentifiers();

        self::assertSame(['β'], array_map(
            static fn ($id) => $id->glyph(),
            $identifiers,
        ));
        self::assertSame(1, $identifiers[0]->line());
    }

    /** A plain retired term is LIVE — the AMD4 defect shape. */
    public function test_a_plain_retired_term_is_live(): void
    {
        file_put_contents($this->file, "the manifest is committed at Phase 2b\n");

        $occurrences = $this->reader()->read($this->file, $this->vocabulary())->staleTermOccurrences();

        self::assertCount(1, $occurrences);
        self::assertFalse($occurrences[0]->cited());
        self::assertSame(1, $occurrences[0]->line());
    }

    /** A QUOTED retired term is CITED — AMD5's DI-2 row `*"Phase 2b"*`. */
    public function test_a_quoted_retired_term_is_cited(): void
    {
        file_put_contents($this->file, "AMD3/AMD4 said *\"committed at Phase 2b\"* — impossible\n");

        $occurrences = $this->reader()->read($this->file, $this->vocabulary())->staleTermOccurrences();

        self::assertCount(1, $occurrences);
        self::assertTrue($occurrences[0]->cited());
    }

    /** A §-SCOPED retired term is CITED — the recap catalog row `§4 Phase 2b — …`. */
    public function test_a_section_scoped_retired_term_is_cited(): void
    {
        file_put_contents(
            $this->file,
            "| 7 | §4 Phase 2b — **`.gitattributes` pin** required before Phase 3 | `CL-6` |\n",
        );

        $occurrences = $this->reader()->read($this->file, $this->vocabulary())->staleTermOccurrences();

        self::assertCount(1, $occurrences);
        self::assertTrue($occurrences[0]->cited());
    }

    /** A Traceability block occurrence is CITED. */
    public function test_a_traceability_line_is_cited(): void
    {
        file_put_contents($this->file, "**Traceability (AMD5):** the term Phase 2b is listed as history\n");

        $occurrences = $this->reader()->read($this->file, $this->vocabulary())->staleTermOccurrences();

        self::assertCount(1, $occurrences);
        self::assertTrue($occurrences[0]->cited());
    }

    /** A hyphenated compound is a DIFFERENT term: `Phase 2b-pin` must not match. */
    public function test_a_hyphenated_compound_is_not_the_retired_term(): void
    {
        file_put_contents($this->file, "the shorthand is Phase 2b-pin\n");

        self::assertSame([], $this->reader()->read($this->file, $this->vocabulary())->staleTermOccurrences());
    }

    /** The backticked integrity label is the collision declaration. */
    public function test_it_detects_the_declaration_label(): void
    {
        file_put_contents($this->file, "| ⚠️ **`DI-7`** | integrity | the collision is gone |\n");

        self::assertTrue($this->reader()->read($this->file, $this->vocabulary())->confusableCollisionDeclared());
    }

    public function test_it_reports_absent_declaration_as_false(): void
    {
        file_put_contents($this->file, "CASE B and CASE β\n");

        self::assertFalse($this->reader()->read($this->file, $this->vocabulary())->confusableCollisionDeclared());
    }

    /** References inside a code fence are CODE, not vocabulary. */
    public function test_it_skips_vocabulary_inside_fenced_code_blocks(): void
    {
        file_put_contents(
            $this->file,
            "CASE B\n\n```\nCASE β\ncommitted at Phase 2b\n```\n",
        );

        $contents = $this->reader()->read($this->file, $this->vocabulary());

        self::assertSame(['B'], array_map(
            static fn ($id) => $id->glyph(),
            $contents->confusableIdentifiers(),
        ));
        self::assertSame([], $contents->staleTermOccurrences());
    }

    /** The corpus uses NEL (U+0085) as an in-line separator — line numbers must track
     *  ACTUAL newlines only, so a later token keeps its true line number. */
    public function test_line_numbers_ignore_exotic_unicode_linebreaks(): void
    {
        file_put_contents(
            $this->file,
            "separator\x85continues on the same visual line\nCASE β\n",
        );

        $identifiers = $this->reader()->read($this->file, $this->vocabulary())->confusableIdentifiers();

        self::assertSame(2, $identifiers[0]->line());
    }

    /** Fail-closed: an unreadable document is an error, not an empty PASS. */
    public function test_a_missing_document_throws(): void
    {
        $this->expectException(RuntimeException::class);

        $this->reader()->read($this->file.'-absent', $this->vocabulary());
    }
}
