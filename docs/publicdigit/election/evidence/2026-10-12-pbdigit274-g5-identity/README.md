# PBDIGIT-274 gate G5: identity verification on the isolated database, 2026-10-12

**Authority:** Human PO, 2026-10-12 ("continue for G5"). This folder covers the **non-destructive** G5 steps 1 to 5. No test was executed and nothing was written. Credentials came from the git-ignored `.pbdigit274.env`; no password appears in any file here.

| # | File | What | Result |
|---|---|---|---|
| 01 | `01-preflight.txt` | variables set (names; only the database name and host shown), no config cache, `APP_CONFIG_CACHE` unset, `APPROVED_NAME` | all as required |
| 02 | `02-safety-recheck.out` | dedicated config with no name, and with `nrna_test` | `[missing]` / `[forbidden]`, exit 1, before any connection |
| 03 | `03-identity-proof.out` | the real bootstrap with `.pbdigit274.env`; `--list-tests`, so the bootstrap runs but **no test executes** | `current_database() = 'pbdigit274_isolated_test' confirmed.`, exit 0. The raw PDO identity check worked against the real server. Four database tests listed, none run |
| 04 | `04-identity-and-version.out` | read-only `psql` in the target | `pbdigit274_isolated_test` / `pbdigit274_test_owner` / `127.0.0.1:5432`; PostgreSQL 18.6; 0 user tables |
| 05 | `05-wrong-name-refused.out` | as 03, but `PBDIGIT274_TEST_DB=pbdigit274_isolated_tes` | `[not_approved]`, exit 1. Refused **before** any connection |

## Part 2: first database test run (destructive on the isolated database only)
**Authority:** Human PO, 2026-10-12 ("Run the 4 tests").

| # | File | What | Result |
|---|---|---|---|
| 06 | `06-database-tests.out` | `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml`: the bootstrap identity check, then the 4 tests of `VoterSlugStepTenantIsolationTest`. Each runs `IsolatedDatabaseTestCase::refreshDatabase()`, i.e. the in-app identity and connection check before `migrate:fresh` | bootstrap `confirmed`; **4 tests, 9 assertions, exit 0**. 3 deprecation notices, not itemised in this run; the known PHP 8.5 vendor notices in earlier runs |
| 07 | `07-after-run.out` | read-only `psql` in the target after the run | `pbdigit274_isolated_test` / `pbdigit274_test_owner`; **0 → 99 tables, 224 migrations** (`migrate:fresh` ran here); 0 step rows (each test runs in a transaction that is rolled back); `voter_slugs` still has its old unique constraint (no S2 migration yet) |

**What part 2 shows:**
- **In-app check passed.** The second identity check inside the running Laravel app passed before `migrate:fresh`. This is shown by the code path, not by printed output: `refreshDatabase()` runs `confirmBeforeRefresh` first and throws on any mismatch, which would have failed every test.
- **The schema was built only in `pbdigit274_isolated_test`** (files 04 → 07).
- **Real-PostgreSQL proof for the tested cases:**
  - a recorded step carries its slug's organisation;
  - organisation A can't read B's steps, and B can't read A's;
  - a step recorded under another tenant keeps the slug's organisation and is invisible to that tenant;
  - the same step recorded under two tenant contexts is stored **once** (the `stepQuery()` fix).

**G5 status: PASS.** The identity was verified by the bootstrap (file 03) and inside the app before every refresh (file 06). Unsafe or wrong names were refused before any connection (02, 05), and the only database touched was the approved one (04, 07).

**Not verifiable on plain PostgreSQL:** a live `current_database()` mismatch. The guard connects with `dbname` = the approved name. The mismatch path is unit-tested with a stand-in.

**Still open:**
- G6 (real concurrency, S3);
- G7 (vote contract, S9);
- G8 (release);
- the S2 migration itself.
