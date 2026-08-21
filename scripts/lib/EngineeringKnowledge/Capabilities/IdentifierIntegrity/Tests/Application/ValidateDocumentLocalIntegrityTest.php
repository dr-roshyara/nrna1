<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\DocumentContentsReader;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateDocumentLocalIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S1 — application service over the document-local register.
 *
 * The reader is a PORT so infrastructure is replaceable (markdown today, a
 * pre-extracted index later) with the domain unchanged.
 *
 * Fail-closed, inherited from CAP-001: an unreadable document yields INCONCLUSIVE,
 * never PASS.
 */
final class ValidateDocumentLocalIntegrityTest extends TestCase
{
    private function readerReturning(DocumentSectionSequence $sequence): DocumentContentsReader
    {
        return new class($sequence) implements DocumentContentsReader
        {
            public function __construct(private readonly DocumentSectionSequence $sequence) {}

            public function read(string $path): DocumentSectionSequence
            {
                return $this->sequence;
            }
        };
    }

    public function test_it_returns_the_domain_verdict(): void
    {
        $sequence = DocumentSectionSequence::fromIdentifiers([
            DocumentSectionIdentifier::fromNumberLine('4.1', 349),
            DocumentSectionIdentifier::fromNumberLine('4.1', 436),
        ]);

        $result = (new ValidateDocumentLocalIntegrity($this->readerReturning($sequence)))
            ->handle('fixture.md');

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('fixture.md', $result->evidence());
    }

    public function test_it_reports_a_clean_document_as_pass(): void
    {
        $sequence = DocumentSectionSequence::fromIdentifiers([
            DocumentSectionIdentifier::fromNumberLine('4.0', 30),
            DocumentSectionIdentifier::fromNumberLine('4.1', 31),
        ]);

        $result = (new ValidateDocumentLocalIntegrity($this->readerReturning($sequence)))
            ->handle('fixture.md');

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Fail-closed: an unreadable document must not yield PASS. */
    public function test_unreadable_document_is_inconclusive_not_pass(): void
    {
        $reader = new class implements DocumentContentsReader
        {
            public function read(string $path): DocumentSectionSequence
            {
                throw new RuntimeException('document unreadable');
            }
        };

        $result = (new ValidateDocumentLocalIntegrity($reader))->handle('fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    public function test_result_carries_evidence(): void
    {
        $result = (new ValidateDocumentLocalIntegrity($this->readerReturning(
            DocumentSectionSequence::fromIdentifiers([
                DocumentSectionIdentifier::fromNumberLine('4.0', 30),
            ])
        )))->handle('fixture.md');

        self::assertNotSame('', $result->evidence());
    }
}
