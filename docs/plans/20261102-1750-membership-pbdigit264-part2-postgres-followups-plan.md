# Plan: PBDIGIT-264 Part 2: Slice 1b PostgreSQL follow-ups F-5 → F-4b → F-2 (roadmap W2)

**Status:** **APPROVED by the PO, 2026-11-02 (G-PLAN and P2-0: "approve the plan and P2-0").** Steps 0–5 are authorised, including container runs with the pinned image. The precondition is met (PBDIGIT-324 accepted, `1d8f670fc`). *(Was: PROPOSED.)*
**Story:** `docs/publicdigit/backlog/PBDIGIT-264-membership-slice1b-acceptance-follow-ups.md` (`IN PROGRESS`: F-1 + Option E and F-3 done on the test double; F-2, F-4, F-5 held "with PostgreSQL Part 2").
**Roadmap:** W2, "… the Membership PostgreSQL suite; then PBDIGIT-264 F-5 → F-4b → F-2" (`…20261016_2219_…_completion_plan.md:176`). The exit is the Slice 1b re-entry criteria 1–4, then ACC-260.
**Baseline:** HEAD `ae9e46768`. Facts are read at this baseline unless marked otherwise.
**Runs on:** the throwaway PostgreSQL cluster of PBDIGIT-324 (`bash scripts/testing/membership-pg.sh`, image `postgres:15@sha256:7da5d534…a822`).

## 1. Objective

Close the remaining Slice 1b follow-ups on a real, isolated PostgreSQL:
- **F-5:** classify the key-collision violation correctly, and enforce the isolation level the adapter assumes;
- **F-4b:** make the commands → facts reference correct by construction;
- **F-2:** cover the two missing real-process races.

Then the PO can reconsider Slice 1b (ACC-260) against its re-entry criteria.

## 2. Decisions this plan relies on (already taken)

