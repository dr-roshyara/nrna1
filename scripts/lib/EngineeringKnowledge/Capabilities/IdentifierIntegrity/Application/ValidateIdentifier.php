<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\AssessesIdentifierIntegrity;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use RuntimeException;
use Throwable;

/**
 * Application service — the CAP-001 use case.
 *
 * Orchestrates: parse the proposed identifier → read its register(ns) through the port
 * → apply the domain service → return one verdict. It contains no policy of its own.
 *
 * AC-1 constraint: this capability CANNOT SELF-ISSUE. It is invoked; it never schedules
 * itself. (Where the trigger belongs is AD-1 Q-7 — OPEN — so no trigger is asserted here.)
 */
final readonly class ValidateIdentifier
{
    public function __construct(
        private SeriesContentsReader $reader,
        private AssessesIdentifierIntegrity $validator = new AssessesIdentifierIntegrity(),
    ) {
    }

    public function handle(string $proposedIdentifier): Assessment
    {
        $identifier = Identifier::fromString($proposedIdentifier);

        try {
            $contents = $this->reader->read($identifier->series());
        } catch (RuntimeException|Throwable $e) {
            // Fail closed: an unreadable register yields INCONCLUSIVE, never PASS.
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Series '%s' could not be read (%s). Absence of evidence is not PASS.",
                    $identifier->series()->prefix(),
                    $e->getMessage(),
                ),
                $identifier->value(),
            );
        }

        return $this->validator->validate($identifier, $contents);
    }
}
