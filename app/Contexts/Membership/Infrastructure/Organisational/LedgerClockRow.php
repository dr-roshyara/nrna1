<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;

/**
 * Creates an organisation's ledger clock row (PBDIGIT-343, plan D-2 / D-4, deviation 4) without an unbounded wait.
 *
 * INSERT … ON CONFLICT DO NOTHING waits for any transaction holding an uncommitted change of the conflicting row
 * (an uncommitted update, or an uncommitted insert of the same key). So:
 * - a plain read first: an existing row is never inserted again, and a plain read never waits (MVCC);
 * - the row is created only by a statement committed on its own, never inside an append or close transaction,
 *   so no long-running transaction can ever hold an uncommitted new clock row;
 * - with $lockTimeoutMs (the closer), even that short wait is bounded; on timeout it answers false (fail closed).
 * Must be called outside any transaction.
 */
final class LedgerClockRow
{
    private const LOCK_NOT_AVAILABLE = '55P03';

    /** @return bool true when the row exists and is committed; false when another session's creation is still in flight */
    public static function ensure(Connection $db, TenantId $organisation, ?int $lockTimeoutMs = null): bool
    {
        if ($db->selectOne('SELECT 1 AS one FROM org_membership_ledger_clocks WHERE organisation_id = ?', [$organisation->value()]) !== null) {
            return true;
        }
        $insert = static fn () => $db->statement(
            'INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES (?) ON CONFLICT (organisation_id) DO NOTHING',
            [$organisation->value()],
        );
        if ($lockTimeoutMs === null) {
            $insert();

            return true;
        }
        try {
            $db->transaction(static function () use ($db, $insert, $lockTimeoutMs): void {
                $db->statement(sprintf("SET LOCAL lock_timeout = '%dms'", $lockTimeoutMs));
                $insert();
            });
        } catch (QueryException $e) {
            if ($e->getCode() !== self::LOCK_NOT_AVAILABLE) {
                throw $e;
            }

            return false;
        }

        return true;
    }
}
