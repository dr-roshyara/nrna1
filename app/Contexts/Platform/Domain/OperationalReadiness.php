<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * The facts that must hold, checked in the organisation's own database at the moment of the `operational`
 * transition, never assumed from earlier steps:
 * - L-1 (delta §3.7, D-219 (1)): the Membership ledger tables, the identity row equal to the registry id, the ledger
 *   clock row. "Migrations current" is the aggregate's own schema state, so it is not repeated here;
 * - D-193 R5 F-07: at least one platform contact with an active account.
 *
 * The domain states the rule; gathering the facts belongs to the application and infrastructure layers (task 5).
 */
final readonly class OperationalReadiness
{
    public function __construct(
        public bool $ledgerSchemaPresent,
        public bool $identityRowMatches,
        public bool $ledgerClockRowPresent,
        public int $activeAccountContacts,
    ) {
        if ($activeAccountContacts < 0) {
            throw new \InvalidArgumentException('A contact count cannot be negative.');
        }
    }

    public function isComplete(): bool
    {
        return $this->missing() === [];
    }

    /** @return list<string> each precondition that does not hold (task 5c, T-13: a refusal names them) */
    public function missing(): array
    {
        return array_keys(array_filter([
            'ledger schema' => !$this->ledgerSchemaPresent,
            'organisation identity' => !$this->identityRowMatches,
            'ledger clock row' => !$this->ledgerClockRowPresent,
            'a platform contact with an active account' => $this->activeAccountContacts < 1,
        ]));
    }
}
