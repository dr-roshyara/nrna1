# PBDIGIT-274 S2: migration database run on the isolated database, 2026-10-12

**Authority:** Human PO, 2026-10-12. Implementation of the approved S2 design (plan 11 / 11.0) was authorised, then the database run ("continue").

**Scope:** `pbdigit274_isolated_test` only, behind the identity guard (G5). No other database was touched. No password appears in any file here.

**Code under test (uncommitted at run time):**
- `database/migrations/2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index.php`;
- `tests/Isolated/Pbdigit274/Database/VoterSlugsActiveIndexMigrationTest.php`, `…/PgUniqueViolationShapeTest.php`;
- `…/VoterSlugStepTenantIsolationTest.php`, re-run.

| # | File | What | Result |
|---|---|---|---|
| 01 | `01-preflight-identity.out` | no config cache; `APPROVED_NAME`; target; bootstrap identity check (`--list-tests`) | `confirmed`; 20 tests discovered |
| 02 | `02-database-run-1.out` | `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml --display-deprecations` | **20 tests, 62 assertions, exit 0**, first run. 3 deprecations: `PDO::MYSQL_*` in vendor `config/database.php:61, 81` and project `config/database.php:66`; not from the new code |
| 03 | `03-after-run.out` | read-only `psql` afterwards | migration recorded (batch 1, via `migrate:fresh`); new index `CREATE UNIQUE INDEX voter_slugs_active_election_user_unique ON public.voter_slugs USING btree (election_id, user_id) WHERE (deleted_at IS NULL)`; old constraint absent; failing scratch migration **not** recorded; 0 rows (each test is rolled back) |

**S2 acceptance (plan 11.0) covered by this run:**
- tests 1–5: test 1 directly (level +1, `lock_timeout` 5s); test 2 through the Migrator (level +2, not recorded); test 3 drift refusals ×4; test 4 extended; test 5;
- behaviour (a)–(d);
- `PgUniqueViolationShapeTest` (23505; constraint names);
- identity check before the run;
- S1 regression, database-free: 72 / 424.

**Disclosed deviations:**
- The migration was written before its tests, so no RED phase was recorded for the database tests. The tests passed on their first run.
- The precondition "no two current rows" is defensive and unreachable while the old constraint exists, so it has no test of its own.
- Lock contention under real load is not tested; only the `lock_timeout` setting is.

**Not covered:**
- G6 (real concurrency, S3);
- G7;
- G8 / the production runbook (section 11.1).
