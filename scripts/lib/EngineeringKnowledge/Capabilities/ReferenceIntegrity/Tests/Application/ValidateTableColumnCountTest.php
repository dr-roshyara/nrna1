<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\DocumentTableReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateTableColumnCount;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableRow;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S4 — application service over table column-count consistency.
 *
 * The reader is a PORT so infrastructure is replaceable with the domain unchanged.
 *
 * Fail-closed, inherited from CAP-004's realization: an unreadable document yields
 * INCONCLUSIVE, never PASS.
 */
final class ValidateTableColumnCountTest extends TestCase
{
    private function readerReturning(TableContents $contents): DocumentTableReader
    {
        return new class($contents) implements DocumentTableReader
        {
            public function __construct(private readonly TableContents $contents) {}

            public function read(string $path): TableContents
            {
                return $this->contents;
            }
        };
    }

    private function raggedContents(): TableContents
    {
        return TableContents::of([
            TableRow::of(2, 1, false),
            TableRow::of(2, 2, true),
            TableRow::of(2, 3, false),
            TableRow::of(1, 4, false),
        ]);
    }

    public function test_it_returns_the_domain_verdict(): void
    {
        $result = (new ValidateTableColumnCount($this->readerReturning($this->raggedContents())))
            ->handle('fixture.md');

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('fixture.md', $result->evidence());
    }

    public function test_it_reports_a_clean_document_as_pass(): void
    {
        $contents = TableContents::of([
            TableRow::of(2, 1, false),
            TableRow::of(2, 2, true),
            TableRow::of(2, 3, false),
        ]);

        $result = (new ValidateTableColumnCount($this->readerReturning($contents)))
            ->handle('fixture.md');

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Fail-closed: an unreadable document must not yield PASS. */
    public function test_unreadable_document_is_inconclusive_not_pass(): void
    {
        $reader = new class implements DocumentTableReader
        {
            public function read(string $path): TableContents
            {
                throw new RuntimeException('document unreadable');
            }
        };

        $result = (new ValidateTableColumnCount($reader))->handle('fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    public function test_result_carries_evidence(): void
    {
        $result = (new ValidateTableColumnCount($this->readerReturning($this->raggedContents())))
            ->handle('fixture.md');

        self::assertNotSame('', $result->evidence());
    }
}
