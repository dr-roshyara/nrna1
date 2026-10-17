# Membership TDD Backlog Story Drafts

**Author:** Session 1. **Status:** DRAFTS, PROPOSED — NOT AUTHORISED. No backlog file was created and no PBDIGIT ID was allocated.
**Why drafts:** the backlog convention records "creation approved by the PO" for new items (e.g. `PBDIGIT-303`, status line), and the index `docs/publicdigit/backlog/README.md` is shared. The highest existing ID is `PBDIGIT-304`. On approval, each new draft receives the next free ID and an index row; drafts that extend an existing story are applied to that story instead.
**Sources:** requirements, open questions and dependencies in `20261016_2257_membership_architecture_verification_rev1.md` (BR-*, SEC-R*, OQ-*, §7); diagrams in `20261016_2257_membership_architecture_diagrams.md`.
**Commission note:** the commission text was truncated in this phase; increments below follow the dependency plan.

## How every story is executed (common Definition of Done)

1. Definition of Ready: story ID, approved plan, the listed decisions recorded, written authorisation naming scope and environment.
2. RED first: a failing test recorded (output stored); assertion failures preferred over errors; deviations disclosed.
3. GREEN: minimal change; REFACTOR: behaviour-preserving.
4. Verification on a **committed** tree: scoped Membership regression (the A1 scope) + C-1 differential (15,530 decisions, empty mismatch list) + G6 golden hash; PostgreSQL work **only** on the isolated, identity-verified database.
5. Evidence folder: commands, commit, PHP / PHPUnit / php-parser versions, exit codes, SHA-256 sums.
6. Developer guide under `developer_guide/membership/` (one file per step, index updated).
7. Review: Session 1 conformance review → Principal Reviewer → Human PO / ARB decision. **Engineering never accepts its own work.**
8. Stop and report on: a failing regression, a database identity mismatch, a needed rule that is not decided, any change outside scope, any other session's uncommitted change in the paths touched.

---

## Increment 0 — Architecture and requirements corrections (decision tasks, no code)

These are not developer stories; they are the decisions that unblock later increments (owners from rev 1 §6).

| Task | Decision | Owner | Unblocks |
|---|---|---|---|
| D0-1 | adopt names (COR / CUT / GOV-END) and the corrected order (rev 1 §7) | PO | all |
| D0-2 | OQ-01: confirm which migrations ran in production on 2026-10-16 | Operations + PO | MEM-COR4, MEM-2B, F-4b |
| D0-3 | OQ-09 DEC-SEC-DB, with P-DB and I-1 | ARB + PO + Infrastructure | Increments 3, 4 |
| D0-4 | Package G (GOV-END; CQ-3 incl. "reason"; CQ-9 / D-COR-5 (B); DS-4; LQ-1; D-3-L; SEC-R3; SEC-R5; suspend / archive authority) | Governance + PO + Legal (per item) | MEM-2B, MEM-COR3A, MEM-SEC-FIX-S3 / S5, MEM-HTTP |
| D0-5 | OQ-02 BD-REV-ER, OQ-03 D-COR-3 | PO | MEM-COR2 |
| D0-6 | OQ-04 boundary date rule; OQ-05 ties; OQ-06 column; OQ-07 end-kind representation; OQ-08 PUB; OQ-10 ACC-260 security conditions | PO / ARB / PR as listed | as listed |
| D0-7 | acceptance verdicts ACC-258 / 259 / 268 / 271 and DEC-G6 (A1 evidence exists) | Human PO / ARB (after PR review) | Increment 2, MEM-COR2 |

---

## Increment 1 — Baseline and evidence

### Draft MEM-EV-A2 → **apply to existing `PBDIGIT-268`** (no new ID)
- **Objective:** make the G3 recommendation traceable.
- **Requirement:** CC §15.7(7); G3 dispositions §1 (cites the non-existent `202610101901_implementation.md`).
- **Scope:** add (or reference by a tracked copy) `docs/publicdigit/membership/architecture/brainstorming/20261010_1901_implementation.md`; correct the citation; record DEC-G6 once decided. Documentation only.
- **Acceptance:** the citation resolves to a tracked file; decision text unchanged; DEC-G6 recorded by its owner.
- **Approver:** Human PO (documentation change; DEC-G6 is PO / ARB).

### Draft MEM-EV-A1b — wider Membership suite characterisation (new)
- **Objective:** know the health of Membership code outside `Organisational` before any slice relies on it (G3 gap G-5).
- **Scope:** database-free files only under `tests/Unit/Contexts/Membership` (selected as in A1: files importing `PHPUnit\Framework\TestCase` and no Laravel testing trait), in a `git archive` copy of a named commit; `composer install` from that commit's lock (the use of `--no-scripts` must be in the authorisation this time).
- **Allowed:** running PHPUnit with `--no-configuration`; storing outputs. **Prohibited:** fixing anything; any database; the shared working tree.
- **Acceptance:** every file classified pass / fail / error with cause class; no fix; outputs hashed.
- **Approver:** Human PO.

