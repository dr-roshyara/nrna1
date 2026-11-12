# 20: Ledger slice 1, step 6 — the completeness point (`LedgerClockCloser`)

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 6, D-4; tests 4, 6, 10, 11 at the closer level) · **Decisions:** D-209 §2, C1/C2 spec §4, §11 K-2 (A1–A3) · **Commit:** `afad064ec`

## Purpose

**The business invariant:** once the ledger reports `closedThrough = b`, **no fact committed later may have `recordedAt ≤ b`.** Only then can a historical answer "as known at τ ≤ b" (C2, step 8) never change after it is given.

It holds because the two writers of the clock row cooperate:
- **every append** takes the organisation's clock row `FOR UPDATE` and refuses `t ≤ closed_through` (guide 18);
- **the closer** advances `closed_through` only while it holds that row.

## Where it fits

```text
Infrastructure  LedgerClockCloser::closeOrRead(TenantId): ?Instant   the step
                DueScheduledResignations (port) / NoDueScheduledResignations   hook (a); slice 5 implements it
                LedgerCloseInsideTransactionRefused, ScheduledExecutionFailed
```

Domain and Application are unchanged. C2 (step 8) will reach the closer through an Application port.

## How it works

| # | Step |
|---|---|
| 0 | refuse if called inside a transaction |
| 1 | ensure the clock row: a **plain read** first; `INSERT … ON CONFLICT DO NOTHING` only if no row exists |
| 2 | own transaction, `READ COMMITTED`: `SELECT … FOR UPDATE NOWAIT` |
| 3 | **free:** `t` = the database clock (guide 17) |
| 4 | **regression** (`t < last_recorded_at` or `t < closed_through`): **no advance**; logged `membership.ledger_clock.regression` |
| 5 | **(a)** `DueScheduledResignations::executeDue($db, $org, $t)`. **If it throws, the close rolls back** (logged `…scheduled_execution_failed`) |
| 6 | **(b)** `closed_through = max(closed_through, t)`; commit |
| 7 | **busy** (SQLSTATE `55P03`): PostgreSQL aborted that transaction; Laravel's `transaction()` rolls it back, and it is **never reused** |
| 8 | **always last:** a **new statement outside any transaction** reads the committed `closed_through` and returns it (`null` = never closed) |

**Equality is allowed:** `t = last_recorded_at` may close (a fact at exactly `t` is already committed).

**Why a stale value is safe:** the busy path returns an older committed boundary. That makes C2 refuse a τ beyond it (`BOUNDARY_NOT_CLOSED`). **It never yields a false "complete".**

## Use

```php
$closer = new LedgerClockCloser($connection, new PostgresLedgerClockReader());   // the organisation's connection
$b = $closer->closeOrRead($tenant);   // ?Instant
```

Call it **outside** any transaction, and read facts **after** it returns, in new statements (A3). In slice 1 nothing calls it in production yet: C2 (step 8) will; a scheduler can call it independently of reads (plan D-2 G-2, cadence an implementation choice).

## Testing

| Test | Proves |
|---|---|
| `PostgresLedgerClockCloserTest` (7) | <ul><li>LC-6: no advance on regression, stored value unchanged; may close at equality;</li><li>no facts: closes at the reading;</li><li>**busy: the committed value, never the holder's uncommitted one; the next statement works** (no reuse);</li><li>the hook runs only when free and not regressed; a failing hook rolls back;</li><li>refused inside a transaction; a REPEATABLE READ session default does not change it</li></ul> |
| `PostgresClosedThroughRaceTest` (2, forked processes, real clock) | <ul><li>**3 writers × 6 admits and 2 readers × 25 closes interleaved:** every reported boundary `b` is re-checked against the final table, and **the facts with `recordedAt ≤ b` are exactly those visible when `b` was reported**; boundaries per reader never move back; the boundary advanced during the run;</li><li>**a reader between a writer's lock and its commit** gets the committed boundary; the writer then records after it; a later close covers it</li></ul> |

```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter 'PostgresLedgerClockCloserTest|PostgresClosedThroughRaceTest'
```

## Pitfalls

- **`INSERT … ON CONFLICT DO NOTHING` waits** while another transaction holds an uncommitted update of the conflicting row. The first version of the closer did this unconditionally and **blocked** behind a writer (found by the busy test). **Ensure a row with a plain read first** wherever you must not wait.
- **Never read facts inside the closing transaction** or before it commits.
- **Never run the closer inside your own transaction** (refused): a `NOWAIT` failure aborts the whole transaction.
- The race test proves the property **for its interleavings**. It is evidence, not a proof of every schedule; the guarantee itself rests on the lock protocol above.

**Traceability:** D-209 §2, CR-01 :28, spec §4 / K-2 A1–A3, LC-3, LC-6 · plan task 6 · PBDIGIT-343.
