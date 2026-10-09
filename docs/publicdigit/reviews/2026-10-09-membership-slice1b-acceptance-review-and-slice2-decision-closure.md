# Membership: Slice 1b Acceptance Review, Slice 2 Decision Closure, Participation Research Contract

**Date:** 2026-10-09 · **Story:** `PBDIGIT-260` (review) · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Instruction:** `docs/publicdigit/membership/architecture/brainstorming/2026109_2043_membership_implementation.md` (Principal Reviewer), phases A–F.
**Status:** REVIEW + DECISION REQUEST. It creates no business decision, no ratification and no code. Engineering supplies evidence; acceptance and ratification are the Principal Reviewer's and the PO's.

## 0. Disagreements with the instruction (stated first)

| # | Instruction | Position | Reason |
|---|---|---|---|
| X-1 | Phase A: "Issue an explicit verdict: ACCEPT, ACCEPT WITH CONDITIONS, or REJECT" | **I give a recommended verdict, not a verdict.** I am the author of Slice 1b. | R-34 / EP-02: engineering never accepts its own work. **Mitigation:** Phase A was run by an **independent reviewer agent** (fresh context, read-only, adversarial, own probes). I then re-verified its key findings myself (§2). |
| X-2 | Phase F treats Slice 2 as "Admit / End wiring incl. HTTP". The blockers named are consent, recognition, ending kinds, authority and time zone. | **One blocker is missing: legacy coexistence (EP-01 principal review §16, R10).** An HTTP Admit for a Full-Membership organisation would create a second membership truth. Full-mode voter eligibility reads the legacy `members` table (`VoterEligibilityService.php:~63-83`), and 23 application files read `members` directly. | The next slice must be split: command wiring without a live entry point first (no R10 dependency); then cutover, reverse projection and write guard (R10); then HTTP / UI (§7). |
| X-3 | Phase C lists organisation-local "today" as a business decision | **It is mostly a boundary default, not a domain rule.** The kernel takes explicit calendar dates (EP-Q6 = T1), and the register classes the tenant time zone as an implementation decision (`final-decision-register.md:160`). | **The business question hidden in it** is the allowed range of `effectiveAt` relative to the decision date (backdating, future-dating). That one is genuinely business (§4, D-5). |
| X-4 | Phase E: "prepare a bounded research contract" on committees, roles, positions, mandates, obligations, services | **Agreed, but scoped to the gaps.** `2026-10-09-organisational-participation-domain-research.md` already covers committees, positions, tenures, appointments, mandates and authority derivation, with RD-1…RD-5 and BQ-P1…P7 open. | No duplication: the contract reuses those findings and opens only what is new (responsibilities vs permissions, obligations vs payments, service access, succession). |
| X-5 | Phase C asks for "the authority required to admit or end" | **Evidence and an interim option only.** I do not recommend that Membership decide it. | The authority model is **DS-4, a Governance decision** (`final-decision-register.md:158`). |
| X-6 | Phase B: correct obsolete MySQL defaults and dead code | **Agreed, through their own stories.** They are already recorded as `PBDIGIT-261` / `262`. I did not execute them here. | Not authorised; doing so would broaden this commission. |

## 1. Slice 1b: recommended verdict

**Recommended: ACCEPT WITH CONDITIONS.** The Principal Reviewer decides.

The core of the slice held up under an independent adversarial review: idempotency, atomic append, tenant scoping and the absence of any authoritative persistence state. The reviewer **could not construct an interleaving** that returns a wrong outcome for an identical retry (§2, A-2).

There is **one real, low-severity defect (F-1)**, and the implementation review over-claimed in three places (F-6 … F-8, now corrected).

**Conditions proposed:**
1. **Fix F-1, RED first, or bound the domain by decision.** F-1 is the far-date defect.
2. **Accept or decline the residual risks F-2 … F-5 as a follow-up story** (`PBDIGIT-264`), not as a reason to reopen 1b.
3. **Confirm the DV-1 authorisation.** The PO's "Allow minimal fix" (2026-10-09) is now also recorded in the 1b contract (§3).