---

## Increment 2 — Kernel and fitness hardening (database-free)

### Draft MEM-K1 — An Annul must itself carry decision provenance (new, follow-up of PBDIGIT-268)
- **Type:** defect against an approved requirement (V-2). **Requirement:** BR-9a, CC §3.1 "Resulting fact `Annul{factId, target, provenance(decision, authority, reason), decidedAt}`".
- **Depends on:** ACC-268 (so that the accepted COR-1 is not reopened); a contract amendment naming the refusal (additive), approved by PR / PO.
- **Decision needed:** the refusal name (proposal: `AnnulRequiresDecisionProvenance`); placement in the guard order (proposal: after `DecidedAtRequired`, before target rules — to be confirmed in the amendment).
- **Scope:** `Member::decideAnnul` guard; `RefusalReason`; tests. **Out:** Void (any provenance is valid for Void per ratified §2: "Void | … | any fact" — the rule concerns the target, and the contract places no provenance restriction on the Void itself; to be confirmed in the amendment); persistence; commands.
- **RED tests (K):**
  - Given an admitted period with decision provenance, when `decideAnnul` is called with `rule` provenance, then it is refused with the new reason and the version is unchanged.
  - Same with `attested` provenance.
  - Given decision provenance on the Annul, then K-A1 behaviour is unchanged (characterisation).
  - Guard-precedence test against `DuplicateFactId`, `TargetNotFound`, `DecidedAtRequired`.
- **Acceptance:** RED recorded; GREEN; C-1 differential still 15,530 decisions with no mismatch (the oracle uses decision provenance throughout); G6 hash unchanged; R7 / R8 fitness tests green.
- **Approver:** Human PO / ARB (fix authorisation and contract amendment).

### Draft MEM-R — Fitness-scanner hardening (new; absorbs the Slice 2a follow-up "R5 onto the parser scan")
- **Type:** evidence / hardening (V-3, V-10). **Requirements:** Slice 2 plan §4.5–§4.7 (R5, R7, R8); BR-15.
- **Scope:**
  1. R7 forbids `new Annul(` and `new VoidFact(` outside `Member.php` and the decoder;
  2. allowlists per rule, not per file (`Decision::accepted` only in `Member.php`; `Provenance::attested` / `::rule` only in `ProvenanceJson.php`);
  3. scan roots extended to `database/` (seeders, factories) for R7 / R8;
  4. R5 moved onto the parser scan.
  Known limits stay documented (dynamic names, reflection, `eval`).
- **RED tests (R):** planted violations for each new form, each **detected**; the real tree stays clean; the old planted matrix (P1–P23, N1–N7) unchanged.
- **Acceptance:** each new detection proven by a planted case; real-tree scan clean at the committed baseline; `MembershipDecisionCreationTest` and `MembershipLedgerIsolationTest` green.
- **Approver:** Human PO.

### Draft MEM-CUT0 — Reproducible inventory of writers of `members` (new)
- **Type:** cutover gate CUT-0 ("a fitness test enumerates writers of `members`; an unknown writer fails the build", cutover gate `:291`). **Requirement:** BR-21.
- **Depends on:** nothing decided; the allowlist is the CUT-3 inventory (REPORTED: 9 direct writers, 8 DDD callers, the import, `ExpireMemberships`, which this story re-derives).
- **Scope:** a static architecture test over `app/`, `database/`, `routes/` detecting writes to `members` (Eloquent model writes on the legacy `Member` model, `DB::table('members')` mutations, query-builder updates / deletes, relation deletes), with an explicit allowlist; database-free.
- **RED tests (R):** a planted unknown writer fails; each listed writer form is detected; the real tree matches the allowlist exactly.
- **Acceptance:** the allowlist equals the re-derived inventory; differences from the REPORTED counts are explained in the evidence.
- **Approver:** Human PO.

### Existing stories to authorise in this increment (no new IDs)
- `PBDIGIT-267` TG-1…TG-5 (tests only; TG-1 covers the existing `fromLog` checks and is a prerequisite context for V-1 in MEM-COR4).
- `PBDIGIT-269` runner hardening (G3 residual risk).

---

## Increment 3 — Isolated database and PostgreSQL verification (existing `PBDIGIT-264` Part 2; no new ID)

