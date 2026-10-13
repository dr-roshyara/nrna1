# PBDIGIT-283: Tests may reset only a test database (guard after the `publicdigit` wipe)

**Type:** Defect, test infrastructure / data safety · **Epic:** none · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from an incident during `PBDIGIT-274`. **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · tests green · committed `772d06a5d` (guard) and `fcde1b76b` (record) · a default-suite run on `nrna_test` is still pending |
| **Severity** | high: data loss on the development database (the PO decided no restore) |

## Defect
- `Tests\TestCase` resets the connected database with `migrate:fresh` on PostgreSQL (no per-test transaction).
- **Nothing checked which database that was.** Safety depended entirely on `phpunit.xml` pinning `nrna_test`.
- So **any run without that configuration wiped the `.env` database:** `--no-configuration`, an IDE runner with a different configuration, another session.
- The class comment wrongly promised "ZERO data corruption possible".

**Incident 2026-10-13:** two such runs by Claude wiped `publicdigit`. Root cause, timing and evidence: `docs/publicdigit/testing/evidence/2026-10-13-test-database-guard/README.md`.

## Fix (implemented)
- **`tests/Support/TestDatabaseSafety`:** only `nrna_test` or `pbdigit274_isolated_test` (PostgreSQL) may be reset; anything else is refused before any change.
- **`Tests\TestCase::setUpTraits()`** checks the actual `current_database()` before `RefreshDatabase` / `DatabaseMigrations` / `DatabaseTruncation`.
- **The false comment is corrected.**
- **`tests/Architecture/DatabaseResetIsGuardedTest`** keeps every database-resetting test behind a guarded base.

## Acceptance
- [x] The exact incident command is refused, and the target is untouched (end-to-end, scratch SQLite: RED 5 tables → GREEN 0 bytes).
- [x] Legitimate runs still work (isolated configuration: pass; whole isolated suite 94 / 490).
- [x] Every database-resetting test class inherits a guarded base (architecture test).
- [ ] **The default suite** (`phpunit.xml`, `nrna_test`) runs with the guard. Not run here: `nrna_test` is off limits for this session's work. The guard allows `nrna_test` by design, but no default-suite run has confirmed it.

## Follow-ups (not in scope)
- **Two plain-PHPUnit tests carry an inert `RefreshDatabase`:** `IpEvidenceDeterminismTest`, `IpEvidenceTopologyEquivalenceTest`. Remove the trait.
- **If a new test database is introduced**, add it to `TestDatabaseSafety::RESETTABLE` deliberately; never widen the rule to a pattern.
