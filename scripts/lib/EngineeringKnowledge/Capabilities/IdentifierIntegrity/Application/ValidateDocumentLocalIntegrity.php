<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\AssessesDocumentLocalIntegrity;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use RuntimeException;
use Throwable;

/**
 * Application service — the S1 use case over the document-local register.
 *
 * Orchestrates: read the document through the port → apply the domain service →
 * return one verdict. It contains no policy of its own.
 *
 * AC-1 constraint, inherited: this capability CANNOT SELF-ISSUE. It is invoked;
 * it never schedules itself.
 */
final readonly class ValidateDocumentLocalIntegrity
{
    public function __construct(
        private DocumentContentsReader $reader,
        private AssessesDocumentLocalIntegrity $validator = new AssessesDocumentLocalIntegrity(),
    ) {
    }

    public function handle(string $path): Assessment
    {
        try {
            $sequence = $this->reader->read($path);
        } catch (RuntimeException|Throwable $e) {
            // Fail closed: an unreadable document yields INCONCLUSIVE, never PASS.
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Document '%s' could not be read (%s). Absence of evidence is not PASS.",
                    $path,
                    $e->getMessage(),
                ),
                $path,
            );
        }

        return $this->validator->validate($sequence, $path);
    }
}