| Item | Decision | Source |
|---|---|---|
| F-4 | **F-4b (normalise):** drop `fact_id` and `recorded_at` from `org_membership_commands`; FK `(organisation_id, membership_id, seq)` → facts PK; `appliedCommand` reads them by join. Migration preflight and rollback rules in §10.2 | Principal Reviewer disposition, `2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §10 |
| F-5 | **N1:** refuse nested adapter calls (`transactionLevel() > 0`) before any statement; `SET TRANSACTION ISOLATION LEVEL READ COMMITTED` as the first statement of a top-level append; classify by SQLSTATE `23505` plus the constraint name from the **first line** of `errorInfo[2]` | same §10, §2 "F-5", §9.3 |
| F-3 | a MemberId collision is an infrastructure failure; the key is looked up first | same §10; implemented `d2a3f2b17` |
| P-DB | **W2-1 = C**, the throwaway container (PBDIGIT-324); PostgreSQL 15, W2-2 provisional | PBDIGIT-324 story and plan |
| ARB-SEC-01 / BR-20 | persistence tests run only on an isolated, identity-verified database | Human ARB 2026-10-09; met by PBDIGIT-324 |

**New PO decision needed by this plan:**

**P2-0, reconciliation with the 2026-10-10 plan.** `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md` Part 2 was written for a database on the **shared** server. Its gate requires:
- provisioning evidence (PV), checked by ChatGPT and accepted by the PO;
- a role R without CONNECT on `nrna_test` (I-1);
- a `Connection::resolverFor` identity hook.

Under W2-1 = C, PBDIGIT-324 meets the same goals by construction:
- there is no route to the shared server;
- the guard checks the cluster identity (`system_identifier`) and `current_database()`;
- the run's role exists only inside the container.

**Proposed wording:**
> "The PV, role-R / I-1 and resolver-hook requirements of the 2026-10-10 Part 2 gate are superseded for runs on the PBDIGIT-324 throwaway cluster. Their evidence is the PBDIGIT-324 fail-closed proof and run records. They remain applicable to any future run on a shared server."

## 3. Facts (verified at the baseline)

| # | Fact | Source |
|---|---|---|
| F1 | `append()` catches `UniqueConstraintViolationException` and tests `str_contains($e->getMessage(), 'org_membership_commands_pkey')` | `PostgresMemberRepository.php:136-145`, constant `:45` |
| F2 | `append()` opens `$this->db->transaction(...)` with no isolation statement and no nesting check | `:137` |
| F3 | `org_membership_commands`: PK `(organisation_id, idempotency_key)`; columns `fingerprint`, `membership_id`, `fact_id`, `seq`, `recorded_at`; FK `(organisation_id, membership_id)` → `org_memberships`. **No reference to the fact** | `2026_10_09_100000_create_org_membership_tables.php:74-85` |
| F4 | `appliedCommand()` selects `fact_id`, `seq` and `recorded_at` from the commands table | `PostgresMemberRepository.php:116-127` |
| F5 | `Tests\TestCase` skips per-test transactions on PostgreSQL (`beginDatabaseTransaction` returns early), so the adapter tests run at transaction level 0. N1 is compatible today; a future PBDIGIT-263 change must let these classes opt out (§10.3) | `tests/TestCase.php:49-60` |
| F6 | `PostgresConcurrencyRaceTest` has 3 methods (`pcntl_fork`) | `tests/Feature/Contexts/Membership/Organisational/PostgresConcurrencyRaceTest.php` |
| F7 | `PostgresMemberRepositoryTest` inherits `MemberRepositoryContract`, which includes the Option E / F-1 cases (E1a–d, boundaries) and the F-3 cases (`…member_id_that_belongs_to_another_holder…`, key-first). The PBDIGIT-324 step-6 run (43 tests OK) therefore **probably** ran them on PostgreSQL | **INFERRED**; confirmed in step 0 |
| F8 | the 1b tables migration was **pending** on the development database on 2026-10-09 (§10.2). Its production status is unknown (**OQ-01**) | ratification pack §10.2; roadmap R-2 |

## 4. Design

### 4.1 F-5: classifier, isolation, nesting (adapter-internal; no schema change)

- **New** `Infrastructure/Organisational/PgUniqueViolation::isConstraint(UniqueConstraintViolationException $e, string $name): bool`.
  - It returns true only if the SQLSTATE `errorInfo[0]` is `'23505'` **and** the first line of `errorInfo[2]` names `"$name"`.
  - It never reads DETAIL or the bound SQL.
- **`append()`:**
  - if `$this->db->transactionLevel() > 0`, throw an infrastructure exception **before any statement**;
  - otherwise the first statement inside the transaction is `SET TRANSACTION ISOLATION LEVEL READ COMMITTED`;
  - the catch uses the classifier.
- **Developer guide 03** states the enforced level and the N1 refusal.

### 4.2 F-4b: normalisation (schema change)

**A new migration** (e.g. `2026_11_xx_normalise_org_membership_commands.php`), following §10.2:
- **`up()`:**
  1. add FK `(organisation_id, membership_id, seq)` → `org_membership_facts` PK **first**; it fails if any row is orphaned;
  2. verify that the stored `fact_id` and `recorded_at` equal the joined fact; **abort on any mismatch**;
  3. drop `fact_id` and `recorded_at`.
- **`down()`:** re-add both columns, backfill them by join, set NOT NULL, drop the FK.
- **`appliedCommand()`:** read `fact_id` and `recorded_at` by joining facts on `(organisation_id, membership_id, seq)`.
- **The write path:** stops writing the two columns.

### 4.3 F-2: race coverage (tests only)

Two new methods in `PostgresConcurrencyRaceTest` (group `membership-race`), reusing `race()`, 8 rounds each (scenarios from the ratification pack §2 "F-2"):
- **(a)** 4 processes submit the **same** `AdmitMember` for a new person. Expected: 1 Member, 1 fact, and 4 identical `Appended`.
- **(b)** 4 persons, the **same** key, different fingerprints. Expected: 1 fact, 3 × `IdempotencyKeyConflict`, the losers stay at version 0, no error.

## 5. Steps (TDD; each stops on surprise)

| # | Step | RED | GREEN | Where |
|---|---|---|---|---|
| 0 | **Coverage record** (no code): run the PostgreSQL suite with `--testdox` and record which contract cases (F-1 / Option E, F-3) ran green on PostgreSQL | — | — | container |
| 1 | **F-5 classifier** | `PgUniqueViolationTest` (database-free): a decoy (facts unique violation whose DETAIL contains `org_membership_commands_pkey`) → false; a real commands-PK violation → true; non-23505 → false; a missing first line → false. Fails, because the class is absent | the classifier; `append()` uses it | local, database-free |
| 2 | **F-5 isolation + N1** | PostgreSQL tests: (a) a nested call (`DB::beginTransaction()` then `append`) is refused and writes nothing; (b) with the session default at REPEATABLE READ, the identical-retry and cross-Member races return contract outcomes. **Expected RED:** (a) not refused; (b) per the pack, the RR session leaks into the append | the nesting check; `SET TRANSACTION …` first | container |
| 3 | **F-4b** | PostgreSQL tests: a command row with a non-existent `seq` is rejected (23503); the commands table has no `fact_id` / `recorded_at` columns; `appliedCommand` answers identically before and after; **`up → down → up` on a populated schema preserves every row and every `appliedCommand` answer** | the migration; `appliedCommand` join; the write path | container |
| 4 | **F-2 races** | coverage, expected **to pass** (pack: "no RED"). **Required sensitivity evidence, in a scratch copy:** (a) remove the key consultation after a domain refusal in `DecideAndAppend`, so the losers get `duplicate_fact_id`; (b) remove the catch in `append`, so the losers get errors; both detected, then reverted | the two methods | container (and a scratch copy) |
| 5 | **Regression + evidence** | — | the full PostgreSQL suite on **3 consecutive** container runs; the database-free Membership suites; evidence folder `docs/publicdigit/membership/evidence/<date>-pbdigit264-part2/` | container + local |

**After each step:**
- the database-free regression (Membership `Organisational`, the 7 architecture files, `tests/Unit/Testing`);
- a developer-guide update: guide 03 for F-5 and F-4b; a new guide `12_pbdigit264_part2.md`.

All container runs use the pinned image through `bash scripts/testing/membership-pg.sh`, from a clean worktree, because the main tree has a cached configuration (PBDIGIT-324 guide 11, Pitfalls).

## 6. Acceptance criteria

| AC | Criterion |
|---|---|
| AC-1 | the classifier rejects the decoy and accepts the real violation; `append()` no longer reads the exception message |
| AC-2 | a nested append is refused before any statement and writes nothing (N1) |
| AC-3 | with a REPEATABLE READ session default, the races still return contract outcomes; guide 03 states the enforced level |
| AC-4 | F-4b: no `fact_id` / `recorded_at` in `org_membership_commands`; FK `(org, membership, seq)` → facts; an orphan `seq` is rejected; `appliedCommand` answers unchanged |
| AC-5 | the migration's `up → down → up` on a populated schema preserves every row and answer; `up()` aborts on a mismatch (tested with a planted mismatch) |
| AC-6 | F-2 (a) and (b) green on 3 consecutive runs; both sensitivity mutants detected |
| AC-7 | the full PostgreSQL Membership suite and the database-free suites green; IDEM-1…4 and T1–T13 unchanged |
| AC-8 | evidence archived (commit, image digest, `server_version`, `system_identifier`, outputs, exits, SHA256SUMS) |
| AC-9 | no change outside the files named in §4 plus tests and docs. **The only schema change is the F-4b migration** |

## 7. Risks

| Risk | Mitigation |
|---|---|
| **The F-4b migration runs wherever `migrate` runs next** (development, CI, production) once merged | `up()` aborts on an orphan or a mismatch (§10.2). **Before merge or deploy:** OQ-01 answered, plus the §10.2 operator preflight (counts 0 / 0 / 0) wherever the 1b migration was applied. The branch is not pushed until then. The PO decides the release separately |
| N1 breaks a future per-test-transaction change (PBDIGIT-263) | recorded dependency (§10.3); the adapter test classes opt out |
| The REPEATABLE-READ test depends on the session default being changeable | set per connection within the test (`SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL REPEATABLE READ`); container only |
| Race tests are flaky | 8 rounds × 3 consecutive runs; on flakiness, stop and report, never retry until green |
| Version parity (W2-2 provisional) | the run records `server_version`; re-run on production's major version when confirmed |

## 8. Gates and owners

| Gate | Content | Owner |
|---|---|---|
| G-PLAN | approve this plan (authorises steps 0–5, **including container runs** with the pinned image for this story) | PO |
| P2-0 | supersede the 2026-10-10 Part 2 gate for throwaway-cluster runs (§2) | PO |
| Precondition | **PBDIGIT-324 accepted (G-ACC):** the container must be accepted before evidence from it counts. Step 1 (database-free) may start before | PO |
| Release | OQ-01 + the §10.2 preflight before the F-4b migration reaches any persistent database | PO + Ops |
| ACC-260 | Slice 1b reconsideration on re-entry criteria 1–4 (dispositions record §4) | PO, after an independent review |

## 9. Re-entry criteria for Slice 1b, as this plan would leave them

| # | Criterion | After this plan |
|---|---|---|
| 1 | PostgreSQL suite green on an explicitly isolated database, with command, name, output and exit captured | met by PBDIGIT-324 step 6, and again in step 5 |
| 2 | far-date defect fixed test-first, nothing out of range persisted | done earlier (`ff18c2a4e` RED, `44348f99b` GREEN); its PostgreSQL run is confirmed in step 0 |
| 3 | the applicable 264 items dispositioned | F-1, F-3 done; **F-5, F-4b, F-2 by this plan** |
| 4 | persistence and security acceptance conditions (ARB-MEM-04 §5) | persistence: by this plan. **Security (PBDIGIT-266) stays separate**; its gate (ARB-SEC-01) can now be met on this cluster |

## 10. Traceability

- PBDIGIT-264; ratification pack `2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §2, §9.2, §9.3, §10.
- The 2026-10-10 plan (Part 2) → superseded for throwaway-cluster runs by P2-0.
- PBDIGIT-324 (P-DB); dispositions record §4 (Slice 1b re-entry criteria); roadmap W2, R-2, R-15.

