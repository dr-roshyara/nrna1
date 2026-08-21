<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\DispositionContentsReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateCompetingCurrentDefinitions;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionContents;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionRow;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\SplitDeclaration;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S5 — the application service over the competing-current-definitions use case.
 *
 * Orchestrates: read the document through the port → apply the domain service → return
 * one verdict. It contains no policy of its own (DDD: application services orchestrate).
 */
final class ValidateCompetingCurrentDefinitionsTest extends TestCase
{
    /** A reader that yields a fixed register (the AMD5 DI-4 shape), to test the use case. */
    private function readerReturning(DispositionContents $contents): DispositionContentsReader
    {
        return new class($contents) implements DispositionContentsReader {
            public function __construct(private DispositionContents $contents)
            {
            }

            public function read(string $path): DispositionContents
            {
                return $this->contents;
            }
        };
    }

    public function test_a_competing_unlabelled_disposition_yields_warn(): void
    {
        $service = new ValidateCompetingCurrentDefinitions($this->readerReturning(
            DispositionContents::of(
                rows: [DispositionRow::of('at Phase 7, on a FINAL re-hash mismatch', 'STOP-AND-RECONCILE.', 9, '8', false, false)],
                splits: [SplitDeclaration::of('THE MISMATCH DISPOSITION IS SPLIT — at Phase 7, on a FINAL re-hash mismatch, never one branch for both.', 4, '4.4')],
                policyDeclared: true,
            ),
        ));

        $result = $service->handle('fixture.md');

        self::assertSame(Verdict::WARN, $result->verdict());
    }

    /** Fail closed: an unreadable document yields INCONCLUSIVE, never PASS. */
    public function test_unreadable_document_is_inconclusive(): void
    {
        $service = new ValidateCompetingCurrentDefinitions(
            new class() implements DispositionContentsReader {
                public function read(string $path): DispositionContents
                {
                    throw new RuntimeException("document not readable at {$path}");
                }
            },
        );

        $result = $service->handle('missing.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }
}
