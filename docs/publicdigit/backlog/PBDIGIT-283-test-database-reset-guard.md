# PBDIGIT-283: Tests may reset only a test database (guard after the `publicdigit` wipe)

**Type:** Defect, test infrastructure / data safety · **Epic:** none · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from an incident during `PBDIGIT-274`. **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | **`DONE` (2026-10-14), closure approved by the Principal:** `docs/publicdigit/reviews/2026-10-14-pbdigit283-closure-record.md`. Guard `772d06a5d` (record `fcde1b76b`); remediation extension R1–R6 (plan `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md`). Deviations D-1..D-6 and follow-ups `PBDIGIT-290`..`PBDIGIT-293` in the closure record. **PBDIGIT-286 G-ISO may now be considered** (separate authorisation). |
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
- [x] **The default suite** (`phpunit.xml`, `nrna_test`) runs with the guard: R6 positive stages 1–3, 2026-10-14 (`b6b7d67fa`, `fd573dd40`, `764d9ea2f`), with no legitimate test refused.

## Follow-ups (not in scope)
- **Two plain-PHPUnit tests carry an inert `RefreshDatabase`:** `IpEvidenceDeterminismTest`, `IpEvidenceTopologyEquivalenceTest`. Remove the trait.
- **If a new test database is introduced**, add it to `TestDatabaseSafety::RESETTABLE` deliberately; never widen the rule to a pattern.

## Remediation extension (added 2026-10-14; from the PBDIGIT-286 database-safety review; recommendation, not authorised)
**Origin:** a read-only review during PBDIGIT-286. No test was run, no database was accessed, and `.env` wasn't read. Session `.claude/sessions/2026-10-14.md`.

**Most likely mechanism of the 2026-10-13 wipe** (high confidence for the mechanism; not reproduced):
- `--no-configuration --bootstrap vendor/autoload.php` skipped both pins to the test database: `phpunit.xml` `<server DB_DATABASE=nrna_test>` and `tests/bootstrap-test-database.php`.
- Laravel then used the `.env` database.
- `Tests\TestCase` always applies `RefreshDatabase` (`tests/TestCase.php:18`), which on PostgreSQL runs `migrate:fresh` (`:49-55`) on the **default** connection.

**Findings in source:**
- **The guard checks only the default connection, and only after Laravel has booted** (`tests/TestCase.php:28-40`, `TestDatabaseSafety::assertResettable`).
- **`pgsql_audit` and `pgsql_geo` aren't pinned in the default suite.** `tests/bootstrap-test-database.php` and `phpunit.xml` set only `DB_*`, and `pgsql_audit` resolves to `env('DB_AUDIT_DATABASE', env('DB_DATABASE'))` (`config/database.php:101-105`). `ElectionSecurityEvent` writes through `pgsql_audit`.
- **The isolated suite pins all three prefixes** (`DB_`, `DB_AUDIT_`, `DB_PGSQL_GEO_`; `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php:38`).
- **Tests that boot Laravel without a guarded base** (for example the dead-socket unit tests) are safe only by convention.

**Unknowns:**
- **U-1:** where `pgsql_audit`/`pgsql_geo` point during default-suite runs (depends on `.env`);
- **U-2:** enforced coverage of tests that boot Laravel without a guarded base;
- **U-3:** the default-suite run with the guard (the open item above);
- **U-4:** whether any migration or reset can reach the audit or geo databases.

**Acceptance criteria:**
- [x] **1. Every connection pinned** (R2 `6d394e6e1`, R3 `17167dec3`; `DB_URL` gap → `PBDIGIT-293`): every connection a test can use (default, `pgsql_audit`, `pgsql_geo`) is pinned to an approved test database, or verified by the guard.
- [x] **2. Fail-closed bootstrap** (R3 `17167dec3`): the safety check runs **before any database query or destructive setup** (at bootstrap or application creation, not after boot). It refuses unless `APP_ENV=testing` **and** every connection's configured database is approved; otherwise nothing is touched.
- [x] **3. Enforced coverage** (R1 `164f74066`, R4 `b7138fafa`): an architecture test requires every test that boots Laravel without a guarded base to neutralise or pin its connections explicitly.
- [x] **4. Negative evidence** (G-RUN-NEG run 2, `812538b62`) (**separate authorisation** to run): a `--no-configuration` run of a `Tests\TestCase` test is refused before any query, with nothing changed.
- [x] **5. Positive evidence** (G-RUN-POS stages 1–3; no PBDIGIT-283 refusal or misdirection; the suites aren't green, D-4) (**separate authorisation** to run): the default suite runs against the approved test database (closes U-3).
- [x] **6. No reach into audit or geo** (R5 `ac66fd940`): verification (code review plus a guarded check) that no migration or reset can affect non-test audit or geography databases (closes U-4).

**Until this extension is done:** no PHPUnit, Artisan migration/reset or database operation for PBDIGIT-286. PBDIGIT-286's **G-ISO** stays closed (plan `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` §13).

**2026-10-14: extension done.** Closure approved; G-ISO may be considered under its own authorisation.