## 11. Progress

- 2026-11-02: plan prepared; awaiting G-PLAN and P2-0. PBDIGIT-324 G-ACC review in progress.
- 2026-11-02: PBDIGIT-324 accepted (`1d8f670fc`). **G-PLAN and P2-0 approved by the PO.** The release gate (OQ-01 plus the §10.2 preflight before the F-4b migration reaches any persistent database) stays open.
- 2026-11-02: **Step 0 done.** A `--testdox` run on the cluster (clean worktree at `f9455ee96`, pinned image): 43 OK, and the names confirm that the F-1 / Option E cases (E1a–c, E2a–c, the End boundary) and the F-3 cases (the other-holder collision, key-first) ran on PostgreSQL. **F7 is confirmed.** The output is kept for the step-5 evidence.
- 2026-11-02: **Step 1 done (TDD).**
  - RED: `PgUniqueViolationTest`, 8 errors (class absent) and 1 failure (the repository still read `getMessage()`). The decoy's precondition shows the old substring test would have matched.
  - GREEN: `Infrastructure/Organisational/PgUniqueViolation::isConstraint()` (SQLSTATE 23505 plus the first-line constraint name); `append()` uses it. 9 tests.
  - Regression: Domain 117, Application 61, Infrastructure 22, Architecture 97. **AC-1 met.**
