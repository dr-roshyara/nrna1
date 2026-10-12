# PBDIGIT-274 voter-slug lifecycle: next steps (to-do), 2026-10-12

**Story:** `PBDIGIT-274` · **Plan (canonical):** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` · **Status:** IN PROGRESS · **Type:** working to-do list, derived from the plan. **The plan wins on any conflict.**

## Where we are

| Item | State | Reference |
|---|---|---|
| Plan approval (G2) | ✅ passed, 2026-10-11 | plan, section 2.1a |
| S1: lifetime rule, guard order, route lookup (database-free) | ✅ in HEAD | `a78d7c2f2`, restored in `4d83dffc0`; evidence `docs/publicdigit/election/evidence/2026-10-11-voter-slug-lifecycle-s1/` (72 tests / 424 assertions, exit 0) |
| S2 migration design (G3) | ✅ approved, 2026-10-12 (isolated test database only) | plan, sections 11 and 11.0; `8b86967c4` |
| Isolated test database (G4) | ⛔ **not provisioned. This blocks everything below** | — |
| G5–G8 | not executed | plan, header |

## A. Decisions by the Human PO / ARB
- [ ] **A1 — G4:** provision an isolated PostgreSQL test database for this work only (**never `nrna_test`**) and name it exactly. **Currently blocking.**
- [ ] **A2:** authorise S2 (after A1).
- [ ] **A3 — O-5:** what another voter sees on someone else's link: the ownership error, or a plain "not found". Needed before S7.
- [ ] **A4:** authorise S3 … S9, one slice at a time.
- [ ] **A5 — release:** name a release owner; confirm the section 11.1 operational controls (maintenance mode, access freeze, process checks, detection snapshot); then approve G8.

## B. S2: the database rule change (after A1 + A2; Claude)
- [ ] **B1:** test setup that aborts unless `current_database()` equals the approved name, **before** any database refresh. S2 tests are kept out of the default test run (plan 11.0, S2-3).
- [ ] **B2 — RED tests:**
  - section 11 tests 1–5;
  - behaviour tests S2-2 (a)–(d): an archived plus a new current link is allowed; a second current link is rejected; several archived links are allowed; restoring an archived link while a current one exists is rejected;
  - `PgUniqueViolationShapeTest`.
- [ ] **B3 — GREEN migration:** check the old rule in `pg_constraint`; drop it with `DROP CONSTRAINT`; create the partial unique index `WHERE deleted_at IS NULL`; set a lock timeout; `down()` refuses on drift or history.
- [ ] **B4:** correct the comment in `VotingIntegrityCharacterizationTest` (S2-7).
- [ ] **B5:**
  - regression;
  - evidence: database identity per run, checksums, credential check;
  - developer guide;
  - commit after approval.

## C. Later slices (each needs its own authorisation)
- [ ] **C1 — S3, issuer:** issue / reuse / replace links; locks on the `codes` row; the real two-process concurrency test (**G6**).
- [ ] **C2 — S4, entry points:** `/vote`, `elections.start`, recovery paths; fix the `ArgumentCountError`.
- [ ] **C3 — S5:** remove every in-place lifetime extension; cleanup never hard-deletes real links.
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

**Traceability:** plan sections 2.1a, 11, 11.0, 11.1, 13, 17 · story `docs/publicdigit/backlog/PBDIGIT-274-voter-slug-lifecycle-fixed-lifetime-and-replacement.md` · commits `4d83dffc0`, `ac50a614a`, `8b86967c4`.
