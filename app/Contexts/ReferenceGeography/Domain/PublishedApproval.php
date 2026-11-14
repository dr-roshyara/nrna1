<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A second-person publication as ApprovalDefects needs it (design revision 3 §4, §17): its position, revision, content
 * version and digest, the approver COUNTED at publication (derived by replaying mayPublish, never stored), and the
 * revision's excluded persons. After a confirmation the counted approver is the confirmer (FindingLifecycle).
 */
final readonly class PublishedApproval
{
    /** @param list<PersonReference> $excluded */
    public function __construct(
        public int $position,
        public RevisionId $revision,
        public int $contentVersion,
        public string $digest,
        public PersonReference $approver,
        public array $excluded,
    ) {
    }
}
