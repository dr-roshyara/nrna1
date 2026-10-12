# PBDIGIT-274 voter-slug lifecycle: next steps (to-do), 2026-10-12

**Story:** `PBDIGIT-274` · **Plan (canonical):** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` · **Status:** IN PROGRESS · **Type:** working to-do list, derived from the plan. **The plan wins on any conflict.** · **Last updated:** 2026-10-12, after the independent review of S2 step 1.

## Where we are

| Item | State | Reference |
|---|---|---|
| Plan approval (G2) | ✅ passed, 2026-10-11 | plan, section 2.1a |
| S1: lifetime rule, guard order, route lookup (database-free) | ✅ in HEAD | `a78d7c2f2`, restored in `4d83dffc0`; evidence `docs/publicdigit/election/evidence/2026-10-11-voter-slug-lifecycle-s1/` (72 tests / 424 assertions) |
| S2 migration design (G3) | ✅ approved, 2026-10-12 (isolated test database only) | plan, sections 11 and 11.0; `8b86967c4` |
| S2 step 1: isolated-database identity guard (database-free) | ✅ in HEAD | `2e9017487`; evidence `docs/publicdigit/election/evidence/2026-10-12-voter-slug-lifecycle-s2-step1/` (42 tests / 96 assertions); guide `developer_guide/election/voter_slug/03_isolated_database_guard.md` |
| Independent review of S2 step 1 | ⚠️ **conditionally safe:** one High finding (H1) must be fixed before the first real-database run | read-only review subagent, 2026-10-12: 42 / 96 reproduced, 19 / 19 evidence checksums OK, `[missing]` and `[forbidden]` refusals reproduced |
| Isolated test database (G4) | ⛔ **not provisioned. This blocks everything below** | no `PBDIGIT274_*` variables set (checked 2026-10-12) |
| G5–G8 | not executed | plan, header |

## A. Decisions by the Human PO / ARB
- [ ] **A1 — G4:** provision an isolated PostgreSQL test database for this work only (**never `nrna_test`**) and name it exactly. Requirements: plan section 11.0 (S2-3) and guide 03.
  - Connection details go in your shell as `PBDIGIT274_DB_HOST`, `_USERNAME`, `_PORT`, `_PASSWORD`, **never in a file**.
  - **Currently blocking.**
- [x] **A1b:** authorise **S2 step 1b** *(authorised 2026-10-12)* (section B0: the review corrections; database-free, test-first). Can be done before A1.
- [ ] **A2:** authorise the **database part of S2** (after A1 and B0).
- [ ] **A3 — O-5:** another voter opening someone else's link sees the ownership error or a plain "not found". Needed before S7.
- [ ] **A4:** authorise S3 … S9, one slice at a time.
- [ ] **A5 — release:** name a release owner; confirm the section 11.1 operational controls (maintenance mode, access freeze, process checks, detection snapshot); then approve G8.

## B0. S2 step 1b: corrections from the independent review (Claude, after A1b; database-free): ✅ implemented 2026-10-12, evidence `docs/publicdigit/election/evidence/2026-10-12-voter-slug-lifecycle-s2-step1b/`
- [x] **H1 (High; blocks the first real-database run):** today only `nrna_test` is refused, so any other shared or development database name (e.g. `publicdigit`, the `pgsql_geo` default) would pass both checks and then be wiped.
  - **Fix:** require `PBDIGIT274_TEST_DB` to equal a committed `APPROVED_NAME` constant.
  - The constant stays **empty until G4**, so everything is refused as `not_approved`.
  - It is set in a small, reviewable commit once the PO names the database.
- [x] **M1:** refuse the names `null`, `true`, `false` and `empty` (any letter case). Laravel's `env()` turns them into special values.
- [x] **M2:** refuse when `APP_CONFIG_CACHE` is set (a cached config elsewhere would bypass the pinned variables).
- [x] **L1:** the in-test check refuses if **any** configured connection points at a forbidden or unapproved database (e.g. the hard-coded `testing` → `nrna_test`).
- [x] **L2:** record the exact S1 regression commands in the step 1 evidence README.
- [x] **L3:** wording:
  - plan line 20: step 1 was authorised;
  - evidence README: the commit came later, at the PO's request.
- [x] **Close 1b:** RED → GREEN with `--no-configuration` only; evidence; guide 03 update. *(Committed 2026-10-12 with the PO's approval.)*
- *Noted, no action in 1b:*
  - **L4:** the environment-writing step and a script-level `config_cached` case are only exercised after G4.
  - **I1:** on plain PostgreSQL the `current_database()` check proves existence and credentials, not isolation. That is why H1 matters.
  - **I4:** the second check covers only `RefreshDatabase`. Database tests must not use `DatabaseMigrations`, `DatabaseTruncation` or a direct `migrate:fresh`.

## B. S2, database part (Claude, after A1 + A2 + B0)
- [ ] **B1 — G5 identity proof** (after `APPROVED_NAME` is set to the G4 name): a run through `phpunit.pbdigit274.xml` that records `current_database()` and `SELECT version()`, plus a deliberate wrong-name run that aborts. This also proves the in-test check inside a booted app.
- [ ] **B2 — RED tests** in `tests/Isolated/Pbdigit274/Database/`:
  - section 11 tests 1–5 (directly and through the Migrator);
  - behaviour tests S2-2 (a)–(d): archived plus current allowed; a second current rejected; several archived allowed; restoring an archived row while a current one exists rejected;
  - `PgUniqueViolationShapeTest`.
- [ ] **B3 — GREEN migration:**
  - precondition read from `pg_constraint`;
  - `DROP CONSTRAINT`;
  - the partial unique index `WHERE deleted_at IS NULL`;
  - a lock timeout;
  - `down()` refuses on history or drift.
- [ ] **B4:** correct the comment in `VotingIntegrityCharacterizationTest` (S2-7).
- [ ] **B5 — close S2:**
  - evidence: database identity per run, checksums, credential check;
  - S1 regression;
  - developer guide;
  - commit after approval.

## C. Later slices (each needs its own authorisation)
- [ ] **C1 — S3, issuer:** issue / reuse / replace links; locks on the `codes` row; the real two-process concurrency test (**G6**).
- [ ] **C2 — S4, entry points:** `/vote`, `elections.start`, recovery paths; fix the `ArgumentCountError`.
- [ ] **C3 — S5:** no in-place lifetime extensions; cleanup never hard-deletes real links.
- [ ] **C4 — S6:** one source for the voting time; remove the `(int)` cast. Characterisation tests for the other readers first.
- [ ] **C5 — S7:**
  - expired link → the election's start page (organisation page as fallback);
  - closed election → "voting is closed";
  - apply O-5;
  - update the stale 410 test.
- [ ] **C6 — S8:** end-to-end lifecycle test.
- [ ] **C7 — S9:**
  - vote success / failure contract (**G7**);
  - count tests;
  - remove raw voting links from logs, sites T1–T11, incl. the error renderer's URL and the binding's slug prefix (decision O-7).

## D. Backlog items to create when PBDIGIT-274 closes
- [ ] **D1:** guard the console commands that write votes; decide the delegated-voting route.
- [ ] **D2:** retention / erasure policy for voter-slug history.
- [ ] **D3:** send the "vote confirmed" email only after the vote is saved (O-15).
- [ ] **D4:** record on `PBDIGIT-276` whether item 1 (renderer URL) is covered by 274.

## E. Release
- [ ] **E1:** **`PBDIGIT-273` is never deployed alone.** Order: the migration first, then the 273 + 274 code together, following plan section 11.1.

## Not part of PBDIGIT-274 (parked)
- **Adjudication:** PO scope decision P-0 → ARB ruling Q1–Q7 → IDs for T-3 / T-5 → test-first plan for `PBDIGIT-211` (`docs/publicdigit/reviews/2026-10-11-adjudication-readiness-review-and-d03-scope.md`).

**Traceability:** plan sections 2.1a, 11, 11.0, 11.1, 13, 17, 18 · story `docs/publicdigit/backlog/PBDIGIT-274-voter-slug-lifecycle-fixed-lifetime-and-replacement.md` · commits `4d83dffc0`, `ac50a614a`, `8b86967c4`, `7b55f86de`, `a93a0412d`, `2e9017487`.
