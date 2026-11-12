# 17: Ledger slice 1, step 3 — the database clock reader

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 3, D-1; proof LC-5) · **Decision:** D-209 · **Commit:** `78af8627a`

## Purpose

**D-209: every record time comes from the organisation database's clock, never from the application server.** This step introduces the one way the ledger reads time.

## Where it fits (hexagonal)

```text
Infrastructure  LedgerClockReader            port: now(Connection $db): Instant
                PostgresLedgerClockReader    the only production implementation: SELECT clock_timestamp()
Test support    tests/Support/ScriptedLedgerClockReader   TEST ONLY: scripted readings
```

**Domain and Application are untouched and stay Laravel-free.** The reader needs a Laravel `Connection`, so it lives in Infrastructure.

## How it works

```php
final class PostgresLedgerClockReader implements LedgerClockReader
{
    public function now(Connection $db): Instant
    {
        return Instant::of((int) $db->selectOne('SELECT ' . InstantMicros::selectExpression('clock_timestamp()') . ' AS us')->us);
    }
}
```

- **`clock_timestamp()`, not `now()`:** `now()` is the transaction's start time and does not advance inside a transaction.
- It reads **on the connection it is given**, so in slice 2 it reads the organisation's own database.
- `PostgresMemberRepository` now takes `(Connection $db, LedgerClockReader $clock)` instead of a `\Closure`.

## Testing

- `tests/Architecture/MembershipLedgerClockArchitectureTest.php`:
  - the repository constructor is `[Connection, LedgerClockReader]`;
  - the only implementation in `app/` is `PostgresLedgerClockReader`;
  - it uses `clock_timestamp()`, not `microtime` or `now()`.
- `tests/Feature/Contexts/Membership/Organisational/PostgresLedgerClockReaderTest.php`: the reading lies between two database readings; it advances inside one transaction.
- **Race tests use the real reader;** other tests use the scripted one.

## Pitfalls

- **Never bind `ScriptedLedgerClockReader` outside tests** (the architecture test fails).
- Read the clock **after** taking the organisation's clock-row lock (guide 18), never before.

**Traceability:** D-209 · plan task 3, D-1 · LC-5 · PBDIGIT-343.
