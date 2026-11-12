# 01: PostgreSQL lock waits — `INSERT … ON CONFLICT`, `FOR UPDATE NOWAIT` and timeouts

**Origin:** `PBDIGIT-343` (Membership ledger clock, guides `membership/18`, `membership/20`) · **Evidence:** `tests/Feature/Contexts/Membership/Organisational/PostgresLedgerClockRowInitialisationTest.php`, `PostgresLedgerClockCloserTest.php` (PostgreSQL 15.19) · **Commits:** `afad064ec`, `9477113e1`

## Purpose

Code that must **never wait** (a reader answering from committed data, a step that must stay available under contention) can still block on statements that look harmless. These rules apply to every context using PostgreSQL.

## 1. `INSERT … ON CONFLICT DO NOTHING` can wait

It does not "just skip". If the conflicting row has an **uncommitted** change by another transaction, PostgreSQL must know whether that change commits, so the insert **waits for that transaction to end**. This happens when:
- **an uncommitted update** of the existing row is in flight; or
- **an uncommitted insert** of the same key is in flight (someone else creating the row).

**Rules:**
- **Read first.** A plain `SELECT` never waits (MVCC). Insert only if the row is absent.
- **Never create a shared row inside a long transaction.** Create it in a statement committed on its own, before the transaction that uses it.
- **Bound what remains.** Where waiting is unacceptable, run the insert in its own short transaction with `SET LOCAL lock_timeout = '…ms'`, and fail closed on `55P03`.

## 2. `FOR UPDATE NOWAIT` aborts the whole transaction

On a busy row it raises `55P03 lock_not_available`, and **PostgreSQL then aborts the transaction:** every later statement in it fails until rollback.

**Rules:**
- Run `NOWAIT` **in a transaction of its own**, never inside a caller's transaction (refuse nesting).
- After the failure, **roll back** (Laravel's `DB::transaction()` does) and **read again in a new statement**, outside any transaction.
- **Never reuse** the aborted transaction.

## 3. Timeouts in tests: fail, don't hang

- `SET statement_timeout = '3s'` on the test connection makes an unexpected wait **fail** with `57014` instead of hanging the whole suite.
- `SET lock_timeout = '…ms'` makes a lock wait fail with `55P03`. Use it to **demonstrate** a wait in a regression test.
- Reset both in `tearDown()`.

## 4. Codes

| SQLSTATE | Meaning | Typical handling |
|---|---|---|
| `55P03` | lock not available (`NOWAIT`, or `lock_timeout` reached) | treat as busy; read committed data in a new statement; fail closed |
| `57014` | statement cancelled (`statement_timeout`) | in tests: the code waited when it must not |

## Pitfalls

- Laravel's `QueryException::getCode()` returns the SQLSTATE string (`'55P03'`). Compare it as a string.
- `now()` in SQL is the transaction start time. Use `clock_timestamp()` when time must advance inside a transaction (guide `membership/17`).

**Traceability:** PBDIGIT-343 plan deviation 4; D-209.
