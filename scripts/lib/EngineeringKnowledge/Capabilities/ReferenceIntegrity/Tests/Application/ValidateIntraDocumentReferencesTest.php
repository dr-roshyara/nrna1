<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\IntraDocumentContentsReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateIntraDocumentReferences;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\ReferenceIntegrityContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\SectionReference;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepDefinition;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepReference;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S2 — application service over intra-document references.
 *
 * The reader is a PORT so infrastructure is replaceable (markdown today, a
 * pre-extracted index later) with the domain unchanged.
 *
 * Fail-closed, inherited from CAP-004: an unreadable document yields INCONCLUSIVE,
 * never PASS.
 */
final class ValidateIntraDocumentReferencesTest extends TestCase
{
    private function readerReturning(ReferenceIntegrityContents $contents): IntraDocumentContentsReader
    {
        return new class($contents) implements IntraDocumentContentsReader
        {
            public function __construct(private readonly ReferenceIntegrityContents $contents) {}

            public function read(string $path): ReferenceIntegrityContents
            {
                return $this->contents;
            }
        };
    }

    private function contentsWithStepFiveDangling(): ReferenceIntegrityContents
    {
        return ReferenceIntegrityContents::of(
            DocumentSectionSequence::fromIdentifiers([
                DocumentSectionIdentifier::fromNumberLine('4', 10),
            ]),
            [],
            [StepReference::fromStepLine('5', 546)],
            [
                StepDefinition::fromStepLine('1', 468),
                StepDefinition::fromStepLine('2', 469),
                StepDefinition::fromStepLine('3', 470),
                StepDefinition::fromStepLine('4', 471),
            ],
        );
    }

    public function test_it_returns_the_domain_verdict(): void
    {
        $result = (new ValidateIntraDocumentReferences($this->readerReturning($this->contentsWithStepFiveDangling())))
            ->handle('fixture.md');

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('fixture.md', $result->evidence());
    }

    public function test_it_reports_a_clean_document_as_pass(): void
    {
        $contents = ReferenceIntegrityContents::of(
            DocumentSectionSequence::fromIdentifiers([
                DocumentSectionIdentifier::fromNumberLine('4.1', 11),
            ]),
            [SectionReference::fromNumberLine('4.1', 11)],
            [],
            [],
        );

        $result = (new ValidateIntraDocumentReferences($this->readerReturning($contents)))
            ->handle('fixture.md');

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Fail-closed: an unreadable document must not yield PASS. */
    public function test_unreadable_document_is_inconclusive_not_pass(): void
    {
        $reader = new class implements IntraDocumentContentsReader
        {
            public function read(string $path): ReferenceIntegrityContents
            {
                throw new RuntimeException('document unreadable');
            }
        };

        $result = (new ValidateIntraDocumentReferences($reader))->handle('fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    public function test_result_carries_evidence(): void
    {
        $result = (new ValidateIntraDocumentReferences($this->readerReturning(
            ReferenceIntegrityContents::of(
                DocumentSectionSequence::fromIdentifiers([
                    DocumentSectionIdentifier::fromNumberLine('4.1', 11),
                ]),
                [SectionReference::fromNumberLine('4.1', 11)],
                [],
                [],
            )
        )))->handle('fixture.md');

        self::assertNotSame('', $result->evidence());
    }
}
