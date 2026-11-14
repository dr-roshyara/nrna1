<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

/**
 * D-193 F-07 (task 5c): how many of the given platform contacts (Identity account ids) have a KNOWN and ACTIVE
 * account. Platform's own port; the only adapter reaches Identity (baseline §9 pattern). Grants nothing.
 */
interface ContactAccounts
{
    /**
     * @param list<string> $accountIds
     * @throws ContactAccountsUnavailable Identity cannot answer: never counted as active
     */
    public function activeCount(array $accountIds): int;
}
