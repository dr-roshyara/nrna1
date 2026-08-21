<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\DocumentVocabularyReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateVocabularyIntegrity;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\VocabularySource;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\ConfusableIdentifier;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\VocabularyContents;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S3 — application service over the vocabulary check.
 *
 * Both the config source and the document reader are PORTS so infrastructure is
 * replaceable with the domain unchanged.
 *
 * Fail-closed, inherited from CAP-004's use case: an unreadable document OR an
 * unreadable config yields INCONCLUSIVE, never PASS.
 */
final class ValidateVocabularyIntegrityTest extends TestCase
{
    private function vocabularySourceReturning(DeclaredVocabulary $vocabulary): VocabularySource
    {
        return new class($vocabulary) implements VocabularySource
        {
            public function __construct(private readonly DeclaredVocabulary $vocabulary) {}

            public function read(): DeclaredVocabulary
            {
                return $this->vocabulary;
            }
        };
    }

    private function readerReturning(VocabularyContents $contents): DocumentVocabularyReader
    {
        return new class($contents) implements DocumentVocabularyReader
        {
            public function __construct(private readonly VocabularyContents $contents) {}

            public function read(string $path, DeclaredVocabulary $vocabulary): VocabularyContents
            {
                return $this->contents;
            }
        };
    }

    public function test_it_returns_the_domain_verdict(): void
    {
        $contents = VocabularyContents::of(
            [],
            [
                ConfusableIdentifier::of('CASE', 'B', 1),
                ConfusableIdentifier::of('CASE', 'β', 2),
            ],
            false,
        );

        $result = (new ValidateVocabularyIntegrity(
            $this->vocabularySourceReturning(DeclaredVocabulary::of(['Phase 2b'], 'DI-7')),
            $this->readerReturning($contents),
        ))->handle('fixture.md');

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('fixture.md', $result->evidence());
    }

    public function test_a_clean_document_passes(): void
    {
        $contents = VocabularyContents::of(
            [\EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\TermOccurrence::of('Phase 2b', 123, true)],
            [],
            false,
        );

        $result = (new ValidateVocabularyIntegrity(
            $this->vocabularySourceReturning(DeclaredVocabulary::of(['Phase 2b'], 'DI-7')),
            $this->readerReturning($contents),
        ))->handle('fixture.md');

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Fail-closed: an unreadable document must not yield PASS. */
    public function test_unreadable_document_is_inconclusive_not_pass(): void
    {
        $reader = new class implements DocumentVocabularyReader
        {
            public function read(string $path, DeclaredVocabulary $vocabulary): VocabularyContents
            {
                throw new RuntimeException('document unreadable');
            }
        };

        $result = (new ValidateVocabularyIntegrity(
            $this->vocabularySourceReturning(DeclaredVocabulary::of(['Phase 2b'], 'DI-7')),
            $reader,
        ))->handle('fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** Fail-closed: an unreadable config must not yield PASS either. */
    public function test_unreadable_config_is_inconclusive_not_pass(): void
    {
        $source = new class implements VocabularySource
        {
            public function read(): DeclaredVocabulary
            {
                throw new RuntimeException('config unreadable');
            }
        };

        $result = (new ValidateVocabularyIntegrity(
            $source,
            $this->readerReturning(VocabularyContents::of([], [], false)),
        ))->handle('fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
    }

    public function test_result_carries_evidence(): void
    {
        $result = (new ValidateVocabularyIntegrity(
            $this->vocabularySourceReturning(DeclaredVocabulary::of(['Phase 2b'], 'DI-7')),
            $this->readerReturning(VocabularyContents::of([], [], false)),
        ))->handle('fixture.md');

        self::assertNotSame('', $result->evidence());
    }
}
