# Plan: PBDIGIT-274, voter-slug lifecycle: revision 10 (consolidated, FROZEN for decisions)

> **Governed copy (ES-004.2), created 2026-10-11 23:02.** The working draft is `.claude/plans/distributed-snuggling-swan.md`; the content is identical at the time of copying, except this note. **The PO decided on 2026-10-12 that this governed copy is canonical;** the working draft is now only a pointer. For S1's status, see section 18.

**Status: PLAN APPROVED (G2) by the Human PO / ARB, 2026-10-11, in session.** Frozen at rev 10 plus R11-1, with the decisions in section 2.1a.
- **S1** was authorised and is done (database-free; in HEAD via `4d83dffc0`).
- **G3 approved 2026-10-12** (the migration design, sections 11 + 11.0; implementation in S2 and execution **on the isolated test database only**).
- **S2 implementation still needs its own explicit authorisation** and is blocked by G4. Every later slice, G4, G5, G6, G7 and G8 remain open. **No production migration or deployment is authorised.** *(Status updated 2026-10-12.)*
- Further decisions: **O-9 = (a)**, a missing environment variable means the documented default 30. **O-10 = (B)**, strict validation everywhere. Its R4–R8 reader changes belong to S6, behind the characterisation precondition in section 6. **O-5 is still open (before S7).**

**Mandatory gates (status 2026-10-12: G2 and G3 met; G1 not executed as a separate review, G2 was granted without it; G4–G8 open):**
- **G1:** independent Principal Review of rev 10, checked **against the finding register (section 17) only**, not a fresh review from scratch.
- **G2:** explicit Human PO / ARB approval of the plan and of every decision marked "plan prerequisite" (section 2.2).
- **G3:** separate migration approval (section 11). **APPROVED 2026-10-12** (design, section 11 + 11.0; isolated test database only).
- **G4:** the isolated PostgreSQL test database provisioned, its name approved.
- **G5:** `current_database()` verified before **every** database run. Never `nrna_test` or any shared database.
- **G6:** the real two-process, two-connection concurrency test **executed and passed**. A simulation never satisfies it.
- **G7:** the cast success / failure contract (section 9) **executed and passed**.
- **G8:** the release order: **the migration (G3) runs before any 274 code is deployed.** Otherwise the new issuer's replacements hit the old `voter_slugs_election_id_user_id_unique` [F `create_uuid_voter_slugs_table.php:38`], get classified as "unknown constraint", and every expired voter receives `VoterSlugIssuanceConflict`.
- **S1** (database-free) needed its own explicit authorisation: granted 2026-10-11, done. **S2** needs its own explicit authorisation: not granted.

**Evidence labels:**
- **[F]** a fact established by **read-only code inspection** on 2026-10-11 (file:line). It is **not** runtime evidence;
- **[D]** design (proposed behaviour, not existing behaviour);
- **[PO]** a Human PO decision recorded in this session;
- **[A]** assumption;
- **[H]** hypothesis that needs runtime evidence;
- **[G]** gate.

**Test status:** `planned` → `written` → `executed` → `passed`. **Every test in this plan is `planned`.** No invariant is verified. A test name or acceptance criterion is not evidence.

**Revision history:**
- **rev 10 (bounded correction, then FREEZE):** R10-1 O-7 scope matches the section 12 inventory (register, L-11, tests, acceptance); R10-2 O-13's contradictory cleanup option removed; R10-3 an exact write pause and drain in the runbook (section 11.1 step 2) and the rollback query matched to `down()`; R10-4 the pre-commit "vote confirmed" email made **decision O-15** with characterisation test L-18. A finite **finding register** (section 17) is added. **The plan is now frozen:** it reopens only on new material evidence or a missed blocking defect, not for runtime questions, which stay execution gates.
- **rev 9:** Principal Review of rev 8 (R9-1 … R9-6):
  - **R9-1:** the issuer-vs-cast residual becomes decision **O-13**; L-14 / L-15 wording aligned (section 5);
  - **R9-2:** executable failure-injection mechanics (section 9.1);
  - **R9-3:** L-17 split into two deterministic cases (section 9.2);
  - **R9-4:** a raw-slug logging traceability table and a broader inventory method (section 12); the PBDIGIT-276 overlap made an explicit tracking decision;
  - **R9-5:** a migration-first release runbook with rollback-window rules (section 11.1);
  - **R9-6:** step-1 closure. The no-election fail-open is unreachable after the guard by code reading [F] and needs a runtime test; the demo-session bypass residual becomes decision **O-14** (section 7.1).

  Only the plan changed; it **replaces rev 8**.
- **rev 8:** Principal Review of rev 7:
  - the test-status contradiction corrected (L-16 planned; G7 open; minimum G7 evidence defined);
  - the measurement boundary for L-14 (isolated test vs. real concurrency vs. production aggregate vs. history);
  - the full guard-chain trace per route group, incl. bypass analysis;
  - issuer concurrency cases without relying on a lock on a non-existent row;
  - an exact console-guard contract;
  - one retention / migration-rollback formulation;
  - O-10 (B) kept gated by evidence;
  - wording reconciled.

  **Replaces rev 7 entirely.**
  - **Independent subagent review (2026-10-11, read-only): REVISE.** Folded in here:
    - **B-1:** the issuer now locks and checks the authoritative `codes` row, so it serialises with the cast (section 5);
    - **B-2:** the issuer and cleanup reads run without model events, so refusals write nothing (section 5);
    - **SF-1:** P3 relabelled "statically non-functional";
    - **SF-2:** full raw-slug inventory; L-11 narrowed to logs and error output;
    - **SF-3:** code rollback marked [A];
    - **SF-4:** gate G8, migration before code;
    - **SF-5:** a post-commit false failure (L-17) and corrected line ranges;
    - **SF-6:** the failure-injection method;
    - **SF-7:** the O-10 gate split;
    - **SF-8:** step-1 closure caveats;
    - **SF-9:** P3 / P4 go to a new backlog item, not PBDIGIT-275;
    - **SF-10:** L-14 defined for O-6 (A);
    - plus the NOTE items.
- **rev 7:** vote-writing paths, the historical baseline, the retention / cascade conflict, the success contract, non-mutating refusals, the duration contract, tenant scope, register O-5 … O-12.
- **rev 6:** cast paths; `has_voted` instead of `status`; the transaction trace; the `withoutEvents` scope; the readers.
- **rev 5:** binding without events; migration levels; driver shape.
- **rev 4:** consolidated.
- **rev 3:** commit-safe retirement; migration atomicity.
- **rev 2:** isolated database; entitlement inside the transaction.
- **rev 1:** superseded.

## 1. Context [F]

`VerifyVoterSlug` (PBDIGIT-273, `6d3b9e1e0`) refuses steps once `now ≥ min(expires_at, created_at + T)`. The lifecycle contradicts that:
- `start()` reuses rows and keeps the old `created_at` (`ElectionVotingController.php:166-191`);
- in-place extensions (`CodeController:177-187`, `VoterSlugService:183`, `:139`);
- hard deletes (`VoterSlugService:307`, `:522`, `CleanupExpiredVoterSlugs:96`);
- entitlement missing at issuance;
- `ArgumentCountError` in `/vote` and `/test/generate-slug` (`electionRoutes.php:86`, `:388`);
- broken recovery paths;
- `createNewSlug` collides with the unique index;
- three duration sources;
- dashboard / 410 on expiry.

**273 must not ship without 274.**

## 2. Decisions

### 2.1 Recorded [PO] (this session)
- **Lifetime:** fixed from `created_at`.
- **Restart:** a new row; the old real row soft-deleted, its steps kept.
- **Session expiry:** owner-only redirect to `elections.show` (fallback `organisations.show`); JSON 403.
- **Production database:** PostgreSQL (partial index).
- **Recovery paths:** included.
- **Demo:** hard delete with a new row each time.
- **Real slugs:** never hard-deleted by the application (the boundary is in O-12).
- **Test routes:** removed.
- **O-1:** isolated database.
- **O-2:** a separate closed-election exception.
- **O-3:** re-check entitlement on issue and reuse; refuse and retire.
- **O-4:** fail closed on an invalid duration.
- **O-6 statement:** "the slugs used for successful voting equal the number of successful voters". **Scope open.**

### 2.1a Plan-prerequisite decisions recorded [PO] (Human PO, 2026-10-11, in session, after rev 10 plus R11-1)
- **O-6 = (B):** the count invariant covers **P1 slug-flow casts only**. P2 / P3 / P4 are documented exceptions; the P4 guard and the P3 disposition go to a **new backlog item**. **O-8 is not needed.**
- **O-6b = (i):** prospective only. A read-only baseline; historical mismatches reported, never changed; no absolute-equality claim.
- **O-7 = (B):** redact every in-scope log site in section 12.1 (T1–T9; T10 / T11 if classified in scope; plus any section 12.2 find), with tests. **PBDIGIT-276 stays OPEN** until the PO records the outcome at 274's closure.
- **O-11 = (a):** voted slugs are immutable; re-voting is out of scope.
- **O-12 = (a):** accept the latent parent hard-delete cascade (documented in section 10.4); a **retention / erasure policy backlog item** is created at closure.
- **O-13 = (a):** accept the issuer-vs-cast residual end state, pinned by `test_issuer_vs_cast_residual_state_is_as_accepted`.
- **O-14 = (a):** the step-1 demo-session bypass is an accepted residual risk owned by the Human PO, tracked in **PBDIGIT-275**. The tests document it and don't claim closure.
- **O-15 = (a):** accept the pre-commit "vote confirmed" email for 274; L-18 characterises it; a **follow-up story** moves the email after commit.

**Still open:** O-5 (before S7), O-9 and O-10 (before S1 / S6). **Plan approval (G2) itself is a separate explicit act.**

### 2.2 Decision register (the options as presented; O-6, O-6b, O-7, O-11, O-12, O-13, O-14 and O-15 are now decided, see section 2.1a). Recommendations (from the Principal Reviewer and Claude) are **not** decisions.

