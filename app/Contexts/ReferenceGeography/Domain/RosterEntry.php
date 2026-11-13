<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * One append-only roster write, with its gap-free roster version. A steward or reviewer write names its subject and
 * the person who made it; an Identity notice names its merge id and (for a merge) the references it links.
 */
final readonly class RosterEntry
{
    /** @param list<PersonReference> $merged */
    public function __construct(
        public int $version,
        public RosterEntryKind $kind,
        public ?PersonReference $subject = null,
        public ?PersonReference $by = null,
        public ?RevisionId $matter = null,
        public ?string $mergeId = null,
        public array $merged = [],
    ) {
    }
}
