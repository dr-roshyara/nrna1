# PBDIGIT-283 closure record: tests may reset only a test database

**Status: APPROVED by the Principal, 2026-10-14. PBDIGIT-283 is `DONE`.** Engineering supplied the evidence; acceptance is the Principal's (EP-02).

| | |
|---|---|
| **Story** | `PBDIGIT-283` (`docs/publicdigit/backlog/PBDIGIT-283-test-database-reset-guard.md`): the guard (2026-10-13) plus the remediation extension (2026-10-14) |
| **Plan** | `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (r2, approved 2026-10-14; D-283-1..4; C-1..C-3) |
| **Guides** | `developer_guide/testing/00_index.md`, `01`–`06` |
| **Evidence** | `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-negative/`, `…/2026-10-14-pbdigit283-r6-positive/`; the 2026-10-13 incident evidence `docs/publicdigit/testing/evidence/2026-10-13-test-database-guard/` |
| **Outcome** | **`DONE`** (guard and remediation extension), with the deviations and follow-ups below. Approved 2026-10-14 |

## 1. What was wrong (2026-10-13)
- **The incident:** a `--no-configuration` run skipped `phpunit.xml`'s pinning. Laravel used the `.env` database, and `Tests\TestCase`'s `RefreshDatabase` ran `migrate:fresh` on it, wiping the development database `publicdigit` (no restore, by the PO's decision).
- **The first guard (`772d06a5d`)** checked only the default connection, and only after boot.
- **The 2026-10-14 review found further gaps:** unpinned audit and geo connections, and boot paths that bypassed every check.

## 2. What now protects a test run
| Layer | Step | Commit | Guide |
|---|---|---|---|
| Shared target rules + database-free marker (all targets at a dead socket before boot) | R1 | `164f74066` | 01 |
| Default suites pin `pgsql`, `pgsql_audit`, `pgsql_geo` to `nrna_test`, `DATABASE_URL` empty | R2 | `6d394e6e1` | 02 |
| Pre-provider check: every Laravel-booting test refuses an unsafe loaded configuration before any provider (also fixes an R1 test) | R3 | `17167dec3` | 03 |
| Every Laravel boot goes through `CreatesApplication` and has a cover (4 bypassing tests migrated) | R4 | `b7138fafa` | 04 |
| No migration names a connection; `app/` reaches only confined connections | R5 | `ac66fd940` | 05 |
| Negative-evidence harness (Option A), correction for Laravel's merged `mariadb` entry | R6 | `eb18ccfa9`, `4ce631f39` | 06 |
| `TestDatabaseSafety::assertResettable` on the actual `current_database()` before a reset (2026-10-13 guard, kept) | — | `772d06a5d` | 00 |

## 3. Acceptance criteria and evidence
**Original item, open box: "the default suite runs with the guard":** **met.** R6 positive stages 1–3 ran `phpunit.xml` on `nrna_test` with the guard active, and no legitimate test was refused (`b6b7d67fa`, `fd573dd40`, `764d9ea2f`).

**Remediation extension:**

| # | Criterion | Evidence | Status |
|---|---|---|---|
| 1 | Every connection pinned or verified | R2 pins all three PostgreSQL connections + `DATABASE_URL` (`DefaultSuitePinningTest`); R3 verifies every loaded connection before providers; isolated suite already pinned | **Met.** Deviation D-3 below (`DB_URL`) |
| 2 | Fail-closed before any query; `APP_ENV=testing` and every database approved | R3 hook after `LoadConfiguration`, before `RegisterProviders` (`PreProviderHookTest`: 7 unsafe cases refused with `db` unbound) | **Met** |
| 3 | Enforced coverage of tests booting Laravel without a guarded base | R1 marker on 12 tests; R4 `LaravelBootIsGuardedTest` (tokenizer detector, named and anonymous boot sites); 4 bypassing tests migrated | **Met.** Limits recorded in guide 04 |
| 4 | Negative evidence: a `--no-configuration` `Tests\TestCase` run refused before any query | G-RUN-NEG run 2 (`812538b62`): canary refused by R3 for `publicdigit`, from `bootstrapped: LoadConfiguration`; no `SQLSTATE`; body not reached | **Met.** Deviations D-1, D-2 |
| 5 | Positive evidence: the default suite on the approved test database | G-RUN-POS stages 1–3 (canary; Unit 4,352; Feature 3,482 with `memory_limit=1G`; Architecture 246): **0 PBDIGIT-283 refusals, 0 reset refusals, 0 misdirected connections**; PBDIGIT-283's architecture tests pass on the real database | **Met as "no refusal or misdirection".** Deviation D-4 |
| 6 | No reach into audit or geo | R5 `NoReachIntoAuditOrGeoTest` + review note (guide 05): `migrate:fresh` wipes the default connection only; no migration names a connection; `ElectionSecurityEvent` → `pgsql_audit` (pinned, confined) | **Met** (source-level) |

**Unknowns closed:**
- **U-1:** pinned by R2, confined by R3.
- **U-2:** R1 + R4.
- **U-3:** R6 positive.
- **U-4:** R5.

## 4. Recorded deviations (all approved or decided by the Principal)
- **D-1 Option A:** the negative harness accepts exactly one hard-coded connection (`testing` → `127.0.0.1:5432/nrna_test`, user `nrna`), with the password never read. It stands until `PBDIGIT-292` removes the connection.
- **D-2 Negative run 1 aborted before the canary:** Laravel 11 merges a framework `mariadb` connection (the application doesn't use MariaDB). The fail-safe worked; the harness was corrected test first, and run 2 is the evidence.
- **D-3 `DB_URL`** (Laravel 11's URL variable, read by the merged `mariadb` entry) isn't neutralised by R1 or pinned by R2. R3 refuses any URL, so it fails closed. → `PBDIGIT-293`.
- **D-4 The default suites aren't green.** Stages 2–3 show about 1,900 failures and errors unrelated to PBDIGIT-283 (schema and test-data drift, accumulated data without transaction isolation on PostgreSQL, code and HTTP drift, PHPUnit 11 error-handler notices; triage in the evidence README). Criterion 5 is met in the sense "PBDIGIT-283 neither refuses nor misdirects"; the suites' own health is separate work.
- **D-5 The first Feature run** aborted at 84% on PHP's default 128M `memory_limit`; the authorised re-run with `1G` completed (peak 144.5 MB). Both are kept.
- **D-6 R4 scope extended** (option A): four tests that required `bootstrap/app.php` themselves were migrated; R4's own detector was hardened during review.

## 5. Evidence limits (not claimed)
- **"No connection attempted"** in the negative run rests on the absence of driver errors plus unreachable endpoints; nothing instrumented the run.
- **The architecture checks read source.** Paths built only at runtime (variable-only bootstrap paths, runtime-added connections) aren't detected (guides 04, 05).
- **No connection's `current_database()` was printed** in the positive runs:
  - **default connection (`pgsql`):** checked silently by `TestDatabaseSafety::assertResettable` before every reset (it must be in `nrna_test` / `pbdigit274_isolated_test`; 0 refusals);
  - **`pgsql_audit` and `pgsql_geo`:** the target follows from the R2 pin (`nrna_test`) and the R3 confinement to the approved set; additionally, the 3 audit foreign-key violations in stage 2 show `pgsql_audit` reached the freshly migrated schema;
  - **so the identity `nrna_test` is inferred,** not observed (forensic review 2026-10-14).
- **No baseline full-suite run predates PBDIGIT-283,** so the pre-existing state of the unrelated failures isn't established.

## 6. Follow-ups (separate backlog; not part of this closure)
| Item | What |
|---|---|
| `PBDIGIT-290` | The committee-creation handler binding queries the database when resolved (found by R1) |
| `PBDIGIT-291` | The outbox adapter test passes on any database outcome (found by R4) |
| `PBDIGIT-292` | **Security:** the hard-coded `testing` connection with a tracked plaintext password (credential review, rotation if warranted, removal; removes D-1) |
| `PBDIGIT-293` | `DB_URL` not covered by R1/R2 (D-3) |
| Original follow-ups | Inert `RefreshDatabase` in `IpEvidenceDeterminismTest`, `IpEvidenceTopologyEquivalenceTest`; "never widen `RESETTABLE` to a pattern". Still open |
| `PBDIGIT-294`..`298` (created 2026-10-14 after the closure) | The unrelated suite failures grouped by cause: schema/test-data drift (294), no per-test isolation on PostgreSQL (295), tests out of step with code (296), error-handler risky notices (297), PHP `memory_limit` (298). D-4, D-5 |

## 7. Effect on other work
- **`PBDIGIT-286` G-ISO:** "stays closed until this extension is done" (backlog line 62; PBDIGIT-286 plan §13). **With this closure approved, G-ISO may be considered.** It isn't opened by this record; it needs its own authorisation, and PBDIGIT-286 still waits on O-3 → G-ADR-A.
- **The standing rule against destructive commands on `nrna_test`** is unchanged. The R6 positive permissions were per-stage and are spent.

## 8. Done on approval (documentation only)
- **The backlog item `PBDIGIT-283`:** status `DONE`, the open boxes ticked with evidence references, and the README row updated.
- **The plan:** status closed, plus a closing progress entry.
- **CONTEXT and session log** updated.
- **A commit only when asked.**

**Traceability:** commits `3fd58af8c` (extension) · `b71137e62`, `ba07ee8cd`, `bda1192cd` (plan) · `164f74066`, `6d394e6e1`, `17167dec3`, `b7138fafa`, `ac66fd940` (R1–R5) · `eb18ccfa9`, `4ce631f39`, `812538b62` (R6 negative) · `b6b7d67fa`, `fd573dd40`, `764d9ea2f` (R6 positive).

## 9. Forensic review of the positive evidence (2026-10-14)
A read-only review of the committed logs (no runs) found:
- **No safety class appears in any error trace;** 0 PBDIGIT-283 refusals and 0 reset refusals (expected: they refuse only unapproved targets).
- **0 connection, authentication, unknown-host or missing-database errors.**
- **SQL errors name only `pgsql` and `pgsql_audit`;** no database name other than `nrna_test`. The 6 `publicdigit` hits are `https://publicdigit.com` URLs in a page's JSON-LD.
- **The 1,026 errors / 301 failures** of the Feature re-run are application and test failures against the current schema and code (PBDIGIT-294..298).
- **Verdict: PASS for database-test safety,** with the database-identity limit above. Suite health is a separate FAIL, outside the acceptance criteria. Limit wording corrected (option A, the Principal).