| ID | Question | Options and consequences | Recommendation | Blocks | Approver | Depends on |
|---|---|---|---|---|---|---|
| **O-6** | the scope of the count invariant | **(A) every permitted production vote-writing path:** P2, P3 and P4 must be closed, guarded or integrated (O-8) before L-14 can be claimed; a scope expansion beyond 274. **(B) P1 slug-flow casts only:** L-14 is the "prospective count invariant for P1 slug-flow casts only"; P2 / P3 / P4 stay production-capable exceptions, visible in acceptance and operational docs; **no system-wide claim** | (B) | **plan approval** | Human PO / ARB | — |
| **O-6b** | historical baseline | (i) prospective: a recorded read-only baseline; correctness judged on isolated tests (section 10.2), **no absolute-equality claim**; (ii) absolute: requires a separately approved reconciliation first | (i) | **plan approval** | Human PO / ARB | O-6 |
| **O-7** | raw voting links in logs | **(A)** keep them; residual risk recorded; no-raw-slug guarantee limited to the issuer / guard / binding code 274 writes. **(B)** redact **every confirmed raw-slug or raw-link log site** classified in scope in section 12: **T1–T9 confirmed** (`bootstrap/app.php:219`; `VoteController.php:1951, 3404, 3408`; `CodeController.php` log sites incl. `:94-98`, `:545`; `VoterSlugController.php:54-56, 152-154, 189, 286`; `ElectionController.php:240, 264-265`; `ElectionManagementController.php:425, 648`; `DemoCodeController.php:419`), **T10 (`ElectionAudit`) and T11 (`app/Exceptions/Handler.php`) conditional on classification**, plus any further log site found by the section 12.2 classification. Each one is mapped to a test or a justified exclusion; tests inspect messages **and** recursively serialised context | (B) | **plan approval** | Human PO / ARB | — |
| **O-8** | the other vote-writing paths | **only needed if O-6 = (A)** (section 8) | per section 8 | **plan approval if O-6 = (A)** | Human PO / ARB | O-6 |
| **O-11** | changing a voted slug (re-vote) | (a) never; voted slugs immutable; re-vote out of scope; (b) a separate audited design | (a) | **plan approval** | Human PO / ARB | — |
| **O-12** | voted-slug retention vs. foreign-key cascades | (a) **accept**, with one formulation used throughout (section 10.4); a retention / erasure policy as follow-up work; (b) **protect** (foreign keys to `restrict` or archive before a hard delete): a separate scope and migration, outside 274 until approved | (a) | **plan approval** | Human PO / ARB | — |
| **O-5** | a non-owner opening another voter's link | (a) keep `SlugOwnershipException` (reveals that the link exists, not the election); (b) a generic 404 identical to an unknown link, the reason logged internally | (b) | slice S7 | Human PO | — |
| **O-9** | missing `VOTING_TIME_IN_MINUTES` | (a) the documented default 30; (b) fail closed | (a) | slice S1 | Human PO | — |
| **O-10** | duration validation reach | (A) narrow: keep the `(int)` cast; accept truncation (`'20min'`→20, `'5.9'`→5), documented as **not** strict; (B) strict: remove the cast; every reader R1–R8 validates; invalid → fail closed everywhere. **A3 unverified** | (B), only if the wider impact is accepted **and** the R4–R8 coverage of section 6.4 is executed | slices S1 / S6 | Human PO | O-9 |

| **O-13** | **the issuer-vs-cast residual (R9-1)** | **Interleaving [F by code reading]:** (1) the issuer locks the voter's `codes` row; (2) it retires the expired slug S1 (soft delete, `is_active=false`, `status=expired`), inserts S2 and commits; (3) a cast already in flight on S1 (it passed the guard before expiry) resumes; its CAS on the `codes` row succeeds; it marks the **soft-deleted** S1 `has_voted=true`, `status='voted'` (`save()` by primary key, no SoftDeletes scope) and commits. **Final state:** one vote; `codes.has_voted=true`; S1 soft-deleted **and** voted; S2 current, `has_voted=false`, unusable for a cast (the CAS refuses); later issuer calls return `AlreadyVoted`. **Options:** (a) **accept** this final state; L-14 holds (S counts soft-deleted rows); documented. (b) **close the race:** the cast locks the slug row `FOR UPDATE` early, or updates `WHERE deleted_at IS NULL` and asserts 1 row. A cast-side change **outside 274**, needing its own story and approval | (a), with an explicit acceptance test; (b) as a separate story if you want the state impossible. *(Rev 10: the former "issuer-side cleanup" option was **removed**, because it contradicted the issuer's write-free `AlreadyVoted` refusal, R10-2.)* | **plan approval** (it decides the L-15 wording and the concurrency acceptance) | Human PO / ARB | — |
| **O-14** | **the step-1 demo-session bypass (R9-6)** | `VoteEligibility.php:36-40` skips `canVote()` for any route once `session('selected_election_type')==='demo'`, so a closed **real** election may be reachable at step 1 under that flag. (a) **accept as a residual risk**, owned by the Human PO, tracked in **PBDIGIT-275** (which already lists this bypass); (b) fix it in 274 (a scope expansion) | (a) | **plan approval** | Human PO | — |

| **O-15** | **pre-commit "vote confirmed" email (R10-4)** | **[F]** `SendVoteSavingCode` is a plain `Notification`, **not queued** (`app/Notifications/SendVoteSavingCode.php:10`; `ShouldQueue` imported but not implemented). It is sent **synchronously inside the cast transaction, before `DB::commit()`** (`VoteController.php:1903-1920` vs `:1922`). Its template (`resources/views/mail/send_vote_saving_code.blade.php`) says "Vote Successfully Recorded!", "Vote Status: CONFIRMED", "securely saved in our database", and carries the receipt code. **Sequence [H]:** the email is sent, then the commit fails (or the transaction was already aborted by a swallowed error, section 9), so the voter holds a "CONFIRMED" email for a vote that didn't commit. **Options:** (a) **accept** the residual risk for 274, documented; L-18 test characterises today's behaviour; (b) **send only after commit** (move the send after `:1922`, or `afterCommit`). A cast-path change, **outside 274** unless explicitly brought in; its own approval | (a) for 274, plus a follow-up story for (b) | **plan approval** | Human PO / ARB | — |

**PBDIGIT-276 tracking (part of O-7):** if O-7 = (B), 274 implements the renderer-URL redaction, which is 276 item 1. **276 is not closed by this plan.** At 274's closure the PO must record on 276 either "item 1 covered by 274 (commit ref)" or "keep open". Until then 276 stays `OPEN`.

**G2 requires:** O-6, O-6b, O-7 (incl. the 276 tracking action), O-11, O-12, O-13, O-14, O-15, and O-8 if O-6 = (A). O-5 blocks S7; O-9 and O-10 block S1 and S6.

## 3. Invariants (all **planned / unverified**)

