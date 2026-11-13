<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The outcome of a governance check at one roster version. When a second person is relied on, it records
 * "independent as known to Identity at roster version v" (completion §5a), never "verified distinct".
 */
final readonly class GovernanceDecision
{
    public bool $allowed;
    public ?string $independence;

    /** @param list<GovernanceRefusal> $refusals */
    public function __construct(
        public int $rosterVersion,
        public GovernanceRule $rule,
        public array $refusals = [],
        public ?PersonReference $secondPerson = null,
    ) {
        $this->allowed = $refusals === [];
        $this->independence = $secondPerson === null ? null : "independent as known to Identity at roster version {$rosterVersion}";
    }
}
