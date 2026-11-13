<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The complete state of one Objection, for storage. Back to an aggregate only through Objection::fromSnapshot. */
final readonly class ObjectionSnapshot
{
    /** @param list<string> $evidence */
    public function __construct(
        public ObjectionId $id,
        public ObjectionState $state,
        public string $organisationId,
        public ClaimId $target,
        public string $statement,
        public array $evidence,
        public PersonReference $recordedBy,
        public ?ObjectionAssessment $assessment,
        public ?PersonReference $assessedBy,
        public ?PersonReference $decidedBy,
        public ?RevisionId $revision,
        public ?string $reason,
    ) {
    }
}
