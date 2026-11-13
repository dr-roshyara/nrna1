<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The complete state of one Candidate, for storage. Back to an aggregate only through Candidate::fromSnapshot. */
final readonly class CandidateSnapshot
{
    /** @param list<string> $evidence */
    public function __construct(
        public CandidateId $id,
        public CandidateState $state,
        public CandidateSubmitter $submitter,
        public string $summary,
        public ?ReferenceUnitId $subject,
        public array $evidence,
        public PersonReference $recordedBy,
        public ?PersonReference $verificationBy,
        public ?PersonReference $decidedBy,
        public ?RevisionId $revision,
        public ?CandidateId $linkedTo,
        public ?string $reason,
    ) {
    }
}
