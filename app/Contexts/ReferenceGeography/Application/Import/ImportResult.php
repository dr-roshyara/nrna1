<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Import;

/**
 * G-8: what an import produced: one DRAFT revision (its id), how many claims and evidence documents it holds, and the
 * validation preview (the rules violated, empty when clean). Nothing is assessed, submitted or published.
 */
final readonly class ImportResult
{
    /** @param list<string> $violations "RULE: detail" */
    public function __construct(
        public string $revisionId,
        public int $claims,
        public int $evidence,
        public array $violations,
    ) {
    }
}