## 2. Verified findings

**Method:**
- An independent reviewer agent read the contract, the review, all code, the migration and the tests.
- It ran the Organisational unit suite (34 tests OK), the three architecture tests (12 OK) and the Feature suite including the `membership-race` group (27 tests, 184 assertions, OK). The T11 outcome counts matched the review.
- It ran scratchpad probes against `nrna_test` only.
- I re-verified F-1 (reproduced below), the dirty factory (F-7) and the absence of kernel / port / handler diffs.

### Phase A checklist

| # | Check | Result | Evidence |
|---|---|---|---|
| A-1 | Kernel and port preserve the approved semantics | **holds** | `git diff 1013555aa..2aa9ac69b` is empty for `Domain/Organisational/`, `Ports/` and both handlers. Only `DecideAndAppend` changed (DV-1). |
| A-2 | An identical concurrent retry gets the original outcome; one (key, fingerprint) gives at most one fact | **holds** (residual: F-2) | Interleavings checked: CAS waits for the row lock, then re-reads the key (`PostgresMemberRepository.php:160-167`); load after commit → `DuplicateFactId` → answered from the key (`DecideAndAppend`); Admit via `create` / `HolderExists`; same key + other fingerprint → `IdempotencyKeyConflict`; same FactId + other key → `DuplicateFactId`. The key PK and the fact insert share one transaction. |
| A-3 | Key, fingerprint, CAS and transactions are consistent | **holds** (residual: F-3) | The fingerprint covers every command field except the key, including caller-supplied `decidedAt` and `memberIdIfNew` (`AdmitMember.php:154-160`, `EndMembership.php:185-191`). Nothing is filled from a clock. |
| A-4 | Tenant enforced in every operation and FK | **holds** (residual: F-4) | Every query filters on `organisation_id`; composite FKs (migration). |
| A-5 | Three initial-term states; calendar dates | **holds within years 0001–9999**; **F-1 beyond** | Ordinals −719162 … 2932896 round-trip, including before 1970. |
| A-6 | Unexpected DB failures are not translated | **holds** (residual: F-5) | Only a unique violation on `org_membership_commands_pkey` is caught; without a key row it is rethrown. The probe also covered an outer transaction (savepoint). |
| A-7 | No stored status is authoritative membership truth | **holds** | `load` never reads `version`; the version is the log length. The copies of `seq` / `recorded_at` in `org_membership_commands` serve retries only. Resolution reads the facts. |
| A-8 | Architecture tests enforce the dependency direction | **holds**; over-claim F-8 | Application → Infrastructure is caught (1b regex + 1a import whitelist). Domain → Illuminate is caught (kernel purity). |
| A-9 | Regression comparison is honest | **partly**; over-claims F-6 / F-7, corrected | see below |

### Findings

