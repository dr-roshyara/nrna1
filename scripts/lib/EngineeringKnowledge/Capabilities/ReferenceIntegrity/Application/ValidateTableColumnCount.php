<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\AssessesTableColumnCount;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use RuntimeException;
use Throwable;

/**
 * Application service — the S4 use case over table column-count consistency.
 *
 * Orchestrates: read the document through the port → apply the domain service →
 * return one verdict. It contains no policy of its own (DDD: application services
 * orchestrate; business meaning stays in the domain).
 *
 * AC-1 constraint, inherited: this capability CANNOT SELF-ISSUE. It is invoked;
 * it never schedules itself.
 */
final readonly class ValidateTableColumnCount
{
    public function __construct(
        private DocumentTableReader $reader,
        private AssessesTableColumnCount $validator = new AssessesTableColumnCount(),
    ) {
    }

    public function handle(string $path): Assessment
    {
        try {
            $contents = $this->reader->read($path);
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

        return $this->validator->validate($contents, $path);
    }
}
