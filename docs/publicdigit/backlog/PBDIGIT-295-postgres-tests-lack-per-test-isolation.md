# PBDIGIT-295: Tests on PostgreSQL share data, because the per-test transaction is switched off

**Type:** Test-suite health · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-283 R6 positive runs (the first full default-suite runs on `nrna_test` since the 2026-10-13 incident). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **verified in source** (`tests/TestCase.php`); the link to the observed failures is **inferred** |
| **Scope** | unrelated to PBDIGIT-283's guards; nothing fixed |

## Finding
- **`Tests\TestCase::beginDatabaseTransaction()`** returns early on PostgreSQL ("Skip transaction-based isolation for PostgreSQL tests"; `tests/TestCase.php:49-62`).
- **`RefreshDatabase` runs `migrate:fresh` only once per process** (Laravel's `RefreshDatabaseState`). So without a per-test transaction, **rows written by one test remain for the next.**
- **Observed:** 158 unique violations (`SQLSTATE[23505]`, e.g. `Key (email)=(test@example.com) already exists`) across the Unit and Feature runs, consistent with leftover data. Evidence: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-positive/` (`stage-2-unit.txt`, `stage-3-feature-rerun.txt`, `stage-3-architecture.txt`).
- **The comment's stated reason** (nested-transaction/savepoint errors) isn't re-verified.

## Impact
- Results depend on test order; failures cascade; legitimate tests fail for data they didn't create.

## Proposed direction (nothing decided)
1. **Reproduce** the original savepoint problem on PostgreSQL with the current Laravel version.
2. **Re-enable per-test transactions** if possible, or use `DatabaseTruncation` or a per-class reset where transactions can't be used. Keep the PBDIGIT-283 reset guard in every path.
3. **Measure** the effect on suite duration.

## Acceptance criteria
1. A test proves two tests creating the same unique row both pass in either order.
2. The unique-violation count from leftover data drops to 0 in a full Unit + Feature run.
3. The PBDIGIT-283 guards stay in force (`DatabaseResetIsGuardedTest`, the R3 hook, `assertResettable`).
4. Runs only under an authorised test-database run.