| ID | Class | Finding | Failure scenario | Minimal remedy |
|---|---|---|---|---|
| **F-1** | **DEFECT** (low severity, but irreversible per Member) | `DateOrdinal::toDate` formats ordinal ≥ 2932897 as `10000-01-01`. PostgreSQL accepts it; `fromDate` (`!Y-m-d`) rejects it. | Admit with effectiveAt 2932897 → `Appended`, then every `load()` throws `UnexpectedValueException`, so the **Member is permanently unloadable**. Reproduced by me: `2932897 -> 10000-01-01 -> UnexpectedValueException`. Year 0000 / BC fail safely (PostgreSQL rejects them at write). `TimePoint::of` is unbounded, so Slice 2 input could reach this. | A range guard 0001-01-01 … 9999-12-31 in `toDate` (fail at write), RED first. Or a PO decision bounding valid time in the domain. |
| F-2 | Residual risk (test gap) | The identical-retry race is tested for **End only**. There is no race test for an identical **Admit** retry with concurrent creation, and no committed test for the cross-Member key catch path (the reviewer's probe exercised it: correct). | A regression in the Admit path would go unnoticed. | Two tests. |
| F-3 | Residual risk (adapter parity; **port contract silent**) | `create()` with a MemberId that already exists **for another person** in the same tenant: the in-memory adapter **overwrites** that Member (`tests/Doubles/InMemoryMemberRepository.php:62`); PostgreSQL throws a PK violation (an infrastructure error). The order of checks in `append` (MemberNotFound vs key) also differs. | Only reachable through a caller-supplied id collision. The test double can hide a bug that production would surface as a 500. | Define the case in the 1a port contract (recommended: an infrastructure error, as in PostgreSQL), and make the double match. |
| F-4 | Residual risk | `org_membership_commands` has an FK only to `org_memberships`. A key row pointing at a non-existent fact is accepted by the database (probe). | Only the adapter's transaction keeps them in step; a manual write could break it. | Composite FK `(organisation_id, membership_id, seq)` → facts PK. |
| F-5 | Residual risk | The constraint is recognised by `str_contains(getMessage())`. Correctness also assumes READ COMMITTED: under REPEATABLE READ a CAS conflict surfaces as an exception, which is safe but untranslated. | A misroute falls back to rethrow, so it is safe. | Use SQLSTATE + the constraint name from the error detail; state the isolation assumption. |
| N-1 | Note | After the original command was applied, a retry that is then **denied by authority** gets `NotAuthorised`, not the original outcome. | Authority is re-checked before the key on that path (R-1a-2). | A contract decision, not a defect: recorded for the Slice 2 contract. |
| N-2 | Note | A refused first admission leaves an empty Member row (`create` autocommits). | Unchanged 1a semantics; the empty row has no facts. | none |
| F-6 | **Over-claim** (corrected) | §7 attributed the error-count difference (55 vs 54) to the known flaky test **without evidence**. | — | Corrected: "not attributed; not investigated". |
| F-7 | **Omission** (corrected) | Both regression runs used uncommitted working-tree changes **that are not part of Slice 1b** (origin not recorded), among them `database/factories/OrganisationFactory.php` (`faker->unique()`, aimed at the very slug flake), `tests/Feature/Election/*` and `tests/Support/ElectionScenarioFactory.php`. Because `vendor/` is shared, the baseline also ran the current `app/` code. | The comparison shows "no new failure under the same working tree". It does not show a clean-checkout baseline. | Disclosed in §7 of the implementation review. |
| F-8 | **Over-claim** (corrected) | "T13 ✅", but T13 does not enforce the contract's "kernel / application unchanged" clause (that was shown by `git diff`, not by the test). "Race-free" (§6) holds only under READ COMMITTED with an autocommitted `create`. | — | Wording corrected. |

**Not a new finding:** the legacy ability `manageMembers` (`MemberController.php:309,338`) is defined nowhere. It is already known as `IERVP-3` and tracked in `PBDIGIT-68` G-7.

## 3. Record closure (Phase B)

| Item | Done | Where |
|---|---|---|
| Index rows `PBDIGIT-258 … 263` | ✅ | `0b2685c38`. The other session's uncommitted `PBDIGIT-256` row is left in their working tree. |
| Test-transaction documentation (O-1) | ✅ new `PBDIGIT-263` | The comment "ZERO data corruption possible, even if wrong database is targeted" is **false** on PostgreSQL: there is no per-test transaction, and `migrate:fresh` runs once per process. |
| Production engine verification (O-3) | already in `PBDIGIT-261` ("verify the production `.env` (operator)") | no duplicate |
| Migration not rolled out (O-4) | already stated | `PBDIGIT-260` status · dev guide 03 · index row |
| 1b follow-ups F-1 … F-5 | ✅ new `PBDIGIT-264` | not authorised |
| Review corrections F-6 … F-8; DV-1 recorded in the 1b contract | ✅ | the implementation review §7 / §11, contract amendment note |
| MySQL residue | `PBDIGIT-261` / `262` | not executed (X-6) |

## 4. Slice 2 decision register (Phase C)

Evidence labels: **L** = legacy code, **K** = the new kernel, **R** = ratified / adopted, **G** = register (open recommendation), **P** = proposal.

### D-1: BD-CONSENT / LQ-1, the basis of an admission

1. **Question.** May a person become a Member without their own declaration (application or acceptance)? If so, on what other basis (statute, ex officio, import of history)?
2. **Approved evidence.**
   - G: recommended "no", via a consent guard (`final-decision-register.md:43,114,170`).
   - R: "Whether a declaration … **must** be present is BD-CONSENT / LQ-1 / DS-4 (Slice 2)" (`slice0-semantic-design-contract.md:278`).
   - K: `Provenance` already has three kinds, `decision(actor, evidence, declarationRef?)`, `rule(ruleRef, version)` and `attested(source, importer)` (`Provenance.php:32-44`). `AdmitMember.declarationRef` is nullable and unguarded (`AdmitMemberHandler.php:53`).
3. **Conflicting evidence (L).** Legacy admits **without** a declaration on three paths:
   - admin add (`MemberController.php:93-110`);
   - role promotion (`OrganisationRoleController.php:102-108`);
   - CSV import of up to 50,000 rows (`MemberImportService.php:57,68`; `MEMBER_IMPORT_GUIDE.md`).

   It admits **with** one on two paths: public application → approval (`MembershipApplicationController.php:224,251-262`) and invitation acceptance (`OrganisationMemberInvitationController.php:~207-224`). No consent field exists anywhere in the schema.
4. **Alternatives.**
   - **C1, basis required:** every Admit carries exactly one basis: a declaration reference, a statutory rule reference, or attested history (migration only).
   - **C2, optional:** record a declaration when present.
   - **C3, per-organisation setting.**
5. **Consequences.**
   - **C1:** adds one domain refusal, e.g. "admission basis missing". It is an additive guard over the existing provenance kinds; Slice 0 is not redesigned. Admin add then needs a reference, such as the signed paper form. CSV import becomes either history (attested, migration only) or rows with references.
   - **C2:** forced and consensual admissions become indistinguishable in history, and the decision is unrecoverable later.
   - **C3:** multiplies the semantics per tenant without evidence that any organisation needs it.
6. **Recommendation: C1.** A declaration *reference* is evidence, not a stored document. The register's direction is "no", and C1 is the only option that keeps the history able to answer "on what basis is this person a member?".
7. **Ratifier:** PO (product), plus legal advice for LQ-1 (statutory / ex-officio membership).
8. **Blocks Slice 2 Admit:** **yes.**

### D-2: BD-REC0, a pending member

1. **Question.** Can a "member, not yet confirmed" state exist?
2. **Approved evidence.** G: recommended "no"; the alternative would be an optional Recognise fact (`:40`).
3. **Evidence (L).**
   - "pending" is an **application** state (`membership_applications`: submitted / under_review / approved / rejected; auto-rejected when stale, `ProcessMembershipExpiryCommand.php:16-20`).
   - Legacy Member rows are born `active`.
   - Counting, newsletters and voting use `members.status='active'` only (`VoterEligibilityService.php:81`, `ElectionMembership.php:140`, `NewsletterService.php:143`).
   - **Conflict:** the DDD value object `MemberStatus` has PENDING / APPROVED, and `canHoldCommitteeRole()` admits APPROVED (`Domain/ValueObjects/MemberStatus.php:17-24,200`). A second `MemberStatus` disagrees with it (`Domain/Member/MemberStatus.php:9-12`).
4. **Alternatives.**
   - **R1:** no pending Member; the application process stays outside the Member aggregate, and approval records the Admit with `declarationRef` = the application.
   - **R2:** a Recognise fact (two-step admission).
5. **Consequences.**
   - **R1:** the invariants are unchanged and Slice 2 has one fact.
   - **R2:** a new fact kind, a new predicate (recognised but not admitted) and a new G\* case. It also legitimises the DDD `APPROVED` anomaly.
6. **Recommendation: R1.** All running behaviour already treats pending as an application state. The single contrary artefact is an unratified value object.
7. **Ratifier:** PO.
8. **Blocks Slice 2 Admit:** **yes** (it fixes the shape of the command).

### D-3: BD-3 / LQ-3, ending kinds

1. **Question.** Which acts end a membership, and does each one need its own semantics?
2. **Approved evidence.**
   - K: `EndKind` is opaque and resolution never reads it (`EndKind.php:9-17`).
   - G: "resignation, exclusion, organisation termination, death"; "implementation can proceed without it: yes" (`:38`).
3. **Conflicting evidence.**
   - L: only free text `end_reason` (`2026_04_03_155709…`).
   - L: `endMembership()` has no production caller and its blueprint disposition is DELETE (`final-implementation-blueprint.md:81`).
   - The user doc lists "resignation, non-payment".
   - L has `expired`, which is **not an End**: in the kernel it is term lapse, derived from the initial term (C-S0-2).
   - No UI offers death, exclusion or dissolution.
4. **Alternatives.**
   - **E1:** a closed list ratified now: `resignation`, `exclusion`, `death`, plus `legacy` for attested migration only.
   - **E2:** the opaque string as today.
   - **E3:** E1 plus "non-payment" as its own kind.
5. **Consequences.**
   - **E1:** validation at the boundary; resolution unaffected. Organisation dissolution ends *all* memberships, so it is a separate organisation-level use case and not a per-member End in Slice 2.
   - **E2:** history becomes free text again.
   - **E3:** conflates a *ground* (non-payment) with an *act* (exclusion by an organ, or lapse by term). Fees are DS-1 / Finance.
6. **Recommendation: E1.** Record non-payment as the ground of an exclusion or as a lapse, not as a kind. The procedure (hearing, appeal: LQ-2 / LQ-3) stays outside Slice 2: Slice 2 records the decision; it does not run the procedure.
7. **Ratifier:** PO (list); legal (procedure).
8. **Blocks Slice 2 End:** **no, if** the PO ratifies the list, even as interim.

### D-4: DS-4, authority to admit and end

1. **Question.** Who may record an Admit or an End?
2. **Evidence.**
   - L: `approveApplication` and `rejectApplication` = owner / admin; `viewApplications` adds commission (`MembershipPolicy.php:26,33,40`; `OrganisationPolicy.php:35-40,89-115`; `UserOrganisationRole` table).
   - K: `AuthorityCheckPort` exists; the only implementation is a test double.
   - R: "No Governance implementation (DS-4)" (`slice1a-contract.md:76-77`).
   - The Governance `AuthorityResolver` covers committee-to-committee assignments only.
3. **Conflict.** Suspend and archive are unreachable (`IERVP-3`).
4. **Alternatives.**
   - **A1:** an interim adapter that **mirrors the existing policy** (owner / admin) and records the role as `AuthorityEvidence`, labelled interim, replaced by Governance later.
   - **A2:** wait for the Governance authority model.
   - **A3:** a Membership-local policy. **Rejected:** Membership would own authority, which contradicts DS-4.
5. **Consequences.**
   - **A1:** introduces **no new policy**, because it reproduces today's rule. The evidence trail makes later re-evaluation possible.
   - **A2:** blocks Slice 2 for an unknown time.
6. **Recommendation: A1, only if** Governance's owner ratifies it as an interim mapping.
7. **Ratifier:** Governance (DS-4), through the PO.
8. **Blocks Slice 2:** **yes.**

### D-5: the calendar boundary ("today") and the range of `effectiveAt`

1. **Questions.**
   - (a) How does an instant become an organisation calendar date?
   - (b) **Business:** may `effectiveAt` lie before the decision date (backdating) or after it (scheduling)?
2. **Evidence.**
   - R: EP-Q6 = T1 (`slice1b-contract.md:13`).
   - L: `organisations.timezone` exists (default `UTC`) but is neither fillable nor read (`Organisation.php:17-52`). Elections have their own time zone.
   - No clock abstraction knows an organisation calendar.
   - R: "a backdated admission into a live lifetime is refused" (`final-decision-register.md:169`); this is already enforced by the kernel.
3. **Alternatives (a).**
   - **Z1:** commands always carry an explicit date; "today" is only a UI default computed in `organisations.timezone`.
   - **Z2:** the server derives "today" in UTC.
   - **Z3:** the server derives it in the organisation's time zone.
4. **Consequences.**
   - **Z1:** the domain never sees a time zone. Converting an instant to a local date is a total function, so there is no DST ambiguity. It needs the `timezone` attribute made readable.
   - **Z2:** gives a wrong date near midnight for non-UTC organisations.
5. **Recommendation (a): Z1** (implementation decision, `:160`).

   **Recommendation (b), a PO question:**
   - allow backdating; it is common for statutes, and the kernel already guards lifetimes;
   - forbid future-dated Admit / End in Slice 2, because scheduling is a different capability.
6. **Ratifier:** (a) engineering, recorded in the Slice 2 contract; (b) PO.
7. **Blocks:** (a) no; (b) **yes** (validation).

### D-6: coexistence with legacy `members` (EP-01 principal review §16, R10; existing, not new)

1. **Question.** For a Full-Membership organisation, which store is authoritative once new facts are recorded?
2. **Evidence.** R10 requires a per-organisation cutover flag, a single-writer reverse projection to `members`, a write guard on every legacy writer (including `ExpireMemberships.php:19`) and a write freeze during migration. 23 application files read `members`.
3. **Consequence.** Wiring a live Admit / End before R10 creates **two truths**.
4. **Recommendation:** keep R10 as designed, and sequence it as its own slice (§7).
5. **Ratifier:** already designed; the PO approves the slice.
6. **Blocks:** the HTTP / UI slice only.

**Election-Only (EM-EO-001…004, ADOPTED).**
- Admit / End matter only for Full-Membership organisations. Election-Only eligibility reads `organisation_users` (`VoterSourceStrategy.php:18-28`) and must never consult the ledger.
- The legacy `Member::endMembership()` cascade to `ElectionMembership` contradicts EM-EO-004 (`Member.php:186-189`). It is dormant: no production caller, disposition DELETE.
- **Entry criterion for any Slice 2:** a test that the Election-Only voter source never reads `org_memberships`.

## 5. The canonical distinctions (Phase D), as constraints on every next slice

`Person ≠ OrganisationAffiliation (organisation_users) ≠ Member (aggregate) ≠ MembershipPeriod ≠ ElectionRegistration (ElectionMembership) ≠ Admission ≠ VotingEntitlement ≠ Exercisability`.

- **Membership publishes predicates; it grants nothing.** Voting entitlement is Election's. Committee, role and mandate are Governance's. Fees and payments are Finance's.
- **Slice 1b's tables do not decide** membership, being in force or entitlement. Resolution over facts does (A-7).
- **No new aggregate and no identity change** is proposed here.

## 6. Organisational participation: research contract (Phase E; for approval, not started)

**Baseline (reused, not redone):** `2026-10-09-organisational-participation-domain-research.md`. It covers:
- Person-centred participation;
- Position ≠ Tenure ≠ Appointment;
- authority as a derived formula;
- the derived eligibility intersection (cascade computed as harmful: 140 / 476 bounded cases);
- ownership: Membership / Governance / Election / Finance;
- RD-1 … RD-5 and BQ-P1 … BQ-P7 open.

**Research question:** how does an organisation relate a Member (or Person) to committees, positions and mandates, and how do *responsibilities*, *obligations* and *service access* follow from those relations, without any of them being written into Membership?

| Gap (new) | Question | Method | Bound / evidence |
|---|---|---|---|
| G-1 Responsibility vs permission | Is a responsibility (a duty attached to a tenure) a distinct concept from a permission (an authority grant)? Do they share a derivation? | scenarios + falsification against the existing authority formula | ≥ 10 scenarios from the corpus and the legacy roles; counterexample search |
| G-2 Obligation vs payment | Is a financial obligation a *derived claim* (rule × membership predicates × period), and a payment a *settlement fact*, with Finance owning both? | formal model: obligations as a function of Membership predicates; bounded check against annul / correct | small-scope exhaustive check (T ≤ 5, ≤ 3 facts): does a derived obligation stay correct under annulment, where a written one does not? |
| G-3 Service / forum access | Is access a projection (policy over predicates and tenures) rather than stored state? | policy model; compare with the legacy newsletter and forum readers | list each legacy reader of `members.status` (23 files) by the predicate it actually needs |
| G-4 Succession and vacancy | Does a vacancy decision exist as a fact, or is it derived (BQ-P3)? | scenarios + the existing tenure model | — |
| G-5 Ownership map | Which context owns each concept in G-1 … G-4? Are there Published-Language interactions? | strategic DDD (five ownership questions) | the context map changes only by decision |

**Use of ML:** advisory only. For example, clustering the legacy role vocabularies (seven found) for the migration mapping. It is never an authority for business truth.

**Deliverable:** a Participation Semantic Contract draft, plus the answers to RD-1…RD-4 and BQ-P1…P3 needed for it. No code.

**Exit:** Principal Reviewer approval.

**Not in Membership Slice 2.**

## 7. Recommended next slices and entry criteria

| Order | Slice | Content | Entry criteria |
|---|---|---|---|
| 1 | **1b follow-up** (`PBDIGIT-264`) | F-1 date-range guard (RED first); F-2 tests; F-3 port-contract clarification + double parity; F-4 FK; F-5 constraint detection | 1b accepted with conditions; the PO authorises 264. **No business decision needed.** |
| 2 | **Slice 2: command wiring, no live entry point** | the D-1 basis guard; the D-3 kind list; the D-5(b) range validation; the interim authority adapter (D-4 A1); container binding of the port to the PostgreSQL adapter; the Election-Only non-consultation test | D-1, D-2, D-3, D-4, D-5(b) ratified; the Slice 2 contract approved |
| 3 | **Slice 3: cutover (R10)** | per-organisation flag; reverse projection to `members`; legacy write guard; migration freeze | Slice 2 accepted; the migration rolled out to dev (O-4); the operator check of `PBDIGIT-261` |
| 4 | **Slice 4: HTTP / UI** | Admit on application approval (declarationRef = application); End with kind; org-local "today" default (Z1) | Slice 3 accepted |

## 8. STOP

Nothing beyond the record closure was implemented. **Waiting for:**
- (a) the acceptance decision on Slice 1b and its conditions;
- (b) authorisation of `PBDIGIT-264`;
- (c) ratification of D-1 … D-5(b);
- (d) approval of the §6 research contract.

Slice 2 is **not** started, and no Slice 2 contract is written until (c).

```yaml
session_completion:
  status: Slice 1b GREEN, awaiting Principal Reviewer acceptance; decision request open
  completed_work: [independent acceptance review, record closure (0b2685c38), PBDIGIT-263, PBDIGIT-264, review corrections, decision register D-1..D-6, participation research contract]
  evidence: [this document §2, independent reviewer test runs and probes, my reproduction of F-1]
  open_items: [F-1..F-5 (PBDIGIT-264), D-1..D-5(b) ratification, research-contract approval, O-3 operator check (PBDIGIT-261), O-4 rollout]
next_actor:
  recommended_role: Principal Reviewer / PO
  reason: R-34 (engineering never accepts its own work); D-1..D-5 are product, legal and Governance decisions
  blocking_condition: no acceptance or ratification act yet
authorization:
  current_session_can_continue: yes (capability)
  authorized_to_act: no further implementation
  requires_human_decision: yes
```
