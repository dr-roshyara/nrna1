<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\AssessesCompetingCurrentDefinitions;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use Throwable;

/**
 * The S5 use case: one document → one verdict.
 *
 * Orchestrates the port and the domain service; carries no policy of its own
 * (DDD: application services orchestrate, they do not decide).
 *
 * Fail-closed (D-2): any failure to read or to assess — unreadable document, parser error,
 * unexpected throwable — is INCONCLUSIVE, never PASS. "Nothing checked" is not "nothing
 * wrong."
 */
final readonly class ValidateCompetingCurrentDefinitions
{
    public function __construct(
        private DispositionContentsReader $reader,
        private AssessesCompetingCurrentDefinitions $validator = new AssessesCompetingCurrentDefinitions(),
    ) {
    }

    public function handle(string $path): Assessment
    {
        try {
            return $this->validator->validate($this->reader->read($path), $path);
        } catch (Throwable $e) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Dispositions of '%s' could not be assessed (%s). Absence of evidence is not PASS.",
                    $path,
                    $e->getMessage(),
                ),
                $path,
            );
        }
    }
}
