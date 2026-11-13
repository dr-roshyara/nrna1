<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The complete state of a Revision, for persistence (G-4b). Produced by Revision::snapshot(); turned back into an
 * aggregate only by Revision::fromSnapshot(), which refuses a state the aggregate could not have reached.
 */
final readonly class RevisionSnapshot
{
    /**
     * @param list<Claim> $claims
     * @param list<EvidenceVersion> $evidence
     * @param list<Assessment> $assessments assessments of the version being prepared or the current one
     * @param list<array{int, Approval}> $approvals every approval ever given: [the version it named, approval]
     * @param list<PersonReference> $preparers
     * @param list<PersonReference> $excluded everyone who prepared, assessed or submitted any version
     * @param list<array{PersonReference, JudgmentReason}> $judgmentFlags
     */
    public function __construct(
        public RevisionId $id,
        public RevisionState $state,
        public array $claims,
        public array $evidence,
        public array $assessments,
        public array $approvals,
        public array $preparers,
        public array $excluded,
        public ?ContentVersion $current,
        public ?PersonReference $assessor,
        public array $judgmentFlags,
        public ?ValidationResult $validation,
    ) {
    }
}