| # | Invariant | Named test(s) | Status |
|---|---|---|---|
| L-1 | the issuer reuses only if: not soft-deleted ∧ `is_active` ∧ `has_voted=false` ∧ `status≠expired` ∧ `now < min(expires_at, created_at+T)` (the guard doesn't reject voted slugs) | `VoterSlugLifetimeTest::test_reusable_*`, `VoterSlugIssuerTest::test_reuses_only_reusable` | planned |
| L-2 | `now == created_at+T` / `== expires_at` are expired; nulls count as expired; UTC | `VoterSlugLifetimeTest::test_boundaries_*` | planned |
| L-3 | duration per O-9 / O-10; invalid → fail closed (503, no slug) | `VoterSlugLifetimeTest::test_input_classes`, `VoterSlugLifetimeMisconfiguredRequestTest` | planned |
| L-4 | reuse writes nothing | `VoterSlugIssuerTest::test_reuse_writes_nothing` | planned |
| L-5 | at most one current row per (election, user) | `VoterSlugActiveIndexMigrationTest`, `VoterSlugIssuerRealConcurrencyTest` (G6) | planned |
| L-6 | replacement: a new id and string; old row soft-deleted; steps kept | `VoterSlugIssuerTest::test_replacement_keeps_history` | planned |
| L-7 | exact (election, user); only the `'tenant'` scope is removed | `VoterSlugIssuerTest::test_other_election_and_voter_untouched`, `VoterSlugBindingTest::test_only_tenant_scope_removed`, `test_tenant_isolation` | planned |
| L-8 | fresh entitlement in the transaction; not entitled → the retirement commits, then refusal | `VoterSlugIssuerTest::test_lost_entitlement_retirement_persists` | planned |
| L-9 | demo authorisation | `VoterSlugIssuerTest::test_demo_authorisation` | planned |
| L-10 | owner-only restart; closure → no restart | section 7.2 tests | planned |
| L-11 | no raw slug in **logs and error / exception output**, within the O-7 scope: under (B), **every in-scope site in section 12.1 (T1–T9, plus T10 / T11 if classified in scope, plus any section 12.2 find)**; under (A), only 274's new code. **Legitimate UI props that must carry the slug to build links** (`CodeController.php:228, 530`; `electionRoutes.php:540`) **are excluded**: a response-body scan applies to error responses only | section 12 tests | planned |
| L-12 | refusals and recovery are non-mutating and atomic | section 10.3 tests | planned |
| L-13 | the binding: 404 for replaced / soft-deleted / unknown; no model event or write while resolving | section 7.2 tests | planned |
| L-14 | **If O-6 = (B):** the prospective count invariant for P1 slug-flow casts only (counted by `has_voted`), judged by isolated tests (section 10.2); not a system-wide equality across P2 / P3 / P4. **If O-6 = (A):** the prospective count invariant for **every** permitted production vote-writing path. It can be claimed only after the O-8 closures / guards are implemented and their tests pass, and it is measured by the same isolated method extended to each remaining permitted path | section 10.2 tests (plus `ConsoleVoteWriterGuardTest` and route-absence tests under (A)) | planned |
| L-15 | **voted-slug immutability on application paths while the parents exist:** a slug with `has_voted=true` is never soft-deleted, hard-deleted, replaced, reused for a cast or reset by the application. A parent **hard** delete may cascade-delete it (O-12). **Under O-13 (a)** a slug that is *already retired* may **become** voted by an in-flight cast. That is the accepted residual: the invariant protects voted slugs from being changed, and doesn't promise that a retired slug is never voted | section 10.3 tests; `VoterSlugIssuerRealConcurrencyTest::test_issuer_vs_cast_residual_state_is_as_accepted` | planned |
| L-16 | **success contract:** "success" is reported only if the vote, the participation and (P1) the slug `has_voted` committed | section 9 tests (G7) | **planned / unverified**; G7 open |
| L-17 | **no false failure:** once the cast has committed, a failure in post-commit work (audit log `VoteController.php:1927-1932`; `current_step` save `:1946-1949`, still inside the outer `try`, catch `:1979`) must not tell the voter "An error occurred… please try again" | `CastSuccessContractTest::test_post_commit_audit_failure_never_reports_retry` (L-17a), `…::test_post_commit_current_step_failure_never_reports_retry` (L-17b) | **planned / unverified**. If the existing code violates it → **stop for a PO decision** (no repair in 274) |
| L-18 | **pre-commit external side effect (O-15):** under O-15 (a), the plan characterises (it doesn't fix) whether a "vote confirmed" email can go out for a cast that then fails to commit. Under (b), the email is sent only after commit (a separate story) | `CastSuccessContractTest::test_confirmation_email_vs_commit_failure`: `Notification::fake()`; forced commit failure through a **deferred constraint trigger** on `votes` (`CREATE CONSTRAINT TRIGGER … DEFERRABLE INITIALLY DEFERRED` raising at COMMIT; same C1 / C2 mechanics as section 9.1); asserts on C2 that the vote didn't commit, and records whether `SendVoteSavingCode` was dispatched | **planned / unverified** |

## 4. Gate on database work
- A scratch bootstrap pins the approved database and **aborts unless `current_database()` matches**.
- One named file per run.
- No `php artisan migrate`; no other database touched.

## 5. Issuer (proposed behaviour [D])
- **The calling contract:** `DB::transactionLevel() === 0`, otherwise `LogicException`.
  - **[F]** no transaction in `ElectionVotingController`, `ElectionController`, `VoterSlugController`, `Admin/VotingSecurityController`, `GenerateRecoverySlug`, `VoterRecoveryService`, or `app/Http/Middleware`.
  - `ElectionManagementController:1124,1146` are unrelated to `:421,:643`.
  - **[A1]** none is framework-managed.
- **The attempt loop (outside the transaction):** each attempt is `DB::transaction(fn () => decide())`. Laravel rolls back before re-throwing [F `ManagesTransactions.php:85-104`].
  - On SQLSTATE `23505` [F `PostgresConnection.php:53-56`], classify by the constraint name in `getPrevious()->errorInfo[2]` (`PgUniqueViolationShapeTest`, G4):
    - `voter_slugs_slug_unique` → a new string (at most 3);
    - `voter_slugs_active_election_user_unique` → a full re-decision (at most 1);
    - unknown or unparseable → `VoterSlugIssuanceConflict(previous)`, no retry, no slug.
  - Other errors propagate.
- **`decide()`:** every **read** of `VoterSlug` / `DemoVoterSlug` inside `decide()` runs in `Model::withoutEvents(fn () => <query>)`, with only the query inside, so the `retrieved` hook (`VoterSlug.php:129-151`, which writes `status='expired'` on load [F]) **cannot write during issuance or refusal** (B-2).
  1. **Real elections: `SELECT … FOR UPDATE` on the voter's `codes` row for (election, user)** (unique [F `create_uuid_codes_table.php:53`]). The cast's participation CAS updates that same row (`VoteController.php:1807-1833` [F]), so **issuer and cast serialise on it** (B-1):
     - if the cast commits first → the issuer sees `codes.has_voted = true` → `AlreadyVoted`;
     - if the issuer locks first → the cast's CAS waits until the issuer commits.

     **No code row yet** → the voter hasn't reached the code step, so no concurrent cast is possible.
  2. `SELECT … FOR UPDATE` on the current slug row (it may not exist).
  3. If **`codes.has_voted = true`** (authoritative [F `:1801-1802`]) **or** any slug row of (election, user), soft-deleted included, has `has_voted=true` → `AlreadyVoted`, **no write**.
  4. Real: fresh entitlement; not entitled → retire the current row → `NotEntitled`.
  5. Demo: authorisation (there are no demo codes in this check; demo is outside L-14).
  6. Reusable → `Reused`.
  7. Otherwise retire and insert → `Issued`.
- **The remaining interleaving is NOT resolved. It is decision O-13 (section 2.2).** It is described there with its final state. **Count consistency (L-14) is not lifecycle correctness:** under O-13 (a) a replacement slug may exist for a voter who has voted, and a retired slug may become voted.
  - **The claim that stays valid in every option:** "no slug is issued **after** the cast has committed; a slug issued concurrently before it can't be used to cast."
  - Under O-13 (c), closing the race needs a cast-side change outside 274.

### 5.1 Concurrency cases (planned tests; G4, and G6 where marked)
**Mechanism [D]:** the row lock serialises only when a current row exists. When **no row exists, nothing is locked**, so **the partial unique index is the serialising mechanism**: the losing insert gets 23505 on `voter_slugs_active_election_user_unique`, rolls back, and re-runs `decide()`, which then locks the winner's row.

| Case | Expected database state | Mechanism | Test |
|---|---|---|---|
| no row | one new current row | insert | `VoterSlugIssuerTest::test_no_row_issues_one` |
| a current reusable row | unchanged, returned | lock plus no write | `…::test_reuse_writes_nothing` |
| a soft-deleted row with `has_voted=true` | nothing written; `AlreadyVoted` | step 2 query (sees soft-deleted rows) | `…::test_soft_deleted_voted_row_refuses_without_write` |
| a current row with `has_voted=true` | nothing written; `AlreadyVoted` | lock plus step 2 | `…::test_current_voted_row_refuses_without_write` |
| concurrent issuers, **no row initially** | exactly one current row; both return it | **the unique index**, not a lock | `VoterSlugIssuerRealConcurrencyTest::test_no_row_two_processes` (**G6**) |
| concurrent issuers on an expired current row | one replacement; the old row soft-deleted once | the lock on the existing row, plus the index | `…::test_expired_row_two_processes` (**G6**) |
| issuer concurrent with a P1 cast on the same voter | cast first → `AlreadyVoted`, no write; issuer first → the cast waits on the `codes` row lock, then commits one vote (possibly marking the soft-deleted old slug); **never a slug issued after the cast committed** | **row lock on the `codes` row** (step 1). The cast's `Cache::lock` (`VoteController:1636-1640`) is keyed by slug id and is **not** relied on. **[H]** the interleaving is shown, not assumed | `…::test_issuer_vs_cast_two_processes` (**G6**): both orders forced by holding each lock in turn; plus `…::test_issuer_vs_cast_residual_state_is_as_accepted`, which asserts the final state **chosen in O-13** (under (a): one vote; S1 soft-deleted and voted; S2 current and unusable; the next issuer call `AlreadyVoted`) |
| a 23505 on the slug string | retry with a new string | classification | `VoterSlugIssuerConflictTest::test_collision_retries_new_string` (simulation) |
| a 23505 on the active index | full re-decision | classification | `…::test_race_redecides` (simulation; it does **not** satisfy G6) |
| refusals | the full row snapshot (incl. `status`) and `voter_slug_steps` unchanged **by the issuer** | no write before step 3; reads without events (B-2) | section 10.3 tests. Later loads **outside** the issuer (e.g. post-ownership middleware) may still trigger the hook; that is out of scope and the tests don't cover it |

## 6. Duration contract (O-9 / O-10)

**[F]** `config/voting.php:34` is `(int) env('VOTING_TIME_IN_MINUTES', 30)`; `config/election.php:30` holds an unused key.

| # | Reader | Controls |
|---|---|---|
| R1 | `VerifyVoterSlug.php:106` | slug lifetime cap |
| R2 | `VoterSlugService.php:82` → issuer | new `expires_at` |
| R3 | `VoterSlug.php:157`, `DemoVoterSlug.php:194` (undefined `voting.slug_expiration_minutes`) | default `expires_at` |
| R4 | `CodeController.php:49` (stored at `:811`) | real code window |
| R5 | `DemoCodeController.php:45` (`:773`) | demo code window |
| R6 | `CheckVotingWindow.php:75` (column ?? config) | demo voting window |
| R7 | `CodeVerificationTrait.php:173` (column ?? config) | code verification |
| R8 | `VoteController.php:923, 963, 1050, 1092`; `DemoVoteController.php:985, 1025, 1112, 1154`; `CodeController.php:521, 527`; `DemoCodeController.php:392, 398`; `Models/Demo/DemoCode.php:136` (column ?? config) | display / submission windows |

| Input | (A) narrow | (B) strict |
|---|---|---|
| missing | 30 (O-9 a) | 30 (O-9 a) |
| `30` | 30 | 30 |
| `0`, `-5` | fail closed | fail closed |
| `abc` | cast → 0 → fail closed | refused → fail closed |
| `20min` | **truncated to 20: accepted, not strict** | refused → fail closed |
| `5.9` | **truncated to 5: accepted, not strict** | refused → fail closed |

- **(A)** touches R1–R3 only.
- **(B)** touches R1–R8.
- **A3** (valid-value semantics unchanged in R4–R8) is **unverified**. The gate is split to avoid circularity (SF-7):
  - **Precondition for S6 under (B):** characterisation tests `DurationReaderRegressionTest::test_valid_value_unchanged_{R4..R8}`, written and **executed against the current code**. At least one per reader group, covering code entry and the voting / submission windows.
  - **S6's own RED → GREEN:** `…::test_invalid_value_fails_closed_{R4..R8}`. RED before the change, GREEN after.
  - **After S6:** the characterisation tests are re-executed and must still pass.
- **Fail closed:** `VoterSlugLifetimeMisconfigured extends VotingException`, 503, generic message. Renderer [F `bootstrap/app.php:213-235`]: web → dashboard; JSON 503. `Log::critical(reason, configured_type)` with no value and no slug.

## 7. Route boundary and full guard chain

### 7.1 Trace [F, read-only route registration plus middleware code]
- **Binding (all `{vslug}` routes, before every route middleware** [F `Middleware.php:490`, `bootstrap/app.php:128-132`]):
  - today: `withoutGlobalScopes()` with an expiry 410;
  - proposed [D]: `withoutEvents` around **only** `$modelClass::on($c)->withoutGlobalScope('tenant')->where('slug', …)->first()` (the scope name `'tenant'` [F `BelongsToTenant.php:44`]); SoftDeletes stays active [F `VoterSlug.php:13`]; not found → 404; **no expiry evaluation**.

| Route group (`routes/election/electionRoutes.php`) | Chain after binding [F] | Gaps |
|---|---|---|
| **real step 1** `slug.code.create` / `slug.code.store` (`:497`) | `voter.slug.verify` (V-1 auth, V-2 type, V-3 owner, V-5a soft-deleted, V-4 expiry [D], V-5b active, V-6 election / organisation) → `voter.slug.consistency` → `ensure.election.voter` (entitlement) → `vote.eligibility` (`canVote` [F demo-session bypass, PBDIGIT-275]) → `vote.organisation` | no `voter.slug.window` (closure) and no `voter.step.order` on step 1 [F]. Closure on step 1 comes from `vote.eligibility` → `ElectionLifecycle::canVote()` (`VoteEligibility.php:55-86`, redirect to the dashboard) [F]. **(b) The no-election fail-open (`if ($election)` `:59`) is unreachable after the guard, by code reading [F]:** V-6 refuses a missing or deleted election (proposed guard); `voter.slug.consistency` runs before eligibility in this chain, throws `ElectionNotFoundException` when the election is missing (`VerifyVoterSlugConsistency.php:51`) and sets the `election` attribute (`:118`); eligibility uses `$voterSlug->election ?? attribute` (`:57`). **[H] runtime test required.** **(a) The demo-session bypass (`:36-40`) is a residual = decision O-14** (tracked in PBDIGIT-275). **This is not proof that closure is enforced while that flag is set** |
| **real steps 2–5** (`:514`, inner `voting.active` `:524`) | as step 1, plus `voter.slug.window` (closure → `ElectionVotingClosedException` [D]), `voter.step.order`, `validate.voting.ip`, `throttle:10,1`; vote steps add `voting.active` | — |
| **final cast** `slug.vote.store` (`:531`) | the steps 2–5 chain → `VoteController::store`: `forCast` entitlement uncached, lifecycle `canVote`, participation CAS, transaction (section 9) | — |
| **demo** (`:577`, inner `election.demo` `:579`) | `auth:sanctum`, `verified` → `voter.slug.verify` → window → consistency → `voting.code.window` → step order (demo steps not mapped [F, 275]) → eligibility → organisation → `election.demo` | step order inactive for demo (275) |
| **P2 `POST /votes`** (`:169`) | `auth:sanctum`, `verified`, `vote.eligibility` → `VoteController::store` | **no slug guard at all**: outside L-14 under (B) |
| **P3 `POST /deligatevotes`** (`:236`) | `auth:sanctum`, `verified` → `DeligateVoteController::store` | no slug guard. **Statically non-functional** (section 8); outside L-14 under (B) |

- **Controllers read the guard's attribute:** `CodeController` (`:63, 246, 430, 552, 679, 776`), `VoteController` (`:100, 225, 569, 606, 665, 729, 1635, 1850`) and `DemoCodeController` / `DemoVoteController` take the slug from `$request->attributes->get('voter_slug')`, which the guard sets after all checks. They don't look it up again. `VoteController:1946` uses `$request->route('vslug')`, which is the same bound instance: harmless, but not the attribute.
- **Guest existence oracle:** the binding runs before V-1, so a guest gets 404 for an unknown slug but a login redirect for an existing one. O-5 (b)'s "identical 404" can't cover guests. This is tracked in PBDIGIT-275 (no `auth` on the real slug groups).
- **Exception:** `DemoVoteController.php:1471` queries a **real** `VoterSlug` by `user_id`. It isn't an ownership bypass (filtered by the authenticated user), but it is an anomaly in the demo flow. **Outside L-14; recorded for PBDIGIT-277.**
- **Redirect branches:** the expiry redirect is produced **only** in the guard after V-3 [D]. The binding produces only 404. **[H]** no other middleware redirects with an election-specific target before V-3. That holds for the chain above, because everything else runs after the guard.
- **Tenant isolation:** removing `'tenant'` lets a link resolve regardless of the session's tenant, as today. Isolation is enforced by **V-3** (the slug's `user_id` = the authenticated user) and **V-6** (the slug's organisation = its election's organisation) before any controller runs.

### 7.2 Tests (planned)
- **No database:**
  - `VoterSlugBindingTest::test_binding_fires_no_model_events`
  - `…::test_dispatcher_restored_after_success_and_after_exception`
  - `…::test_unrelated_model_events_fire_after_binding`
  - `…::test_only_tenant_scope_removed`
- **Database (G4):**
  - `test_tenant_isolation_other_tenants_slug_not_usable`
  - `test_owner_expired_current_slug_restart_redirect` (web / JSON; row unchanged)
  - `test_non_owner_expired_slug_no_redirect_no_election_no_write` (row byte-identical; O-5)
  - `test_guest_expired_slug_no_write`
  - `test_slug_marked_expired_by_retrieval_restart_for_owner`
  - `test_replaced_and_soft_deleted_slug_404_identical_to_unknown`
  - `test_unknown_slug_404`
  - `test_valid_owner_slug_passes`
  - `test_election_closed_on_steps_2_to_5_shows_closed_without_restart`
  - step 1 (R9-6):
    - `test_step1_closed_real_election_refused_without_demo_flag`;
    - `test_step1_demo_session_flag_characterisation`: records today's behaviour under the flag; it asserts the O-14 decision, and **if O-14 = (a) it documents the bypass rather than claiming closure**;
    - `test_step1_missing_election_refused_before_eligibility` (the slug's election hard-missing or soft-deleted → V-6 / consistency refuse; eligibility is never reached);
    - `test_step1_election_organisation_mismatch_refused` (V-6).

**Guard order [D]:** V-1 → V-2 → V-3 (O-5) → V-5a → **V-4** → V-5b → V-6. The decision-driven change to 273's `test_a_slug_with_expired_status_is_rejected_even_when_still_marked_active`: Invalid → Expired.

## 8. Other vote-writing paths (O-8, **only if O-6 = (A)**) [F]

| Path | Production-capable? | Vote | Participation | Slug |
|---|---|---|---|---|
| P1 `slug.vote.store` | yes | `saveValidatedVote` | CAS `:1807-1830` | `:1876-1878` |
| P2 `POST /votes` | yes | the same `store` | yes | **no** (`:1875` `if ($voterSlug)`) |
| P3 `POST /deligatevotes` | the route is registered, but it is **statically non-functional** [F]: `store` calls `$this->vote_post_check()` (`:140`), `generateAndStoreVerificationKey()` (`:176`) and `markUserAsVoted()` (`:179`), none of them defined in the class, so `Controller::__call` throws `BadMethodCallException`. `Vote`, `Hash` and `Log` aren't imported (`:5-14`), so `new Vote()` (`:215`) would resolve to `App\Http\Controllers\Vote`. The catch (`:194`) rolls back, then `Log::error` fails → 500. **[H]** runtime confirmation | would be `new Vote()` `:215` (unreachable statically) | a write is attempted through the undefined `markUserAsVoted()` (`:179`) | no |
| P4 seven console commands (`test:create-votes`, `test:random-votes`, `test:full-election`, `election:simple-populate`, `election:populate-all`, `test:vote-counting`, `test:with-himani`) | **yes: no environment guard, no confirmation** | `Vote::create` | no | no |

**If O-6 = (A), all of these are required before L-14 can be claimed:**
- **P2:** (a) close (remove the route; verified by `route:list`-equivalent route-table test `test_votes_route_absent`), or (b) bind it to a slug (the same chain as P1).
- **P3:** first **establish whether delegated voting is live**: product documentation, UI links to `/deligatevotes` (grep of `resources/js`), and production usage from the access log, which the PO / ops must provide. Then (a) close it, or (b) integrate it (consume participation and mark a slug under the same transaction).
- **P4, the exact guard contract [D]:**
  - each command runs **only** when `app()->environment()` is exactly `local` or `testing`;
  - in **any other** environment (`production`, `staging`, unknown or empty) it prints a refusal and **exits non-zero before touching the database**;
  - **there is no override flag**: the rev-7 `--force-non-production` idea is dropped as ambiguous;
  - verified by `ConsoleVoteWriterGuardTest::test_{command}_refuses_outside_local_testing`, which sets the environment to `production` and to `staging`, asserts a non-zero exit, and asserts the vote count is unchanged.
- **P3 under (A):** because it is statically non-functional, **closing it** (removing the route) is the simplest compliant action. The "is delegated voting live?" evidence is still required before removal.
- **If O-6 = (B):** P2 / P3 / P4 stay outside L-14 and **are not secured by 274**. The P4 console guard and the P3 delegated-voting disposition go to a **new backlog item** (the next free ID, created at plan closure per the end-of-commission rule). **Not PBDIGIT-275**, whose scope is the request-path gaps around the slug guard (SF-9).

## 9. Cast success / failure contract (L-16, G7)
- **[F] trace** (`VoteController::store`):
  - `:1474` records the outer level, then `:1478` `DB::beginTransaction()`;
  - refusals → `failCastClosed` (rollback above the outer level and lock release, `:2051-2056`);
  - `:1639` `Cache::lock`;
  - `:1807` CAS;
  - `:1839-1841` vote save (no transaction control or catch inside `saveValidatedVote` `:2830+`);
  - **`:1849-1868` step-5 record in `try/catch(\Exception)` (swallowed); the recorder is built with `new \App\Services\VoterStepTrackingService()` (`:1852`), not through the container**;
  - `:1871` `markUserAsVoted`;
  - `:1876-1878` slug voted;
  - **`:1881-1901` `ReceiptCode::firstOrCreate` in `try/catch(\Exception)` (swallowed)**;
  - `:1903-1920` email (no database);
  - `:1922` `DB::commit()`;
  - **post-commit, still inside the outer `try`:** audit log `:1927-1932` and `current_step=5` `save()` on the voted slug `:1946-1949` (no transaction or lock). That save is an application write to a voted slug, but it changes `current_step` only, not `has_voted` (consistent with L-15);
  - outer `catch (\Throwable)` (`:1979`) → `failCastClosed`. A post-commit throw yields "An error occurred… please try again" **after** the vote committed. That is a false failure, L-17.
- **[H]:** a PostgreSQL error swallowed at `:1849-1868` or `:1881-1901` aborts the transaction. The outcome (a false "success" with everything rolled back, or a later 25P02 leading to an error response) must be measured.

### 9.1 Failure-injection mechanics (R9-2; planned, mechanism **unverified until first executed**)

**Facts [F]:**
- **The harness opens no per-test transaction on PostgreSQL:** `tests/TestCase.php` `beginDatabaseTransaction()` returns early for pgsql ("RefreshDatabase uses migrate:fresh"). So DDL and data written by the test **commit immediately**.
- **Laravel feature-test HTTP requests run in-process on the same default connection (same PDO session).** That is framework behaviour **[A]**, to be confirmed by the first executed test.
- **`ElectionAuditService` is container-resolved** (`app(ElectionAuditService::class)`, `VoteController:1928`, `:1937`).
- **The step-5 recorder (`new VoterStepTrackingService()`, `:1852`) and `ReceiptCode::firstOrCreate` are not container-resolved.**

**The design [D]:**

| Aspect | Mechanism |
|---|---|
| connections | **C1** = the default connection, used by the test setup **and** the in-process HTTP request. **C2** = a second named connection, cloned from the approved test database config with its own PDO session, used **only** for independent reads, trigger cleanup and the leftover check |
| trigger creation | on **C1, before the request, outside any transaction** (the harness opens none on pgsql), so it commits and is visible to the request on the same session. Names are unique per test (`t274_fail_<test>_<random>`) plus a `plpgsql` function `t274_fail_fn_<…>()` that `RAISE EXCEPTION 't274 injected'` |
| targets | G7-a `BEFORE INSERT ON voter_slug_steps`; G7-b `BEFORE INSERT ON receipt_codes`; section 10.2(1) forced failure `BEFORE INSERT ON votes` |
| independent verification | after the request, **C2** reads the vote count, the voter's `codes.has_voted` and the slug `has_voted`. C2 sees committed state only |
| transaction level | the harness opens none, so the application's `$outerTransactionLevel` is 0. Assert `DB::connection('C1')->transactionLevel() === 0` after the request. A non-zero level means the application leaked a transaction |
| guaranteed cleanup | in `finally` and `tearDown`: (1) if C1's level > 0 → `DB::rollBack()` until 0, which clears an aborted transaction; (2) **on C2** (never aborted): `DROP TRIGGER IF EXISTS … ; DROP FUNCTION IF EXISTS …`; (3) **on C2**, assert that no `t274_%` trigger or function remains (`pg_trigger`, `pg_proc`). If cleanup fails, the test fails loudly. The next `RefreshDatabase` `migrate:fresh` also drops everything (a second safety net) |
| privileges | the test-database role must own the tables (true after `migrate:fresh` by that role **[A]**) and be able to `CREATE FUNCTION` in `plpgsql` (default **[A]**). Recorded in the evidence; if missing → G7 is blocked |
| status | **if the first execution shows C1 / C2 visibility or cleanup behaving otherwise, G7 stays blocked and the mechanism returns to design.** No experiment in this plan revision |

### 9.2 L-17 split into two post-commit cases (R9-3; planned)

| Case | Injection | Why it is post-commit | Expected HTTP | Independent check (C2) |
|---|---|---|---|---|
| **L-17a** audit failure | bind a fake `ElectionAuditService` in the container whose `log()` throws (deterministic; it doesn't depend on the audit storage) | `log()` is called at `:1928`, **after** `DB::commit()` at `:1922` [F] | **expected by the contract:** no "please try again" message; the voter is sent to the post-vote page (or shown a neutral message). **Today [H]:** the outer catch (`:1979`) → `handleVoteError("An error occurred… Please try again.")`. **If confirmed → stop for a PO decision** | the vote exists, `codes.has_voted=true`, slug `has_voted=true` |
| **L-17b** `current_step` failure | an Eloquent `updating` listener on `VoterSlug`, registered by the test, throws **only if `isDirty('current_step')` and `current_step === 5`** (the cast's own save at `:1878` changes `has_voted` / `status`, not `current_step`, so it isn't hit) | the save is at `:1946-1949`, after `:1922`; the audit fake is a no-op so execution reaches it | same as L-17a | same as L-17a, plus `current_step < 5` |

- **Proof that the cast had committed when the failure happened:** the listener / fake records `DB::connection('C1')->transactionLevel()` at the failure point (**must be 0**), and C2 sees the vote immediately afterwards.
- **Tests:**
  - `CastSuccessContractTest::test_post_commit_audit_failure_never_reports_retry`
  - `…::test_post_commit_current_step_failure_never_reports_retry`
- **The contract [D]:** "success" only if the vote, the participation and the slug `has_voted` committed (P1).
- **Tests (planned):**
  - `CastSuccessContractTest::test_failure_in_step5_record_never_reports_success`
  - `…::test_failure_in_receipt_code_never_reports_success`
- **Minimum evidence to close G7**, per test:
  1. the exact command, and the `current_database()` proof;
  2. the exit status;
  3. the HTTP status, `Location` header and flash keys;
  4. the committed counts after the request (vote rows for the election, the voter's `codes.has_voted`, the slug `has_voted`), read on a **separate connection**;
  5. `DB::transactionLevel()` back at the outer level;
  6. the `voter_slug_transition:{id}` lock released (`Cache::lock(...)->get()` succeeds afterwards).

  **No evidence is fabricated.** If the current code violates L-16 → **stop for an explicit PO / ARB decision**; no repair in this story.

## 10. Count invariant, baseline, immutability, retention

### 10.1 Counting rules (per election E, real elections)
- **V(E):** votes;
- **C(E):** codes with `has_voted=true`;
- **S(E):** voter slugs with `has_voted=true`, soft-deleted included.
- Aggregate only (the votes table has no `user_id` / `voter_slug_id` [F `create_uuid_votes_table.php:11-16`]).

### 10.2 Measurement boundary (O-6 = (B), O-6b = (i))
1. **Isolated P1 test** (G4; the only proof of L-14):
   - a fresh election in the isolated database, **no other writers**;
   - record V, C and S; run N P1 casts sequentially, with interleaved expiries, replacements and one forced cast failure;
   - assert ΔV = ΔC = ΔS = successful casts, and no change for the failed one.
   - `CountInvariantTest::test_isolated_p1_casts_keep_deltas_equal`.
2. **Real concurrency for P1** (G6): two processes cast for two different voters, plus a double submission for one voter. Assert exactly one vote per successful voter, and that the deltas match. `CountInvariantRealConcurrencyTest::test_p1_concurrent_casts`.
3. **Production aggregates:** **not** evidence of P1 correctness, because P2 / P3 / P4 can change V (and C) without S. No production instrumentation or schema change is proposed. **Reliable attribution of production deltas to P1 isn't possible within this scope; this is stated plainly.**
4. **The historical baseline:** a read-only snapshot of V, C and S per election before deployment (`CountInvariantTest::test_baseline_read_only`). Differences are **reported, not corrected**. **No absolute-equality claim** without the separately approved reconciliation (O-6b ii).

### 10.3 Non-mutating refusals and immutability
- **[F] sites that mutate before issuing:**
  - `VoterSlugController::start:62`
  - `restart:203`
  - `GenerateRecoverySlug:86-101`
  - `Admin/VotingSecurityController:167-181`
- **[D]:** those lines are removed. Callers invoke the issuer **first**; step 2 checks before any write; only the issuer mutates. `allowRevote` is refused (O-11 a).
- **Tests (each snapshots the full slug rows and `voter_slug_steps` before and after):**
  - `test_restart_for_voted_user_changes_nothing`
  - `test_recovery_command_for_voted_user_changes_nothing`
  - `test_admin_regeneration_for_voted_user_changes_nothing`
  - `test_cleanup_never_hard_deletes_any_real_slug` (voted or not; `CleanupExpiredVoterSlugs:94-96` currently `forceDelete`s every expired row [F])
  - `test_retrieved_hook_never_changes_has_voted` (the hook rewrites `status`, not `has_voted` [F `VoterSlug.php:129-151`])
  - `test_revote_refused`

### 10.4 Retention boundary (O-12; one formulation)
- **If O-12 = (a):** "**voted-slug immutability applies to application paths while parent records exist. A hard deletion of a parent (organisation, election, user) triggers the existing database cascades** [F `create_uuid_voter_slugs_table.php:22-35`, `create_uuid_codes_table.php:35-48`, `create_uuid_votes_table.php:87-95`] **and destroys slug history** (a user hard delete removes S and C but not V). Any slug hard delete also cascades to its steps (`voter_slug_steps.voter_slug_id`, `create_uuid_voter_slug_steps_table.php:25-28` [F])."
- **Parents are soft-deleted** [F `SoftDeletes` in User / Organisation / Election]. **No application hard-delete call was found by a read-only search.** That is **limited evidence**: it doesn't exclude query-builder deletes, raw SQL, future jobs or database administration.
- The test `test_no_application_forcedelete_of_slug_parents` (a static scan) documents the current state only. It is not a permanent guarantee.
- A retention / erasure policy is follow-up work (backlog).
- **If O-12 = (b):** protection (`restrict` foreign keys or archival) is a separate scope with its own migration approval, **not** implemented in 274.

## 11. Migration (G3 APPROVED 2026-10-12 for the isolated test database only; production execution NOT approved, needs G8)
- **[F]** Laravel v11.41.3: the Migrator transaction only if pgsql and `$withinTransaction` (`Migrator.php:399-417`); a nested transaction is a savepoint (`ManagesTransactions.php:145-167`); non-concurrent index DDL is transactional.
- **[D]:** a named class, `$withinTransaction=true`; its own transaction around `checkPreconditions → dropOldIndex → createActiveIndex` (partial unique `(election_id, user_id) WHERE deleted_at IS NULL`).
- **Preconditions:** pgsql; the old index exists; no duplicate current rows; the target absent.
- **`down()`:** refuses whenever any pair has more than one row; never deletes or rewrites.
- **Operational consequence:** **once real replacement slugs accumulate, rollback of this migration is unavailable.** Recovery never means deleting or rewriting historical rows.
- **[A] Rolling back the code (SF-3)** is **not** verified to be safe. Pre-274 code reads soft-deleted rows: `ElectionVotingController.php:167-180` (`withoutGlobalScopes()->…->first()`, then it rewrites `slug`, `status='active'` and expiry on whatever row comes back, possibly a soft-deleted or voted one) and the old binding `RouteServiceProvider.php:87-88` (`withoutGlobalScopes()`).
  - After replacements accumulate, a code rollback can **mutate history** or leave voters stuck.
  - **There is therefore no safe rollback once replacements exist.** Roll-forward fixes only, and the release decision (G8) has to accept that.
- **Tests (planned):**
  1. **Direct** injected failure after the drop: levels 1; afterwards level 0, the old index present, the partial absent, a duplicate current pair rejected, the row snapshot unchanged.
  2. **Through the Migrator** (a scratch file): levels 2, the same assertions, not recorded in `migrations`.
  3. Each precondition failure changes nothing.
  4. Direct and Migrator success give identical schemas; `down()` without history restores the original.
  5. `down()` with history refuses; every row unchanged.

### 11.0 S2 design clarifications (S2 design review, 2026-10-12; read-only review; **no decision changed, the freeze holds**)
This sub-section makes section 11 precise where the review found it imprecise. If anything above conflicts with it, **this sub-section wins**. **Status: APPROVED (G3) by the Human PO, 2026-10-12:** the migration design (section 11 + this sub-section) is approved for implementation in S2 and for execution **on the isolated test database only**. A production migration still needs G8 and the section 11.1 runbook. S2 itself still needs explicit authorisation, and is blocked until G4.

- **S2-1, the old rule is a constraint [F].** On PostgreSQL, `$table->unique(['election_id','user_id'])` compiles to `ALTER TABLE … ADD CONSTRAINT voter_slugs_election_id_user_id_unique UNIQUE (…)` (`PostgresGrammar.php:315-321`); its backing index has the same name.
  - **Precondition:** `pg_constraint` holds a constraint of type `u` with that name on `voter_slugs`, over exactly `(election_id, user_id)`. Anything else (missing, a plain index, other columns) → stop, change nothing.
  - **Drop** with `dropUnique` (`DROP CONSTRAINT`, `:519-524`), never `DROP INDEX`.
  - Read "old index" in sections 11 and 11.1 as "old constraint". The `pg_indexes` checks in 11.1 step 3 stay valid (the backing index disappears with the constraint), plus a `pg_constraint` check.
- **S2-2, behaviour tests for the new rule (added to S2's acceptance).** After a successful migration:
  - (a) a soft-deleted row plus a new current row for the same pair is allowed;
  - (b) a second current row is rejected with 23505, naming `voter_slugs_active_election_user_unique`;
  - (c) several soft-deleted rows for one pair are allowed;
  - (d) restoring (un-deleting) an old row while a current row exists is rejected.

  Test 4 is also extended: after `down()` without history, a second row for the pair (soft-deleted or not) is rejected again.
- **S2-3, test-database safety (prerequisite of G5) [F].** `phpunit.xml:4,70` and `tests/bootstrap-test-database.php` pin `DB_DATABASE=nrna_test`.
  - S2's database tests run **only** through a separate bootstrap / configuration that pins the PO-approved isolated database and **aborts before any database refresh** unless `current_database()` equals the approved name.
  - The S2 test files also guard themselves (they skip or fail before `RefreshDatabase` when that identity is not confirmed), so a default `php artisan test` run never migrates `nrna_test`.
  - Writing this bootstrap is part of S2; running it is not allowed until G4.
- **S2-4, drift-safe `down()`.** Preconditions:
  - the partial index exists **and** the old constraint does not;
  - no pair has more than one row, soft-deleted included (unchanged).

  Otherwise refuse with a clear message; never `DROP INDEX IF EXISTS` silently; never delete or rewrite rows.
- **S2-5, lock timeout.** `SET LOCAL lock_timeout` (value to be stated in the G3 request, e.g. a few seconds) inside the migration's own transaction. A timeout fails and rolls back cleanly to the old constraint. Recorded in the evidence.
  - **Reason [F]:** `DROP CONSTRAINT` holds an `ACCESS EXCLUSIVE` lock until commit, and the index is built under it.
- **S2-6:** the story's early code sample is superseded by this section (pointer added to the story).
- **S2-7:** the comment in `VotingIntegrityCharacterizationTest:106` ("the cache lock is effectively per voter") is corrected in S2. The test itself stays valid: both of its rows are current.
- **Not verified (runtime evidence needed):**
  - the real schema matching the migrations;
  - the PostgreSQL version on the isolated database;
  - a named migration class with an overridable failure seam working through the Migrator;
  - transaction levels 1 (direct) and 2 (Migrator).

**S2 acceptance (replaces the S2 row of section 13):** tests 1–5 above (with S2-1, S2-4 and the extended test 4); behaviour tests S2-2 (a)–(d); `PgUniqueViolationShapeTest`; the S2-3 identity check executed before every run; the S1 regression still green. The demo table stays outside S2 (decision 2.1: hard delete and a new row each time).

### 11.1 Migration-first release runbook (R9-5; G3 approved the design only; **this runbook is approved with G8**; not executed)

**[F] What the old code does once the partial index is in place.** The interval in which only the migration has run is **not** neutral:
- the old `createNewSlug` (soft delete, then insert, `VoterSlugService.php:406-438`) **starts succeeding** where it used to fail on the plain unique index. Old-semantics replacement rows can therefore appear **during the interval**;
- the old `start()` keeps rewriting rows in place (`ElectionVotingController.php:166-191`);
- the old binding still resolves soft-deleted rows (`RouteServiceProvider.php:86-90`).

**Old-code compatibility in that interval is not established** **[A]**, so it is an explicit pre-release gate.

1. **Preconditions:** the approved production database identity (the operator records `current_database()`); G3 approved; a backup and snapshot taken per the operations standard; a maintenance window agreed.
2. **Write pause (proposed procedure; production capability unverified [A]):**
   - **Paused:** all slug issuance and replacement (`elections.start`, `election.store`, `voter.start`, `voter.restart`, `vote.direct`, admin recovery, recovery command); all cast submissions (`slug.vote.store`, `vote.store`, `deligatevotes`); all voting step routes; the console vote writers.
   - **How, HTTP:** application-wide maintenance mode (`php artisan down`, 503 for all HTTP requests including API / direct routes). **Scheduled / queued:** stop the cron entry and the Supervisor-managed scheduler and queue worker (deployment per `docs/architecture/c4/06_Deployment.md:7` [F]: single node, Nginx → PHP-FPM, Supervisor / cron), and drain the worker.
   - **How, manual console execution (R11-1).** Maintenance mode and a stopped scheduler / worker **do not prevent anyone from running `php artisan …` by hand** [F: `down` affects HTTP only; commands are auto-registered, `bootstrap/app.php:13`; the seven P4 commands have no environment guard, section 8]. **The repository has no deployment config controlling console access** (no Procfile, Supervisor config, Envoy file or deploy script found [F, read-only search]). So the control is an **unverified operational prerequisite** [A]:
     - **(i) Access freeze:** for the whole window, shell and deploy access to the application host is limited to the named release owner. No other operator session, CI job or cron-like automation may invoke `artisan`.
     - **(ii) Process check:** immediately before the migration **and** immediately before the code deploy, the release owner confirms that no `artisan` process other than their own is running (e.g. a process list filtered for `artisan`).
     - **(iii) Detection backstop:** at pause start, record per election the counts V, C, S (section 10.1), the total `voter_slugs` rows incl. soft-deleted, and the rollback-window count (step 4). Re-run them immediately before the code deploy. **Any difference → abort the deploy and investigate** (forward-fix rules, steps 8–9).
   - **Which console commands matter, and why** [F]:
     - the seven **P4 vote writers** only call `Vote::create` and touch neither `voter_slugs` nor `codes`, so they **cannot close the rollback window**, but they would distort V and the baseline;
     - the **slug-writing commands** can mutate slug history during the window: `voting:clean-expired-slugs` (hard delete), `election:recover-voter` (deactivates slugs), `debug:slug`, `election:reset`.
     - Both groups are covered by (i)–(iii).
   - **Drain:** after `down`, wait until the web server reports no in-flight requests to voting routes (access-log or process check by ops), at least the longest request timeout. Then confirm on the database that no session holds a lock on `voter_slugs` / `codes` (`pg_locks` joined with `pg_stat_activity`, read-only).
   - **Owner and evidence:** the release owner (ops), named in the release ticket, records in the release record:
     - the `down` timestamp;
     - scheduler / worker stopped and drained;
     - the access-freeze confirmation (i);
     - the process checks (ii) with timestamps;
     - the drain observation;
     - the `pg_locks` result;
     - the detection snapshot (iii) at pause start and before deploy.

     **A stopped scheduler is not evidence that manual console execution is impossible**; only (i) plus (ii) plus (iii) together are.
   - **Abort:** if the pause, drain, access freeze, process check or identity check can't be established → **do not run the migration**; bring the application back up; release aborted. **No application-code change** (e.g. the P4 guard) substitutes for a missing operational control.
3. **Run the migration;** verify `pg_indexes`: the old index absent, the partial index present; verify the precondition counts.
4. **Rollback-window check:** `SELECT count(*) FROM (SELECT election_id, user_id FROM voter_slugs GROUP BY 1, 2 HAVING count(*) > 1) x` (over **all** rows, soft-deleted included, which is **exactly** `down()`'s refusal condition) must be **0** before step 5. If it's > 0, the window has already closed (step 9).
5. **Deploy the 274 code** (together with the 273 guard).
6. **Verify:** smoke checks of issuance, reuse, expiry redirect and closure; the read-only baseline counts (section 10.2(4)) recorded **before** voting reopens; end maintenance.
7. **Deploy fails while the check is still 0** (no replacement exists): roll back the code, then the migration (`down()` succeeds because there is no history). The previous state is restored.
8. **Deploy fails after replacements exist:** **no rollback** (migration `down()` refuses; old code may mutate history, see section 11 [A]). **Forward-fix only:** keep or restore maintenance, fix forward, redeploy.
9. **The rollback window is closed** as soon as the step-4 check returns > 0, i.e. at the first replacement row. From then on only forward fixes; no deleting or rewriting of history to reopen it.
10. **Forward-fix path:** a hot-fix through the same gates (tests on the isolated database first); no data rewrite without a separately approved operation.

## 12. Raw voting links in logs (O-7) [F]

### 12.1 Traceability (known sites, confirmed as **log calls** by reading)

| # | Site | Data that can carry the slug / link | In scope under O-7 (B) | Redaction / replacement [D] | Test (planned) |
|---|---|---|---|---|---|
| T1 | `bootstrap/app.php:219` (every `VotingException`) | `'url' => $request->url()` | yes (= 276 item 1) | route name, plus URL with `{vslug}` → `[redacted]` | `RawSlugLoggingTest::test_renderer_logs_no_raw_slug` |
| T2 | `VoteController.php:1951` (post-commit) | `'slug' => $vslug->slug` | yes | `slug_id` | `…::test_successful_cast_logs_no_raw_slug` |
| T3 | `VoteController.php:3404`, `:3408` | `'url' => $redirect->…` (redirect URL containing `v/{slug}/…`) | yes | route name only | `…::test_vote_redirect_logs_no_raw_slug` |
| T4 | `CodeController.php:94-98`, `:262`, `:386`, `:441`, `:563`, `:633` (those that are inside `Log::` calls) | `'slug' => $voterSlug->slug` | yes | `slug_id` | `…::test_code_controller_logs_no_raw_slug` |
| T5 | `CodeController.php:545` (`submitAgreement`) | `'url' => $request->url()` | yes | route name | same |
| T6 | `VoterSlugController.php:54-56`, `:152-154`, `:189`, `:286` | slug | yes (rewritten by 274) | `slug_id` | `…::test_voter_slug_controller_logs_no_raw_slug` |
| T7 | `ElectionController.php:240`, `:264` (and `:265` `route(... ['vslug'=>…])` inside the log) | slug / link | yes | `slug_id`, route name | `…::test_election_controller_logs_no_raw_slug` |
| T8 | `Election/ElectionManagementController.php:425`, `:648` (demo) | `'voter_slug' => $slug->slug` | yes | `slug_id` | `…::test_demo_start_logs_no_raw_slug` |
| T9 | `Demo/DemoCodeController.php:419` | `route('vslug')` | yes | `slug_id` | `…::test_demo_code_logs_no_raw_slug` |
| T10 | `Helpers/ElectionAudit.php:136` (and `:163`, `:188`, `:212` `'voter_slug' => $voterId`) | an audit context key named `voter_slug` | **needs classification:** if `$voter->slug` is the voting link, yes; if it is another identifier, documented and excluded | `slug_id` if it's the link | `…::test_election_audit_helper_logs_no_raw_slug` (if in scope) |
| T11 | `app/Exceptions/Handler.php:43`, `:98` (`path` / `url`) | URL | **[A]** probably dead (`bootstrap/app.php:134` uses `withExceptions`; Handler apparently not registered). **Verify; if dead, documented and excluded; if live, T1 treatment** | — | `…::test_legacy_handler_not_registered` or the T1 treatment |

**Excluded (not logs):** UI props and redirect targets that must carry the slug to build voting links, e.g. `CodeController.php:228, 530`, `route('slug.…', ['vslug' => …])` in redirects, `electionRoutes.php:540`. **No test prohibits them.**

**How the tests prove absence:** `Log::spy()` (or a capturing test log channel) collects every **context array and message**. Each test drives the real code path and asserts that the raw slug string occurs in **no** message and **no** (recursively serialised) context value. The response-body scan applies to **error responses only**.

### 12.2 Final inventory method at implementation time (read-only, before T-tests are written)
- **The candidate set** is every `Log::`, `\Log::`, `logger(` and `->log(` call, plus every audit / helper logger, whose message or context (multi-line arrays included) contains:
  - any of `->slug`, `'slug'`, `'vslug'`, `'voter_slug'`, `route('vslug')`;
  - `url()`, `fullUrl()`, `path()`, `getRequestUri()`, `->getTargetUrl()`, `route(`;
  - string interpolation of a slug variable;
  - `$e->getMessage()` of exceptions that may contain a URL.
- A single-line grep is known to miss multi-line arrays: a first broad pass found **180** candidate lines, most of them UI props / redirects.
- **Each candidate is classified** as log / UI / redirect / other identifier, recorded in the evidence folder, and every log hit is added to section 12.1.
- **Until that classification is done, coverage is unverified, and no system-wide no-raw-slug claim is made.**

**PBDIGIT-276:** T1 is 276 item 1. Tracking is per section 2.2: **276 stays `OPEN`** until the PO records the outcome at 274's closure.

**UI props** (`CodeController.php:228, 530`; `electionRoutes.php:540`) must carry the slug to build links and are **not** logs, so they're excluded (L-11).
- **Under O-7 (B):** `RawSlugLoggingTest::test_renderer_logs_no_raw_slug` and `…::test_successful_cast_logs_no_raw_slug` (a log spy over real context, plus a response-body scan).
- **Under (A):** no system-wide claim.
- **Separate observation (not 274):** `VoteController.php:1927` logs `sha256(vote_id . user_id . election_id)` beside the user, which could be linked by recomputation. Independent anonymity review recommended. **Add to that review:** the dead code `saveVoteTransactionally` sets `user_id` on `Vote` (`VoteController:2126+`, `DemoVoteController:1986-1989`). Its call sites are commented out [F], but it is anonymity-hostile if ever re-enabled.

## 13. Test matrix (all `planned`)

| Slice | Tests | Database | Blocked by |
|---|---|---|---|
| S1 | `VoterSlugLifetimeTest`; `VerifyVoterSlugTest` (delegation, order, misconfiguration); `VoterSlugBindingTest` (no database) | no | O-9, O-10, separate authorisation |
| S2 | section 11 tests 1–5; `PgUniqueViolationShapeTest` | yes | G3, G4 |
| S3a | `VoterSlugIssuerTest` (section 5.1 cases, L-4–L-9, L-15 refusals, lost entitlement, misconfiguration, outer transaction) | yes | G4 |
| S3b | `VoterSlugIssuerConflictTest` (simulation) | yes | G4 |
| S3c | `VoterSlugIssuerRealConcurrencyTest` (G6) | yes | G4 |
| S4 | `VoterSlugIssuanceEntryPointsTest` | yes | G4 |
| S5 | `VoterSlugNoExtensionTest`; cleanup | yes | G4 |
| S6 | duration R1–R3 (and R4–R8 `DurationReaderRegressionTest` under O-10 B) | mixed | O-10, G4 |
| S7 | section 7.2 database tests | yes | O-5, G4 |
| S8 | `VoterSlugLifecycleFlowTest` | yes | G4 |
| S9 | `CastSuccessContractTest` (G7: step-5 and receipt failures, L-17a, L-17b; mechanics per section 9.1, which is **unverified until first executed**); `CountInvariantTest` and `CountInvariantRealConcurrencyTest` (section 10.2); the O-13 residual-state test; L-18 `test_confirmation_email_vs_commit_failure` (O-15); section 10.3; section 10.4 scan; section 12.1 T1–T11 (O-7 B), after the section 12.2 classification; `ConsoleVoteWriterGuardTest` (only if O-6 A); regression: `VerifyVoterSlugRequestPathTest`, `ConstitutionalVotingProtectionTest`, `ElectionShowControllerTest`, `ElectionVotingControllerAuditTest`, `VoterSlugServiceTest`, `VoterSlugExpirationTest`, `RouteBindingTest`, `MiddlewareChainTest`, `VotingEntitlementSinglePointTest`, `VotingEntitlementUnificationTest` | mostly yes | G4, O-6 / O-7 |

## 14. Scope, assumptions, stop conditions

**In scope:**
- lifetime, issuer, outcomes, exceptions, the partial-index migration;
- the binding; the guard order; `ValidateVoterSlugWindow`; the renderer cases;
- entry points and recovery (non-mutating); `VoterSlugService` delegation and removals; the `CodeController` slug update; the cleanup command; the test routes;
- the model `expires_at` hooks;
- tests; docs at closure.
- **Conditional:** R4–R8 and the config cast (O-10 B); log redaction (O-7 B); P2 / P3 / P4 (only O-6 A).

**Out of scope:**
- the rest of PBDIGIT-275 … 278 (incl. the P4 guard and P3 participation under O-6 B; the `DemoVoteController:1471` anomaly);
- `UNPARSABLE_KNOWN`;
- demo pair-uniqueness;
- the retention / erasure policy;
- foreign-key protection (O-12 b);
- `retrieved` hook rework;
- historical reconciliation;
- the `receipt_hash` review.

**Unverified:**
- [A1] no framework-managed transaction;
- [A3] R4–R8 valid-value semantics under O-10 (B);
- [A5] two-process tests are possible;
- [H] section 9 cast behaviour;
- [H] P3 participation;
- [H] the issuer-vs-cast interleaving;
- [H] no other pre-V-3 redirect;
- the driver shape.

**Stop if:**
- any gate is unmet;
- a plan-prerequisite decision is open;
- O-5 is open at S7, or O-9 / O-10 at S1 / S6;
- an identity check fails;
- **L-16 or L-17 is violated by the existing code**;
- the section 9.1 injection mechanics don't behave as designed on first execution (G7 stays blocked);
- the old-code compatibility gate (section 11.1) isn't satisfied, or the rollback-window check is > 0 before deploying the code;
- the console control in section 11.1 step 2 (access freeze, process checks, detection snapshot) can't be established or evidenced, or the detection snapshot changes during the window (F28);
- the release order G8 can't be guaranteed;
- any assumption or hypothesis fails;
- `VotingEntitlementSinglePointTest` would need changing;
- a change is needed outside scope;
- an unrelated regression appears.

## 15. Acceptance and evidence
- **Acceptance:**
  - every L-invariant's named test **executed and passed**;
  - G6 and G7 executed and passed (G7 incl. L-17);
  - G8 accepted in the release plan, including the section 11.1 console control **evidenced by the release owner** (F28; an operations gate, not satisfied by this plan);
  - S9 passed, or each failure explained and in scope.
- **Not completable** while G3–G8, a plan-prerequisite decision, or the O-6 scope is open.
- **L-14 is claimed only within the chosen O-6 scope; never absolute equality without the O-6b (ii) reconciliation.**
- **Evidence:**
  - outputs, exit codes, `SHA256SUMS`, a credential check, the `current_database()` proof per run;
  - index / row snapshots / transaction levels;
  - the driver-shape record;
  - the baseline counts;
  - the section 9 G7 evidence set;
  - before / after diffs of changed existing tests, with the decision each follows.
- **Commits:** none without separate authorisation. No push, merge or deploy.

## 16. Repository hygiene (separate from this plan)
- **The `developer_guide/http/` reminder:** an area-mapping false positive.
- **The session log:** the plan revisions aren't logged yet; they'll be added at closure.
- **"Many files modified":** other sessions' work, plus this session's uncommitted Adjudication docs.

None of these widens 274's scope.

## 17. Finding register: finite, closed list (Principal Reviews R4 … R10 plus the independent subagent review)

Status: **R** = resolved at plan level (it still needs execution evidence, as listed) · **D** = a Human PO / ARB decision (section 2.2) · **E** = an execution gate (runtime evidence; not a plan defect).

| # | Finding (source) | Acceptance criterion at plan level | Status | Where |
|---|---|---|---|---|
| F1 | lifetime rule, single source (R1–R3) | one `VoterSlugLifetime`; the boundary is `>=`; the cap is on `created_at` | R | sections 3, 5, 6 |
| F2 | restart = new row, history kept (PO) | issuer `decide()` steps; partial index | R (+E: S2 / S3) | sections 5, 11 |
| F3 | entitlement inside the transaction; retirement commits (R3-1) | outcome object; raised after commit | R (+E: S3a) | section 5 |
| F4 | migration atomicity, two invocation modes (R3-2 / R5-2) | own transaction plus savepoint levels; failure-after-drop tests | R (+E: S2, G3) | section 11 |
| F5 | conflict classification by SQLSTATE and constraint (R3-3 / R4-3) | classification table; unknown → conflict, cause kept | R (+E: driver-shape test) | section 5 |
| F6 | binding writes before ownership (R5-1 / R6-3) | `withoutEvents` around only the lookup; only the `'tenant'` scope removed | R (+E: S1 / S7) | section 7 |
| F7 | expiry vs closure separated (R2) | two exceptions; owner-only redirect | R (+E: S7) | section 7 |
| F8 | invalid-duration contract (R4-4 / R5-4 / R6-4 / R7-6) | input table per option; reader matrix; gate split | D (O-9, O-10) | section 6 |
| F9 | count invariant scope and baseline (R6-1 / R7-1 / R7-2) | the L-14 wording per option; measurement boundary | D (O-6, O-6b) | sections 3, 10.2 |
| F10 | `status` overwritten by the hook → count by `has_voted` (R6) | L-14 / L-15 use `has_voted` | R | section 10 |
| F11 | cast atomicity and swallowed errors (R6-2 / R7-4) | L-16 contract; trace | E (G7) | section 9 |
| F12 | false failure after commit (subagent SF-5 / R9-3) | L-17a / L-17b deterministic tests | E (G7) | section 9.2 |
| F13 | failure-injection mechanics (R9-2) | C1 / C2, triggers, cleanup, privileges | R (+E: first execution) | section 9.1 |
| F14 | issuer vs cast race (subagent B-1 / R9-1 / R10-2) | lock on the `codes` row; residual as O-13; options consistent with the algorithm | D (O-13) | sections 2.2, 5 |
| F15 | refusals write nothing (subagent B-2 / R7-5) | events off during issuer reads; caller lines removed | R (+E: section 10.3) | sections 5, 10.3 |
| F16 | retention vs cascades (R7-3) | one formulation; limited evidence stated | D (O-12) | section 10.4 |
| F17 | other vote-writing paths (R6-1 / R7-1, subagent SF-1) | P1–P4 classified; P3 statically non-functional | D (O-6 / O-8) | section 8 |
| F18 | raw-slug logging scope (R6-6 / R9-4 / R10-1) | O-7 = section 12 inventory; T1–T11; classification method | D (O-7) (+E: section 12.2) | sections 2.2, 12 |
| F19 | PBDIGIT-276 overlap (R9-4) | 276 stays open; PO tracking action | D (O-7) | section 2.2 |
| F20 | step-1 closure caveats (subagent SF-8 / R9-6) | no-election unreachable [F]; demo bypass as O-14 | D (O-14) (+E: section 7.2) | section 7.1 |
| F21 | release order and pause (subagent SF-4 / R9-5 / R10-3) | G8; runbook steps 1–10; pause / drain / abort | R (+ops capability [A]) | section 11.1 |
| F22 | no safe rollback after replacements (subagent SF-3) | the rollback-window rule | R | section 11 |
| F23 | pre-commit "confirmed" email (R10-4) | O-15 options; L-18 characterisation | D (O-15) (+E) | sections 2.2, 3 |
| F24 | re-vote / voted-slug changes (R6-5) | L-15; `AlreadyVoted` | D (O-11) | sections 3, 10.3 |
| F25 | non-owner response (R4-4) | O-5 options | D (O-5) | section 2.2 |
| F26 | real concurrency proof (all reviews) | G6 two-process test | E (G6) | section 5.1 |
| F27 | the `receipt_hash` / dead `user_id` anonymity observation | out of scope; independent review recommended | out of scope (recorded) | section 12 note |
| F28 | manual console execution during the migration-first release window (R11-1; reopened under the freeze rule as a missed release-safety gap) | evidence that manual **and** scheduled execution of the P4 vote writers **and** the slug-writing commands (`voting:clean-expired-slugs`, `election:recover-voter`, `debug:slug`, `election:reset`) is prevented, or detected, during the window, incl. already-running processes: access freeze (i), process checks (ii), detection snapshot (iii), recorded by the release owner; otherwise abort before the migration | **E (operations gate)**: the control and its evidence are specified; availability and effectiveness are **unverified operational prerequisites** (no repository deployment config) | section 11.1 step 2, sections 14, 15 |

**Freeze rule.** The plan is **frozen for decisions** after rev 10.
- What remains is: decisions **D** (to be taken by the Human PO / ARB on the section 2.2 register) and execution gates **E** (to be satisfied only by authorised runs on the isolated database).
- **The plan is reopened only** if new material evidence or a previously missed **blocking** defect appears. Runtime hypotheses don't reopen it; they are stop conditions (section 14).

## 18. Progress (implementation)
- **2026-10-11, S1 (authorised by the Human PO; database-free; committed `a78d7c2f2`, restored in `4d83dffc0`).** Evidence: `docs/publicdigit/election/evidence/2026-10-11-voter-slug-lifecycle-s1/`.
  - **S0:** baseline 31 tests passed.
  - **Lifetime:** RED 32 errors (class missing; a deviation: errors, not assertion failures) → GREEN 32 tests.
  - **Guard:** RED 3 assertion failures → GREEN 34 tests.
  - **Route lookup:** RED 6 errors (class missing) → GREEN 6 tests.
  - **Regression:** 72 tests, 424 assertions, exit 0.
  - **Status:** L-1, L-2 and L-3 (lifetime level) and the database-free parts of L-13 are **executed and passed**. Everything else stays `planned`.
  - **Decision-driven test change:** status-expired → Expired.
- **Recorded for later slices:**
  - `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation` (database, not run) still expects 410, and the `VerifyVoterSlugRequestPathTest` comment is outdated. Both are updated in S7.
  - New O-7 log site: `RouteServiceProvider` `slug_prefix` (the first 10 characters of the link). It goes into the section 12.2 classification.
- **2026-10-11, S1 closeout (read-only review plus status reconciliation; no database, no migration).**
  - **Evidence re-checked:** `SHA256SUMS` verifies; `07-regression-s1` shows 72 tests / 424 assertions, exit 0; `credential-check.txt` 0 matches. All three S1 test files are database-free (no `RefreshDatabase`; `DB::listen` only to prove that no query runs).
  - **Code re-reviewed against L-1 … L-3, L-13:** the `>=` boundary; missing timestamps count as expired; strict config; the misconfiguration log has reason and type only; order V-5a → V-4 → V-5b; only `'tenant'` removed, SoftDeletes kept; `withoutEvents` restores in `finally` (`HasEvents.php:453-459`).
  - **Repository incident:** S1 was committed as `a78d7c2f2`, then the unrelated commit `118193320` (built from the shared index) removed the S1 files from HEAD. The working tree is byte-identical to `a78d7c2f2`. Cause: a commit through a temporary index while the shared index lagged behind HEAD.
  - **Recovery (2026-10-12, authorised by the Human PO):** commit `4d83dffc0` restores exactly the eight S1 files, byte-identical to `a78d7c2f2`, through the shared index with `git commit --only`. It doesn't revert `118193320`'s other changes; no history rewrite, no push, no database operation. The recovery restores the files; it is not runtime evidence.
  - **Next:** S2 needs G3 and G4 (section 13); it is not database-free.