- **Apply to `PBDIGIT-264`:** the Part 2 plan (`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`) is authoritative. Add two conditions: (a) the pre-connect guard **reuses** the PBDIGIT-283 `DatabaseTargetRules` (no second guard); (b) record OQ-01's outcome, because F-4b becomes a forward migration on a released table if the ledger tables are live in production.
- **Gate:** D0-3 (DEC-SEC-DB, P-DB, I-1, PV accepted), Part 2 approval.
- **Order (from the plan):** fail-closed proof → E4-L test-first → migrations only on P-DB → Membership PostgreSQL suite → before / after check of `nrna_test` → F-5 → F-4b → F-2.
- **Exit:** Slice 1b re-entry criteria 1–4 (G3 dispositions §4), DV-1, OQ-10 → ACC-260.

---

## Increment 4 — Security (existing `PBDIGIT-266` for characterisation; new fix stories)

### Apply to `PBDIGIT-266` — characterisation (ARB-SEC-01 scope: tests only)
- Update its item table with S-1 … S-11 (verification rev 1 and the 2244 review §4), marking S-1, S-2, S-3, S-4, S-5 (source), S-7 (export), S-10 (code) VERIFIED at source level and S-6, S-8, S-9, S-11 REPORTED.
- **Gate:** D0-3. **Allowed:** feature tests recording actual behaviour. **Prohibited:** any fix, route, middleware, schema or configuration change.
- **Acceptance:** each item confirmed or refuted with stored output and database identity proof.

### Draft MEM-SEC-FIX-S2 — Fee waiver confined to the member's organisation (new)
- **Requirement:** SEC-R2 (existing sibling rule: `MembershipFeeController.php:26, 42, 82, 108`). **No new business rule.**
- **Depends on:** characterisation of S-2; fix authorisation; D0-3.
- **RED tests (F):**
  - Given an admin of A and a pending fee of a member of B, when the waive route is called under A's slug (session organisation set as in the characterisation), then 404 and the fee stays `pending`.
  - Given a fee whose `member_id` does not match `{member}`, then 404 (existing behaviour, characterisation).
  - Positive: admin of A waives a pending fee of A's member → `waived`, `recorded_by` set.
- **Acceptance:** RED recorded; GREEN by adding the same organisation check as the siblings; no other controller change.
- **Approver:** Human PO / ARB.

### Draft MEM-SEC-FIX-S4 — Personal dashboard data never shared through the role cache (new)
- **Requirement:** SEC-R4 (privacy). **No new business rule.** Evidence: key `membership_dashboard_{orgId}_{role}` with `memberSelf` from `auth()->id()`.
- **RED tests (F):**
  - Given members U1 and U2 of organisation A, when U1 then U2 load the dashboard within the TTL, then U2's `memberSelf` belongs to U2.
  - Positive: an officer's aggregate statistics may still be cached per organisation and role.
- **Acceptance:** per-user data is not stored under a shared key (either excluded from the cached payload or keyed by user — choice documented); invalidation still works.
- **Approver:** Human PO / ARB.

### Draft MEM-SEC-FIX-S1 — Organisation selection only within the caller's memberships (new; platform scope)
- **Requirement:** SEC-R1. **Blocked by:** DEC-TENANT-HDR (ARB, platform security); the Election owner informed (the `tenant` group is on Election routes).
- **RED tests (F):** a verified user with no role in B sends `X-Tenant-Id: B` → refused and the session does not contain B; a later request without the header does not act on B; a member of B with the header → allowed.
- **Approver:** ARB.

### Draft MEM-SEC-FIX-S3 — Committee composition changed only by the authorised role (new)
- **Requirement:** SEC-R3 (UNDEFINED). **Blocked by:** Package G rule (Governance + PO).
- **RED tests (F):** a voter-role participant of A adding / removing a committee member → refused; the authorised role → change recorded; a non-member of A → 403 (existing `tenant.api` behaviour, characterisation).
- **Approver:** Human PO / ARB.

### Draft MEM-SEC-FIX-S5 — Committee-seat application review and apply (new; one story, never split)
- **Requirement:** SEC-R5 (UNDEFINED reviewer rule; self-approval ban). **Blocked by:** Package G rule; eligibility per application type (`MANUAL` / `EXCEPTION`).
- **Mandatory coupling:** the repository's INSERT-only save is repaired **in the same story** as reviewer authority, organisation membership and the self-approval ban; the existing test that codifies self-approval (`CommitteeMembershipApplicationTest.php:144-166`, REPORTED) is replaced, not adapted.
- **RED tests (F):** T1 unauthenticated → 401; T2 verified non-member → 403; T3 applicant reviews own application → refused; T4 plain member → 403; T5 authorised reviewer approves another user's application → 200, `reviewed_by` stored, exactly one active association; T6 application of another organisation → 404; T7 unknown id → 404, not 500; apply: non-member → 403.
- **Approver:** Human PO / ARB.

**Not yet drafted (rules undefined):** S-7 exports / import / geo-unit creation, S-9 import consent, S-10 suspend / archive / create authority. They follow Package G.

---

## Increment 5 — Slice 2b (draft MEM-2B: new ID proposed, so that `PBDIGIT-271` can close on Slice 2a)

