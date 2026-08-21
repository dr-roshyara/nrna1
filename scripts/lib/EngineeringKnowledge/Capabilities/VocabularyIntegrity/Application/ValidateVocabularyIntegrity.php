<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\AssessesVocabularyIntegrity;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use RuntimeException;
use Throwable;

/**
 * Application service — the S3 use case over a document's vocabulary.
 *
 * Orchestrates: read the declared vocabulary → read the document through the port →
 * apply the domain service → return one verdict. It contains no policy of its own
 * (DDD: application services orchestrate; business meaning stays in the domain).
 *
 * AC-1 constraint, inherited: this capability CANNOT SELF-ISSUE. It is invoked;
 * it never schedules itself.
 */
final readonly class ValidateVocabularyIntegrity
{
    public function __construct(
        private VocabularySource $vocabularySource,
        private DocumentVocabularyReader $reader,
        private AssessesVocabularyIntegrity $validator = new AssessesVocabularyIntegrity(),
    ) {
    }

    public function handle(string $path): Assessment
    {
        try {
            $vocabulary = $this->vocabularySource->read();
            $contents = $this->reader->read($path, $vocabulary);
        } catch (RuntimeException|Throwable $e) {
            // Fail closed: an unreadable document or config yields INCONCLUSIVE, never PASS.
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Vocabulary of '%s' could not be assessed (%s). Absence of evidence is not PASS.",
                    $path,
                    $e->getMessage(),
                ),
                $path,
            );
        }

        return $this->validator->validate($contents, $path);
    }
}
