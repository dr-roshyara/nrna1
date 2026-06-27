<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

/**
 * Application port for transaction ownership: the application layer defines the
 * boundary; infrastructure executes the mechanics (DB transaction). Aggregates
 * and repositories never manage transactions (Constitution §Transaction).
 */
interface TransactionManager
{
    /**
     * Run $work inside a single transaction; commit on success, roll back on
     * throw. Returns whatever $work returns.
     *
     * @template T
     * @param callable(): T $work
     * @return T
     */
    public function transactional(callable $work): mixed;
}