- 2026-11-02: **Step 2 done (TDD, on the cluster; clean worktree at `820ae5a52` plus the change).**
  - **RED:** 4 failures, as predicted.
    - The two REPEATABLE READ race variants hit `SQLSTATE[40001] could not serialize access` (errors, not contract outcomes).
    - The nested append was not refused.
    - The first statement was not the SET.
    - 43 existing tests stayed green.
  - **GREEN:**
    - `append()` refuses `transactionLevel() > 0` with the new `AppendInsideTransactionRefused`, before any statement (N1);
    - `SET TRANSACTION ISOLATION LEVEL READ COMMITTED` is the first statement in the transaction;
    - the cluster run: **47 tests, 325 assertions, exit 0**.
  - **Regression (database-free):** Domain 117, Application 61, Infrastructure 22, Architecture 97, MembershipPgIsolation 28.
  - **Guide 03** states the enforced level, N1 and the classifier. **AC-2 and AC-3 met.**
- 2026-11-02: **Step 3 done (TDD, on the cluster; fresh clean worktree at `1e16d89c7` plus the change).**
  - **Deviation:** the first RED run was invalid. The reused scratch worktree could not move to the new HEAD because of an untracked file copied in step 2, so it ran the old state (43 tests). `git clean` was refused by permissions, so a fresh worktree was created instead. The invalid run is not used as evidence.
  - **RED (valid):** 52 tests.
    - 2 errors: the migration was absent.
    - 2 failures: the columns were present, and the orphan insert failed with a NOT NULL error instead of 23503.
    - The characterisation test passed, as expected.
  - **GREEN:**
    - migration `2026_11_02_180000_normalise_org_membership_commands.php`, per §10.2: FK first, abort on mismatch, drop columns; `down()` re-adds, backfills and restores NOT NULL, then drops the FK;
    - `appliedCommand()` joins the fact; the write path no longer copies the values;
    - R5 (`MembershipLedgerIsolationTest`) allowlist + the new migration (a deliberate change: it is the ledger's own migration);
    - the DB-1 PostgreSQL-only rule now covers both migrations;
    - the cluster run: **52 tests, 343 assertions, exit 0**.
  - **Regression (database-free):** Domain 117, Application 61, Infrastructure 22, Architecture 97.
  - **Guide 03** updated. **AC-4 and AC-5 met.**
  - **The release gate stays open:** OQ-01 plus the §10.2 preflight before the migration reaches any persistent database.
- 2026-11-02: **Step 4 stopped on a surprise (EP-01).**
  - **Defect found.** F-2(a), which was expected to pass, failed: 4 identical concurrent Admits for a new person, and at least one gets `UniqueConstraintViolationException … "org_memberships_pkey"` from `create()`.
  - **Root cause:** `INSERT … ON CONFLICT (organisation_id, person_ref) DO NOTHING` does not cover the primary key, so a concurrent identical row can conflict on the PK.
  - **Effect:** a quick retry can get an infrastructure error instead of the idempotent answer (IDEM-1). No duplicate data is created.
  - F-2(b) passed.
- **Revision R1 (PO, 2026-11-02, "approve step 4a"): new step 4a.** `create()` uses `ON CONFLICT DO NOTHING` without a target.
  - If nothing was inserted and a row exists for the person → `holderExists`, unchanged.
  - If no row exists for the person → the conflict was a `MemberId` held by another holder → an infrastructure exception (the **F-3 decision is kept**).
  - RED = F-2(a). AC-6 now reads "F-2(a) green after 4a".
  - No schema change.
- 2026-11-02: **Step 4a done.** `create()` uses `ON CONFLICT DO NOTHING` (no target), with the explicit F-3 branch `MemberIdHeldByAnotherHolder`. The cluster: **54 tests, 423 assertions, exit 0** (F-2(a) 8 rounds, F-2(b), the F-3 contract test). Database-free regression green. Guide 03 updated.
- 2026-11-02: **Step 4 sensitivity** (scratch worktree only; restored and checked with `cmp`):
  - **mutant (a)**, no key re-read after a domain refusal: **caught** by the existing identical-retries race (round 4), but **not** by F-2(a) alone (2 × 8 rounds green). Recorded as a limitation of F-2(a). *(Corrected 2026-11-02, review R-5: the PostgreSQL detection is probabilistic, 1 of 3 in the reviewer's runs; the deterministic catch is the database-free `ConcurrencyAndRaceTest`.)*;
  - **mutant (b)**, the commands-PK collision not answered: **caught** by F-2(b) (round 0).
- 2026-11-02: **Step 5 done.** Three consecutive full runs on fresh clusters: **54 / 423, exit 0 × 3**. Evidence archived in `docs/publicdigit/membership/evidence/2026-11-02-pbdigit264-part2/` (14 runs, hashes verified, no credentials, the invalid run excluded). Guide 03 F-1 note corrected (fixed 2026-10-10).
- **AC status:**
  - AC-1…AC-5, AC-7, AC-8, AC-9 met;
  - **AC-6 met with one qualification:** both mutants are detected by the race group; mutant (a) is detected by the identical-retries race, not by F-2(a).
  - **Not accepted:** an independent review, then the PO's verdict on PBDIGIT-264, then ACC-260.
  - **Release gate still open.**
- 2026-11-02: **Independent review:** ACCEPT WITH CONDITIONS (C1 test, C2 contracts, C3 release gate; findings R-1…R-10). **PO: "A, fix C1 and C2 first".**
  - **C1 (done):**
    - the shared-contract test `test_every_key_is_answered_with_its_own_fact_not_only_the_first` (Admit then End; each key answers with its own fact; in-memory 62 OK);
    - a new `recorded_at`-only case in the F-4b abort test (R-4); its `finally` now restores both copied values from the fact;
    - the tests pass at the committed code (cluster 56 tests, 431 assertions, exit 0);
    - **RED proof by the reviewer's surviving mutants:** M6 (join without `seq`) is now caught by the C1 test; M1 (no `recorded_at` clause) is now caught by the R-4 case. Scratch worktree only, restored.
  - **C2 (done):** amendments appended to the Slice 1b contract (§5 / §6 pointers plus "Amendment (2026-11-02)") and the Slice 1a contract (the F-3 PostgreSQL mechanism).
  - **R-5:** wording corrected in this plan, the evidence README and guide 03.
  - **Gap found while doing C2 (not part of the review):** the **PostgreSQL E4-L** test (approved 2026-10-10 as R3 / P2-4, "Part 2 scope, its own commit") was **omitted from this plan** and is still unwritten. It is raised to the PO; not done silently.
- **Revision R2 (PO, 2026-11-02: "commit, then add E4-L as step 4b"): new step 4b.**
  - **What:** the PostgreSQL E4-L test from the 2026-10-10 task definition (`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md` §"PostgreSQL E4-L (P2-4)").
  - **Adapted to F-4b:** the key row now carries `seq` only; `fact_id` and `recorded_at` come from the fact.
  - **The test:** a legacy Admit row dated `10000-01-01` plus its key row, version 1. The identical replay (same decision, key and fingerprint) → `AlreadyApplied` with the original fact id, seq and recordedAt; `appliedCommand` unchanged; one fact; version 1. **No `load()`.**
  - **Sensitivity:** the R-1a-6 date check moved before the key lookup (scratch only) → the test must fail.
  - **Its own commit.**
- 2026-11-02: **Step 4b done.**
  - `PostgresMemberRepositoryInvariantsTest::test_e4l_…` passes at the committed code: the cluster runs **57 tests, 436 assertions, exit 0** (fresh worktree at `b3b05c616`).
  - **Sensitivity:** the date check moved before the key lookup (scratch only, restored) → the test fails (`ValidTimeNotStorable`).
  - Evidence `step4b-*`.
  - **The E4-L gap is closed.**
- 2026-11-03: **PBDIGIT-264 ACCEPTED WITH EXPLICIT RESIDUAL RISKS** by the PO ("accept PBDIGIT-264").
  - Review and verdict recorded in `docs/publicdigit/reviews/2026-11-02-pbdigit264-part2-independent-review-and-acceptance.md`.
  - R-3 and R-7 → `PBDIGIT-326`.
  - **Release gate (C3) stays open:** OQ-01; the §10.2 preflight (0 / 0 / 0); `SHOW lc_messages` = an English locale; code and migration deployed together.
  - **Plan complete.** Next: ACC-260 (Slice 1b); re-entry criterion 4 (PBDIGIT-266 security; C-1 F-1 assigned to COR-4) is still open.
