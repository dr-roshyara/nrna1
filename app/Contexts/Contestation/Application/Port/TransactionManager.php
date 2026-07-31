<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Port;

/**
 * Application port for transaction ownership: the application layer defines the
 * boundary; infrastructure executes the mechanics. Aggregates and repositories
 * never manage transactions.
 *
 * Contestation-LOCAL for the same reason as {@see IdentityGenerator} — R-1/R-2
 * forbid importing Adjudication's identical port.
 *
 * WP-5 needs it because `route()` saves the aggregate AND writes its outbox row;
 * ADR-T1 requires those to be one transaction, and
 * `EloquentChallengeRepository::save()` has no transaction of its own.
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
