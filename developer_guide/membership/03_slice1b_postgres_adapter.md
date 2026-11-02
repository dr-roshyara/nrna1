# 03: Slice 1b, PostgreSQL adapter for the Member aggregate

**Story:** `PBDIGIT-260` · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md` · **Namespace:** `App\Contexts\Membership\Infrastructure\Organisational`

## Purpose

`PostgresMemberRepository` implements the Slice 1a `MemberRepositoryPort` on PostgreSQL. **This is the only database the Membership kernel supports (DB-1).** The domain kernel, the port and the handlers are unchanged. The adapter only stores and retrieves.

## Where it fits

```text
AdmitMemberHandler / EndMembershipHandler → DecideAndAppend      (Application, Slice 1a)
        ↓ MemberRepositoryPort
PostgresMemberRepository                                          (Infrastructure, this slice)
        ↓ org_memberships · org_membership_facts · org_membership_commands
```

## Key files

| File | Role |
|---|---|
| `database/migrations/2026_10_09_100000_create_org_membership_tables.php` | the three tables. **A deployable migration, not yet run on dev or prod** |
| `PostgresMemberRepository.php` | load / findByHolder / create / appliedCommand / append |
| `DateOrdinal.php` | `TimePoint` ⇄ `date` (days since 1970-01-01; EP-Q6 = T1) |
| `InstantMicros.php` | `Instant` ⇄ `timestamptz(6)` (epoch µs, UTC; exact, no float) |
| `ProvenanceJson.php` | `Provenance` ⇄ `jsonb` |
| `UnsupportedMembershipDatabaseDriver.php` | the fail-fast error for non-PostgreSQL connections |

## Design decisions

- **Calendar dates** for `effectiveAt` and the initial term (T1). ∞ is never a date: `initial_term` ∈ {none, until, unlimited}.
- **Tenant-scoped keys:** PK `(organisation_id, id)`, and composite FKs so a fact can never attach to another tenant's Member.
- **The append transaction:** key check → version compare-and-set (row lock) → re-read the key if the compare-and-set failed → fact + key insert. The adapter never retries.
- **Enforced isolation (`PBDIGIT-264` F-5, 2026-11-02):**
  - the first statement of every append transaction is `SET TRANSACTION ISOLATION LEVEL READ COMMITTED`. The re-read after a waited compare-and-set (IDEM-3) depends on it, so it is enforced rather than inherited from the session;
  - **N1:** an append inside an already open transaction (`transactionLevel() > 0`) is refused with `AppendInsideTransactionRefused` before any statement. `Appended` therefore always means durably committed, and the SET can always be the first statement.
- **Normalised command rows (`PBDIGIT-264` F-4b, 2026-11-02):**
  - `org_membership_commands` no longer copies `fact_id` / `recorded_at`. A row names its fact by `(organisation_id, membership_id, seq)`, enforced by the composite FK `org_membership_commands_fact_fk` → facts PK;
  - `appliedCommand()` reads `fact_id` and `recorded_at` from the fact by join, so the idempotency answer can never disagree with the stored fact;
  - migration `database/migrations/2026_11_02_180000_normalise_org_membership_commands.php`: `up()` adds the FK first, aborts on any copied-value mismatch, then drops the columns; `down()` re-adds them, backfills by join, sets NOT NULL, drops the FK;
  - **Release gate:** before this migration reaches any persistent database where the 1b tables exist, run the operator preflight (ratification pack §10.2) and answer OQ-01.
- **Concurrent identical creation (`PBDIGIT-264` step 4a, 2026-11-02):**
  - `create()` inserts with `ON CONFLICT DO NOTHING` and **no conflict target**. A concurrent identical creation (same id, same person) may conflict on the primary key instead of the person index; before, that escaped as a unique violation (found by race test F-2(a)).
  - If nothing was inserted and the person has a Member → `holderExists` (unchanged).
  - If the person has none, the id belongs to **another** holder → `MemberIdHeldByAnotherHolder` (F-3: an infrastructure failure, no state change).
- **Key-collision classification (`PBDIGIT-264` F-5):** a concurrent registration of the same key is recognised by `PgUniqueViolation::isConstraint()`: SQLSTATE 23505 plus the constraint named on the first line of the driver's error. Never by searching the exception message, which carries bindings and key values.
- **Only port outcomes are translated.** A duplicate fact id at the database is an infrastructure error, by design (B-1).
- **No Eloquent models.** Facts are inserted through the query builder only; there is no update or delete path.

## How to use

```php
$repo = new PostgresMemberRepository(DB::connection(), fn (): int => (int) (microtime(true) * 1_000_000));
$handler = new AdmitMemberHandler($repo, $authority);   // as in Slice 1a
```

Production wiring (service provider, authority adapter, HTTP) is **Slice 2**.

## Testing

| Test | Proves |
|---|---|
| `tests/Feature/Contexts/Membership/Organisational/PostgresMemberRepositoryTest` | the shared repository contract (the `MemberRepositoryContract` trait) on PostgreSQL |
| `…/PostgresMemberRepositoryInvariantsTest` | T2–T10: tenants, FK, term states, round trips, monotone recordedAt, rollback, B-1, no mutation, fail-fast |
| `…/PostgresReferenceModelPropertyTest` | handlers + PostgreSQL against the independent model (`ReferencePropertyRun`) |
| `…/PostgresConcurrencyRaceTest` (group `membership-race`) | real concurrent processes (`pcntl_fork`): creation race, competing Ends, identical retries |
| `tests/Architecture/MembershipSlice1bPersistenceBoundaryTest` | inward dependencies; no Eloquent; a PostgreSQL-only migration |

```bash
# Isolated run (PBDIGIT-324, guide 11): a throwaway PostgreSQL cluster, from a tree without a config cache
MEMBERSHIP_PG_IMAGE='docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822' \
  bash scripts/testing/membership-pg.sh