- **Requirement:** Slice 2 plan §10 AC-11…AC-13; BR-6, BR-7.
- **Gate:** ACC-260 (Part 2 accepted, DV-1, OQ-10), GOV-END, OQ-07, separate authorisation. **Not** gated by COR-2…COR-4 (P-02).
- **Scope:** container bindings (`MemberRepositoryPort` → `PostgresMemberRepository`; `AuthorityCheckPort` → `InterimRoleAuthority`); the Eloquent `OrganisationRoleReader`; End authority per GOV-END; `end_kind` CHECK on the closed set (forward migration if OQ-01 = yes); load-time check of legacy ⇔ attested and a decode-failure test (V-11); a handler + interim-adapter integration test (AC-5 limitation). **Out:** HTTP / UI; live writes (D-6 / R10).
- **RED tests:** P: an unknown stored `end_kind` is rejected by the database; P: loading a stored `legacy` End without attested provenance fails with a defined integrity error; A/P: bindings resolve; P: role reader returns owner / admin / member / none per organisation; A: End per the GOV-END rule.
- **Approver:** Human PO / ARB.

---

## Increment 6 — Correction completion (new drafts)

| Draft | Content | Gate | Key RED tests | Approver |
|---|---|---|---|---|
| **MEM-COR2** | Correct + consequences (g2, CFC+), BD-REV-ER guard, CC §14.5 cases 1–10 | ACC-268; OQ-02; OQ-03 | each §14.5 case; differential against the g6 oracle extended to Correct; properties | PO / ARB |
| **MEM-COR4** | persistence of every kernel-accepted fact (schema / reference column, same-Member target FK, encode / decode, tenant scoping), defined outcome instead of the raw `\Error`, load-time structural validation (V-1), tie rule (OQ-05) | ACC-260; ACC-268; OQ-01; OQ-05; OQ-06; DV-1 | P: Annul / Void (and Correct, unless split) round-trip through a fresh repository instance with identical resolution; P: a stored Annul(Void) fails to load with a defined integrity error; P: race tests | PO / ARB |
| **MEM-COR3A** | Annul / Void / Correct commands and handlers through `DecideAndAppend`; authority evidence incl. "reason"; idempotency per command; **no event emission** | MEM-COR4 accepted; CQ-3 incl. "reason"; G-AUTH-1; D-COR-5 (B) | C-AMEND-1 C-3 items 1–2 (populated evidence unchanged + sensitivity mutant); IDEM-1…4 per command; re-decide on conflict | PO / ARB |
| **MEM-PUB** | event publication by reuse of the shared outbox (EP-01), written in the append transaction; consumer-slice driven | OQ-08; first consumer slice named | P: an outbox row exists iff its fact is committed; tenant id on every row | ARB |
| **MEM-COR3B** | emission of `FactAnnulled`, `FactVoided`, `FactCorrected`, `TrimRevived` | MEM-PUB; MEM-COR3A | P: one event per accepted decision, none on refusal | PO / ARB |

---

## Increment 7 — Boundary, integration and cutover (placeholders, not ready)

| Placeholder | Content | Gate |
|---|---|---|
| MEM-HTTP (Slice 14) | trusted command envelope (tenant, actor from the authenticated context; OQ-04 date rule); pages | D-6 / R10; BD-CONSENT; BD-REC0; LQ-1; D-3-L; Package G; affected security fixes; MEM-2B |
| MEM-Q (Slice 3) | queries / projections, Membership query port | MEM-2B; MEM-PUB where events are consumed |
| MEM-CUT1…10 | one story per cutover gate (CUT-1 flag … CUT-10 environment) | ARB-MEM-01; ACC-260; DV-1; PBDIGIT-265; MEM-CUT0 |

---

## Traceability summary

| Draft | Requirement | Existing ticket touched | New ID needed? |
|---|---|---|---|
| MEM-EV-A2 | CC §15.7(7) | PBDIGIT-268 | no |
| MEM-EV-A1b | G3 G-5 | — | yes |
| MEM-K1 | BR-9a | follow-up of 268 | yes |
| MEM-R | BR-15, Slice 2 §4.5–4.7 | follow-up of 271 | yes |
| MEM-CUT0 | BR-21 | — | yes |
| Part 2 | BR-12, BR-13, BR-20 | PBDIGIT-264 | no |
| characterisation | SEC-R1…R5 | PBDIGIT-266 | no |
| MEM-SEC-FIX-S1…S5 | SEC-R1…R5 | follow-ups of 266 | yes (5) |
| MEM-2B | AC-11…13 | split from PBDIGIT-271 | yes (PO choice) |
| MEM-COR2 / COR4 / COR3A / PUB / COR3B | BR-10, BR-11, BR-16, BR-19, BR-24, BR-26 | follow-ups of 268 | yes (5) |
