<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/**
 * The closedThrough step runs in its own transaction (PBDIGIT-343 plan D-4): inside a caller's transaction a NOWAIT
 * failure would abort the caller's work, and the close could not commit before the caller reads (A3).
 */
final class LedgerCloseInsideTransactionRefused extends \LogicException
{
    public static function atLevel(int $level): self
    {
        return new self("The ledger closedThrough step must not run inside a transaction (level $level).");
    }
}