```

F-5 tests (2026-11-02):
- `…InvariantsTest::test_f5_*`: N1 refusal before any statement, with nothing written; READ COMMITTED as the first statement;
- `…RaceTest::test_f5_*`: different Ends and identical retries under a REPEATABLE READ session default, 8 rounds;
- `tests/Unit/…/Infrastructure/Organisational/PgUniqueViolationTest`: the classifier, including the decoy.

F-4b tests (2026-11-02), all in `…InvariantsTest::test_f4b_*`:
- no copied columns;
- an orphan command is rejected (23503);
- `appliedCommand` answers come from the fact;
- the migration's `up → down → up` on a populated schema preserves every row and answer;
- `up()` aborts on a planted mismatch and changes nothing.

These tests call the migration directly inside `DB::transaction` and **always restore the migrated schema**, because RefreshDatabase migrates only once per process on PostgreSQL.

F-2 races (2026-11-02), `…RaceTest::test_f2a_*` / `test_f2b_*`, 8 rounds:
- **(a)** 4 identical Admits for a new person → 1 Member, 1 fact, and identical `Appended` (member, seq, fact, recordedAt) for all;
- **(b)** 4 persons, one key, different fingerprints → 1 fact, 3 × `idempotency_key_conflict`, the losers stay at version 0.

**Sensitivity limits:**
- removing the key re-read after a domain refusal (`DecideAndAppend`) is caught **deterministically** by the database-free `ConcurrencyAndRaceTest`, and only **probabilistically** by the PostgreSQL identical-retries races (the review caught it in 1 of 3 runs); F-2(a) does not catch it. *(Wording corrected 2026-11-02, review finding R-5.)*
- removing the commands-PK catch is caught by F-2(b).

Evidence: `docs/publicdigit/membership/evidence/2026-11-02-pbdigit264-part2/`.

## Pitfalls

- **Valid-time range (`PBDIGIT-264` F-1, fixed 2026-10-10; guide 06):** dates outside 0001-01-01 … 9999-12-31 are refused before any write (`ValidTimeNotStorable`). They used to make a Member unloadable. *(Was listed here as an open defect; corrected 2026-11-02. Its PostgreSQL run was confirmed in Part 2 step 0.)*
- **Isolation level:** the idempotency and conflict answers assume READ COMMITTED. **Since F-5 (2026-11-02) the adapter enforces it per transaction.** With the session default at REPEATABLE READ, the race tests now return contract outcomes; before, a waiting writer failed with SQLSTATE 40001.
- **Do not call `append()` inside your own transaction:** it is refused (N1). If `PBDIGIT-263` ever restores per-test transactions on PostgreSQL, these adapter test classes must opt out.
- **Test isolation:** `Tests\TestCase` does **not** wrap PostgreSQL tests in transactions (`PBDIGIT-263`). Always create fresh organisations per test, as these tests do.
- **Forked tests:** call `DB::disconnect()` before `pcntl_fork`. Children must open their own connection and end with `SIGKILL`, so that no PHPUnit shutdown runs in them.
- **Never run these migrations on the development database or any persistent database** (CLAUDE.md database rules). The F-4b migration also waits on the release gate: OQ-01 plus the §10.2 operator preflight.

**Traceability:** PBDIGIT-260 · PBDIGIT-264 (F-1, F-3, F-5, F-4b, F-2, step 4a; plan `docs/plans/20261102-1750-membership-pbdigit264-part2-postgres-followups-plan.md`) · contract §1–§10 · EP-Q6 · DB-1 · implementation review `2026-10-09-membership-slice1b-implementation-review.md`.
