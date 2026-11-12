<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * The ledger's closedThrough step (D-209 §2; C1/C2 spec §4, §11 K-2; PBDIGIT-343 plan D-4).
 *
 * closedThrough means: every fact with recordedAt ≤ closedThrough is committed and visible, and none can still be
 * committed later (CR-01 :28). It holds because every append takes the same clock row FOR UPDATE and refuses
 * t ≤ closed_through (PostgresMemberRepository).
 *
 * - Free lock: t = the database clock; if t is before last_recorded_at or closed_through, no advance (flagged);
 *   otherwise (a) execute due scheduled resignations, (b) closed_through = max(closed_through, t); commit.
 * - Busy lock (FOR UPDATE NOWAIT, SQLSTATE 55P03): PostgreSQL aborts that transaction; it is rolled back and never
 *   reused. The last committed value is then read in a new statement.
 * - A failed execution in (a) rolls the close back; the stored value is returned unchanged.
 * Returns the committed boundary, or null when the organisation has never been closed.
 */
final class LedgerClockCloser
{
    private const LOCK_NOT_AVAILABLE = '55P03';

    private readonly DueScheduledResignations $due;

    public function __construct(
        private readonly Connection $db,
        private readonly LedgerClockReader $clock,
        ?DueScheduledResignations $due = null,
    ) {
        $this->due = $due ?? new NoDueScheduledResignations();
    }

    public function closeOrRead(TenantId $organisation): ?Instant
    {
        if ($this->db->transactionLevel() > 0) {
            throw LedgerCloseInsideTransactionRefused::atLevel($this->db->transactionLevel());
        }
        // Ensure the row without ever waiting on a writer: INSERT … ON CONFLICT waits while another transaction has an
        // uncommitted update of the existing row, so it runs only when a plain (non-blocking) read finds no row.
        $exists = $this->db->selectOne('SELECT 1 AS one FROM org_membership_ledger_clocks WHERE organisation_id = ?', [$organisation->value()]);
        if ($exists === null) {
            $this->db->statement(
                'INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES (?) ON CONFLICT (organisation_id) DO NOTHING',
                [$organisation->value()],
            );
        }

        try {
            $this->db->transaction(function () use ($organisation): void {
                $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                $row = $this->db->selectOne(
                    'SELECT ' . InstantMicros::selectExpression('last_recorded_at') . ' AS last_us, '
                    . InstantMicros::selectExpression('closed_through') . ' AS closed_us
                     FROM org_membership_ledger_clocks WHERE organisation_id = ? FOR UPDATE NOWAIT',
                    [$organisation->value()],
                );
                $t = $this->clock->now($this->db);
                $last = $row->last_us === null ? null : (int) $row->last_us;
                $closed = $row->closed_us === null ? null : (int) $row->closed_us;
                if (($last !== null && $t->ordinal() < $last) || ($closed !== null && $t->ordinal() < $closed)) {
                    Log::warning('membership.ledger_clock.regression', ['organisation_id' => $organisation->value(), 'reading_us' => $t->ordinal(), 'last_recorded_at_us' => $last, 'closed_through_us' => $closed, 'step' => 'close']);

                    return;   // no advance; nothing written
                }
                try {
                    $this->due->executeDue($this->db, $organisation, $t);
                } catch (\Throwable $e) {
                    throw new ScheduledExecutionFailed($e);
                }
                $this->db->update(
                    'UPDATE org_membership_ledger_clocks SET closed_through = ? WHERE organisation_id = ?',
                    [InstantMicros::toTimestamp(Instant::of(max($closed ?? PHP_INT_MIN, $t->ordinal()))), $organisation->value()],
                );
            });
        } catch (QueryException $e) {
            if ($e->getCode() !== self::LOCK_NOT_AVAILABLE) {
                throw $e;
            }
            // busy: the aborted transaction was rolled back by transaction(); read the committed value below
        } catch (ScheduledExecutionFailed $e) {
            Log::error('membership.ledger_clock.scheduled_execution_failed', ['organisation_id' => $organisation->value(), 'error' => $e->getPrevious()?->getMessage()]);
        }

        // A new statement, outside any transaction: the last committed value (READ COMMITTED; A3).
        $closed = $this->db->selectOne(
            'SELECT ' . InstantMicros::selectExpression('closed_through') . ' AS closed_us FROM org_membership_ledger_clocks WHERE organisation_id = ?',
            [$organisation->value()],
        )->closed_us;

        return $closed === null ? null : Instant::of((int) $closed);
    }
}
