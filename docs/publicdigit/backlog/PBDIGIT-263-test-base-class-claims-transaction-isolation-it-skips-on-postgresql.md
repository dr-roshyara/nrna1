# PBDIGIT-263: the test base class claims per-test transaction isolation that it skips on PostgreSQL

**Type:** Technical debt (test infrastructure; safety documentation) · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Found during `PBDIGIT-260` (Slice 1b review observation O-1); outside that story's scope.

| | |
|---|---|
| **Status** | `OPEN — technical debt` · not authorised |
| **Found in** | `docs/publicdigit/reviews/2026-10-09-membership-slice1b-implementation-review.md` §8 O-1 |

## Evidence

- `tests/TestCase.php:28-30` returns early from `beginDatabaseTransaction()` when the driver is `pgsql`. **No per-test transaction is opened on PostgreSQL**, which is the only supported engine (DB-1).
- `tests/TestCase.php:29` comments "RefreshDatabase uses migrate:fresh for PostgreSQL isolation". But `RefreshDatabase` runs `migrate:fresh` **once per PHP process**, guarded by `RefreshDatabaseState::$migrated` (`vendor/laravel/framework/src/Illuminate/Foundation/Testing/RefreshDatabase.php:65-70`). It does not run once per test.
- `tests/TestCase.php:55-63` states: "Each test runs in its own transaction", "Transaction is rolled back after test completes" and "ZERO data corruption possible, even if wrong database is targeted". **On PostgreSQL none of these holds.**
- The project `CLAUDE.md` ("How RefreshDatabase Works") repeats the per-test rollback claim.

## Consequences

1. **Data persists between tests in one process.** Tests that assume an empty table or a globally unique value can pass or fail depending on order. The Slice 1b tests isolate themselves by creating a fresh organisation per test.
2. **The safety statement is false in the dangerous direction.** If the testing environment points at the wrong database, the first test runs `migrate:fresh` against it. The comment tells readers this cannot corrupt data. The protection that actually exists is the testing configuration (`phpunit.xml`, database `nrna_test`), not a transaction.

## Proposed handling (needs authorisation; not done here)

- Correct the comments in `tests/TestCase.php` (and the `CLAUDE.md` passage) to describe the real behaviour.
- Decide separately whether to restore per-test isolation on PostgreSQL. The original "already in transaction" problem was recorded under an older Laravel version and would need to be re-measured on Laravel 11 before deciding.
- Consider a guard that refuses to run the suite unless the database name is the testing database. That would turn the documented guarantee into an enforced one.

**Not in scope of PBDIGIT-260.** No code changed under this item.
