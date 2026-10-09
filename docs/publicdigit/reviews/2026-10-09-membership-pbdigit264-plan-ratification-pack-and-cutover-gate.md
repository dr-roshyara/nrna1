# Membership: PBDIGIT-264 Remediation Plan, Slice 2 Ratification Pack, Cutover Gate, Roadmap

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Instructions:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2103_publicdigit_plan.md`, `…/20261009_2104_implementation.md` (Principal Reviewer).
**Builds on:** `2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md` (commit `0969e05a3`; called **"the closure report"** below).
**Companion:** `2026-10-09-participation-semantic-contract-draft.md`.
**Status:** PLAN + DECISION REQUEST. **No code changed. Nothing is ratified or accepted by this document.**

## 0. Disagreements and discrepancies (stated first)

| # | Instruction | Position |
|---|---|---|
| Y-1 | 2104 §9 deliverables 1 and 3 (the acceptance recommendation; the D-1 … D-6 pack) | **These exist in the closure report, written one hour earlier.** Re-deriving them would create a second, drifting copy. §1 and §3 below are **deltas plus a compact ratification table** that points to the closure report §2 / §4 for the evidence. |
| Y-2 | 2103 P0: "add backlog index entries for 258–262", "remove duplicate Slice 1a guide entries and duplicated checklist/status lines", "add the missing database developer guidance" | **Not supported by the repository.** The index rows exist (`0b2685c38`). `developer_guide/membership/00_index.md` lists 01 / 02 / 03 once each. The database guide exists (`03_slice1b_postgres_adapter.md`). The 258 / 259 / 260 stories each carry one status line. The source was probably an older report snapshot. **Nothing to do.** |
| Y-3 | 2103 P0 "document and fix" test isolation; 2104 §5 "Resolve PBDIGIT-263" | **Documented, not fixed.** `PBDIGIT-263` is unauthorised, and the fix touches every database test. It needs its own story and measurement. |
| Y-4 | 2104 §6 "Add or retain an explicit regression test" that Election-Only never consults the ledger | **Retained, not added.** Existing pins prove Election-Only works with **no `members` row**: `ElectionOnlyEntitlementPinTest` test A (`:107-140`) and `ElectionOnlyModeTest:142,295,379`. No production code outside `Infrastructure/Organisational` references `org_memberships` (inventory, §4). A test that the ledger is not consulted becomes meaningful only once something *could* consult it, so it is a **Slice 2 entry criterion** (T-EO, §6). Adding code now would breach §9 "stop". |
| Y-5 | 2104 §7: a Participation Semantic Contract draft | **Drafted, but conditional.** The baseline research put the contract *after* RD-1 … RD-4 ratification, so every dependent clause is marked ⟨RD-n⟩. |
| Y-6 | 2104 §3: "the expected failure before the fix" for every PBDIGIT-264 item | **F-2 has no expected failure.** It is a coverage gap on code believed correct. Its tests should pass on first run, and their value is shown by **mutation sensitivity**. Calling them RED would repeat the DV-2 mislabelling. |
| Y-7 | 2104 §3 F-4: "if consistent with the approved contract" | The approved schema stores copies of `fact_id` / `seq` / `recorded_at` in the commands table. The **minimal** remedy (an FK) is consistent with it. The **cleaner** remedy (remove the copies, read them by join) changes the approved schema, so it is offered as an option for the Principal Reviewer, not assumed. |
| Y-8 | The closure report's "23 files read `members`" (from EP-01 principal review §16) | **An undercount.** The inventory finds **≈ 36 reader files** (25 outside the Membership context) and **≈ 13 writer paths**. Corrected in §4. |

## 1. Slice 1b: recommendation (delta only)

**Unchanged: ACCEPT WITH CONDITIONS recommended** (closure report §1–§2). The classification below follows 2104 §2.

| Class | Items |
|---|---|
| **Demonstrated** | Atomic append (one transaction: key, CAS, fact, key row). One fact per (tenant, key). Identical concurrent retries get the original outcome (real-process races, 8 rounds × 3 scenarios, plus the independent analysis). Tenant scoping in every query and FK. Facts as evidence, with Resolution as truth (`load` never reads `version`). Dates round-trip within 0001–9999. Architecture direction tests. |
| **Only under assumptions** | Answers to retries and conflicts assume **READ COMMITTED** (the server default; not enforced) and an autocommitted `create`. Regression shows "no new failure **under the same dirty working tree**", not against a clean checkout. |
| **Test gaps** | Identical **Admit** retry race; cross-Member key catch path (F-2). Contract clause "kernel unchanged" is not enforced by T13. |
| **Defects** | **F-1** far date makes a Member unloadable. |
| **Deferred risks** | F-3 adapter parity; F-4 commands→facts FK; F-5 constraint detection; `PBDIGIT-263` test isolation; O-3 production engine (`PBDIGIT-261`); O-4 migration not rolled out. |
| **Business decisions** | none inside 1b. **F-1 raises one:** the supported valid-time range (folded into D-5). |
| **Authorised 1a change** | DV-1 (`DecideAndAppend`), PO "Allow minimal fix", recorded in the 1b contract amendment; **confirmation requested.** |
| **Deployment readiness** | **Not ready for live use**, and not intended to be. Migration not run on dev or prod; production engine unverified; no live entry point until the cutover gate (§4). |

## 2. PBDIGIT-264: TDD remediation plan (for authorisation; not started)

**Order:**
1. F-1 (the only defect);
2. F-5 (it changes the catch path that F-2 then covers);
3. F-3 (contract text plus the test double);
4. F-4 (schema);
5. F-2 (coverage, run against the final code).

One commit per RED and per GREEN, subject `(PBDIGIT-264)`; a dev-guide 03 update; a short review; then STOP.

**Never touched:**
- the kernel (`Domain/Organisational`);
- the port signatures;
- the handlers;
- `DecideAndAppend`.

**Rollback:** `git revert` per commit. The F-4 migration has a `down()`.

### F-1: far date (DEFECT)

| | |
|---|---|
| Failing scenario | Admit with `effectiveAt` (or `initialTermUntil`, or an End's `effectiveAt`) at ordinal 2932897 → `Appended`; every later `load()` throws `UnexpectedValueException: Not a calendar date: '10000-01-01'` |
| Regression test | `PostgresMemberRepositoryInvariantsTest`: for each of the three date fields, an out-of-range value → the adapter raises a dedicated infrastructure exception (`UnsupportedCalendarDate`, a `RuntimeException`); afterwards version 0, no fact, no key row, and the Member still loads. Also the lower bound: ordinal −719163 (0000-12-31) |
| Expected failure before the fix | "Failed asserting that exception `UnsupportedCalendarDate` is thrown" (the append returns `Appended`) |
| Smallest change | `DateOrdinal::toDate` checks −719162 ≤ ordinal ≤ 2932896 (0001-01-01 … 9999-12-31, the range in which `Y-m-d` round-trips exactly) and throws otherwise. In `append`, encode the fact **before** opening the transaction, so nothing is attempted |
| Shared contract tests | **none.** The range is a property of this representation, not of the port. The in-memory double keeps an unbounded range, and that **difference is deliberate and documented**. A domain-wide bound would be a kernel change: decision D-5(c) |
| Migration / rollback | none |
| Acceptance | the new tests are green; T1–T13 still green; mutation check: remove the guard → the test fails |

### F-5: constraint detection and the isolation assumption

| | |
|---|---|
| Failing scenario | detection uses `str_contains($e->getMessage(), 'org_membership_commands_pkey')`. Laravel's message includes the SQL **with bindings**, and PostgreSQL's DETAIL includes **key values**. A violation of a *different* constraint whose bound values contain that text is classified as a key collision. Today that falls back to a rethrow, so it is safe but wrong by construction |
| Regression test | a unit test of a small extracted classifier, `PgUniqueViolation::isConstraint(UniqueConstraintViolationException, string $name)`. Inputs: a violation of `org_membership_facts_…_unique` whose DETAIL contains the string `org_membership_commands_pkey` (expected false); a real commands-PK violation (expected true). A second test: with the connection's default set to REPEATABLE READ, the identical-retry and cross-Member races still return contract outcomes |
| Expected failure before the fix | the classifier does not exist, so the call fails. Inlining the current `str_contains` logic answers true for the decoy |
| Smallest change | classify by SQLSTATE `23505` (`errorInfo[0]`) **and** the constraint name parsed from the **first line** of `errorInfo[2]` (`violates unique constraint "(…)"`); the DETAIL line is never read. **Enforce, don't just document:** issue `SET TRANSACTION ISOLATION LEVEL READ COMMITTED` as the first statement of the append transaction |
| Shared contract tests | none (adapter-internal) |
| Migration / rollback | none |
| Acceptance | classifier tests green; the REPEATABLE-READ variant green; dev guide 03 states the enforced level |

### F-3: adapter parity (in-memory vs PostgreSQL)

| | |
|---|---|
| Failing scenarios | (a) `create(t, id, holderB)` where `id` already belongs to holder A: the double **overwrites** A's Member; PostgreSQL raises a PK violation. (b) `append` for an unknown MemberId with an already-used key: the double answers `MemberNotFound`, PostgreSQL answers from the key (`AlreadyApplied` / `IdempotencyKeyConflict`) |
| Contract clarification (Principal Reviewer) | (a) an id collision for another holder is an **infrastructure failure** (an exception, no state change), not a port outcome. Reason: ids are caller-generated (UUIDs), so a collision is a caller defect, and adding an outcome would change the port. (b) **The key check precedes all other checks** (a key identifies a command, IDEM-1 / 3). Recorded in the Slice 1a contract text; **the port file stays unchanged** |
| Regression tests | two tests in the shared `MemberRepositoryContract` trait, so they run on both adapters: (a) expects a `\Throwable`; A's Member is unchanged and holder B is unmapped. (b) expects `AlreadyApplied` |
| Expected failure before the fix | the in-memory run of (a) fails (no exception; A overwritten) and of (b) fails (`MemberNotFound`). The PostgreSQL runs pass |
| Smallest change | `tests/Doubles/InMemoryMemberRepository.php` only: throw on an id collision; check the key before membership in `append` |
| Migration / rollback | none |
| Acceptance | the shared suite is green on both adapters; the property run (T11, in-memory and PostgreSQL) is unchanged |

### F-4: the commands → facts reference

| | |
|---|---|
| Failing scenario | a raw insert into `org_membership_commands` naming `seq` 1 for a Member with no facts is accepted (the independent reviewer's probe) |
| Options | **F-4a (minimal, contract-consistent):** composite FK `(organisation_id, membership_id, seq)` → `org_membership_facts` PK. **F-4b (normalising):** drop the copied `fact_id` / `recorded_at` from the commands table and read them by join through that FK. This removes the inconsistency class instead of guarding it, but changes the approved schema |
| Recommendation | **F-4a now.** F-4b only if the Principal Reviewer amends the 1b contract |
| Regression test | invariants test: a raw inconsistent key row → `QueryException` (FK) |
| Expected failure before the fix | the insert succeeds |
| Smallest change | an **additive** PostgreSQL-only migration `…_add_fact_reference_to_org_membership_commands`, with `down()` dropping the FK. The existing 1b migration is not edited (it may already be applied in some environment) |
| Migration implication | the table is empty everywhere except `nrna_test`. If it has been rolled out anywhere, existing rows are consistent by construction (one transaction), so `ADD CONSTRAINT` validates |
| Acceptance | test green; T7 (rollback) and T12 still green |

### F-2: race coverage (no RED; Y-6)

| | |
|---|---|
| Scenarios | (a) 4 processes submit the **same** `AdmitMember` (same key, MemberId, FactId) for a new person concurrently → exactly 1 Member, 1 fact, and all 4 receive the identical `Appended` (memberId, factId, seq, recordedAt). (b) 4 processes, 4 different persons (each with an existing Member), the **same** key with **different** fingerprints → exactly 1 fact in the tenant; 3 × `IdempotencyKeyConflict`; the losers' versions stay 0; no error |
| Tests | two methods in `PostgresConcurrencyRaceTest` (group `membership-race`), reusing `race()`, 8 rounds |
| Expected before | **pass** (coverage) |
| Sensitivity (required evidence) | (a) remove the key consultation after a domain refusal in `DecideAndAppend` (temporarily, in a scratch run) → the losers receive `duplicate_fact_id`. (b) remove the catch in `append` → the losers receive errors. Both then reverted |
| Acceptance | 3 consecutive green runs of the group; both mutations detected |

## 3. Slice 2 ratification pack (compact; evidence in closure report §4)

| ID | Decision | Recommendation | Ratifier | Blocks |
|---|---|---|---|---|
| D-1 | Admission basis (BD-CONSENT / LQ-1) | every *new* Admit carries exactly one basis: a **declaration reference** (application id, signed form, accepted invitation; a reference, not the document), a **statutory rule** reference, or, **for migration only**, **attested history**. Admin add without a reference is refused | PO + legal advice (LQ-1) | Slice 2 Admit |
| D-2 | Pending member (BD-REC0) | **no** pending Member. The application stays outside the aggregate; approval records the Admit with `declarationRef` = the application. The DDD `MemberStatus` PENDING / APPROVED is unratified legacy and is not followed | PO | Slice 2 Admit |
| D-3 | End kinds (BD-3 / LQ-3) | closed act list: `resignation`, `exclusion`, `death`; `legacy` for attested migration only. Ground ≠ act (non-payment is a ground for exclusion). Term lapse is **not an End** (it is derived). Organisation dissolution is an organisation-level use case, not a per-member End | PO (list); legal (procedure) | Slice 2 End (an interim list suffices) |
| D-4 | Authority (DS-4) | **conditional option only:** an interim adapter that mirrors today's rule (owner / admin, `MembershipPolicy`) and records the role and source as `AuthorityEvidence`, labelled interim. No Membership-local policy | Governance owner through the PO | Slice 2 |
| D-5 | Calendar boundary and date range | (a) "today" is a **UI default** computed in `organisations.timezone` (to be made readable); commands carry explicit dates; engineering decides. (b) backdating allowed (the kernel already refuses a backdated re-admit into a live lifetime); **no future-dated** Admit / End in Slice 2. (c) **new, from F-1:** the supported valid-time range. Recommended: 0001-01-01 … 9999-12-31 enforced by the adapter now; a narrower *business* range is a PO choice validated at the Slice 2 boundary, never silently in the kernel | (a) engineering · (b), (c) PO | (b) Slice 2 |
| D-6 | Coexistence (R10) | per-organisation authoritative store; nothing live before the cutover gate (§4) | already designed (EP-01 review §16); the PO approves the slice | Slice 4 (HTTP / UI) |

**None of D-1 … D-6 is adopted.** Until each is ratified, the corresponding Slice 2 behaviour is not implementable.

## 4. Legacy coexistence: cutover gate checklist (D-6 / R10)

**Evidence (inventory 2026-10-09, read-only; spot-checked by me):**
- Legacy models bound to `members`: `App\Models\Member` and `Contexts\Membership\Infrastructure\Models\MemberContextModel`. A third, `Contexts\Membership\Domain\Models\Member` on the tenant connection, is **unrouted**.
- **≈ 13 writer paths:**
  - 9 direct Eloquent writers;
  - the DDD `EloquentMemberRepository` with 8 callers;
  - **2 mass updates that bypass model events:** `ExpireMemberships.php:15-19` (`withoutGlobalScopes`, all organisations) and `MarkOverdueMembers.php:50-55`;
  - a relation-builder soft delete in `OrganisationUserImportService.php:375`.
- **≈ 36 reader files**, 25 outside the Membership context: Election eligibility, newsletter (15 queries), dashboards, settings guard, roles UI, committee search, finance controllers, participant export.

**Surprises relevant to cutover:**
- Two fee vocabularies: the DDD path writes `fee_state` and never `fees_status`, while Election eligibility reads `fees_status`.
- The import regenerates `membership_number` on every run (`OrganisationUserImportService.php:347`).
- The settings guard filters on `fees_status='pending'`, a value no writer sets.
- The bulk and single-user Full-mode eligibility predicates differ (`PBDIGIT-265`).

| # | Gate condition | Evidence required | Owner |
|---|---|---|---|
| C-1 | **Source-of-truth rule per organisation:** a flag; when set, the ledger is the *only* writer of membership facts for that organisation | flag defined; tested both ways | Membership |
| C-2 | **Column ownership split:** `members` columns are classified as membership-derived (status, joined / ended, expiry, type), **Finance-owned** (`fees_status`, `fee_state`), or other (newsletter tokens, personal info). The projection writes only the first class | a column map signed off; no projection test touches Finance columns | Membership + Finance |
| C-3 | **Every writer of membership-derived columns** is routed to ledger commands, disabled, or skipped for cut-over organisations: the 9 direct writers, 8 DDD callers, the import, `ExpireMemberships` (in the target model expiry is derived term lapse, so the job must skip cut-over organisations) | a per-path test, or a guard hit | Membership |
| C-4 | **Database-level write guard** on `members` for cut-over organisations (e.g. a trigger rejecting changes to membership-derived columns unless the projector's session marker is set). This is the only guard that catches the mass updates and the relation delete | trigger test including a query-builder mass update | Membership (infrastructure) |
| C-5 | **Reverse projection** ledger → `members` (single writer; derived; rebuildable), with a defined mapping from resolved predicates to the legacy status vocabulary (active / expired / suspended / ended / inactive) | rebuild-equals-incremental property test | Membership |
| C-6 | **Reader predicate inventory:** each of the ≈ 36 readers mapped to the predicate it actually needs; Election's Full-mode predicate settled first (`PBDIGIT-265`) | the map; Election sign-off | Membership + Election |
| C-7 | **Migration per organisation:** report-first dry run; attested facts; no invented dates; no automatic trimming of overlaps; an exception report; **parity criteria** (legacy predicate = projected predicate for every member and month, except listed exceptions) | dry-run report; parity = 100 % or signed-off exceptions | Membership + PO |
| C-8 | **Write freeze** during an organisation's migration window, and a **rehearsed rollback** (flip the flag; legacy rows are current through C-5) | rehearsal log | Ops + PO |
| C-9 | **Election-Only untouched:** the voter source never reads the ledger (T-EO); Election-Only organisations are never cut over | test | Election |
| C-10 | **Environment:** migration rolled out to dev, then prod (O-4); production engine verified (`PBDIGIT-261`) | operator record | Ops |

## 5. Participation research (summary; the draft is the companion document)

Results that bear on Membership (bounded, small-scope; counts of enumerated histories, not frequencies):
- **G-2:** charges *written* by a legacy-shaped job diverge from the corrected truth in **140 / 222** histories (85 over, 55 under); credit arises in **70 / 222**. ⇒ Finance: derived target, append-only ledger, adjusting postings. The legacy `endMembership` fee waiver is a cross-context cascade (already DELETE).
- **G-3:** an event-maintained access list diverges from derived access in **180 / 432** histories. ⇒ Access is a rebuildable projection.
- **G-4:** lapse vs suspend (BQ-P3) differ only with **re-entry** (110 / 540; 0 / 432 without). The ratified re-entry semantics therefore make BQ-P3 a real decision.
- **G-1:** duty ≠ authority. Delegation moves authority, not duty; duties can outlive the tenure (BQ-P8).
- **Nothing in G-1 … G-5 requires a change to the Member aggregate.** IP-7: no cross-context writes.

## 6. Roadmap (dependencies and blockers)

| Step | Content | Blocked by |
|---|---|---|
| 1 | **PBDIGIT-264** (§2) | PO authorisation |
| 2 | **Slice 2: command wiring, no live entry point.** Basis guard (D-1), End-kind list (D-3), date-range validation (D-5 b / c), interim authority adapter (D-4), container binding, **T-EO** (Election-Only never consults the ledger), handler tests with the real repository | D-1 … D-5 ratified; Slice 2 contract approved; step 1 accepted |
| 3 | **Slice 3: cutover machinery** (C-1 … C-5, C-8) on a test organisation | step 2; C-2 column map (Finance); `PBDIGIT-265` answered |
| 4 | **Migration dry runs and parity** (C-6, C-7) | step 3; O-4 rollout to dev |
| 5 | **Slice 4: HTTP / UI.** Admit on application approval, End with kind, org-local "today" | steps 3 and 4 for the organisation concerned |
| 6 | Lifecycle slices (renew, type, suspend / restore, number, correct / annul / void), one reviewed slice each | step 2; the respective BD decisions |
| 7 | Governance participation (contract → slices) | RD-1 … RD-4, BQ-P1 … P3, P8 |
| 8 | Finance (charge target + ledger), Audit (fact-stream consumer), legacy retirement | contracts per context |
| ∥ | `PBDIGIT-261`, `262`, `263`, `265` | each its own authorisation |

## 7. Evidence ledger

| Kind | Items |
|---|---|
| **Adopted / ratified** | the Membership architecture (2026-10-08 act); EP-Q6 = T1; DB-1 = PostgreSQL only; EM-EO-001 … 004; D-1 = INV-FID (instruction `202061009_0812…`). The Slice 0 / 1a semantics are referred to as "accepted" by instruction `20261009_0922…` (line 262), but **no formal acceptance act** for 258 / 259 is recorded |
| **PO decisions recorded only in session text** | DV-1 "Allow minimal fix" (confirmation requested) |
| **Recommendations (not adopted)** | ACCEPT WITH CONDITIONS for 1b; D-1 … D-6; F-4a; RD-1 … RD-4 answers; IP-1 … IP-7 |
| **Implementation facts (tested)** | Slices 0 / 1a / 1b as reviewed; independent reruns: 34 unit, 12 architecture, 27 feature tests (race group included) OK; F-1 reproduced |
| **Assumptions** | READ COMMITTED; caller-generated UUID MemberIds; bounded model domains (§5) |
| **Open questions** | BD / LQ / DS items in D-1 … D-6; BQ-P1 … P11; `PBDIGIT-265` intent |
| **Not verified** | production engine; any rollout; runtime impact of `PBDIGIT-265`; clean-checkout regression |

## 8. Decisions required, then STOP

1. **Principal Reviewer:** accept Slice 1b (or not), with conditions; confirm DV-1.
2. **PO:** authorise `PBDIGIT-264` as planned in §2. Choose F-4a or F-4b. Approve the F-3 contract clarification.
3. **PO (+ legal, + Governance owner):** ratify D-1, D-2, D-3, D-4 and D-5 (b) / (c).
4. **Principal Reviewer:** comment on the Participation draft and answer RD-1 … RD-4 when ready.
5. **Election owner:** `PBDIGIT-265`.

**STOP.** Nothing is implemented until (2).
