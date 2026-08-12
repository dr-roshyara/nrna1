# Session 2 — final governance closure

**Type:** Governance closure · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Status:** **SESSION 2 GOVERNANCE CLOSED — AWAITING `BR-1.12` FROM PRODUCT OWNER / ARB**
**⛔ No implementation. No production code, schema, migration or test change. No Constitution or ADR amendment. No new repository investigation performed.**

**This document does not restate rules.** Per the instruction not to let governance reports become a second source of Election rules, it **points** to where each rule is recorded and adds only what is new.

---

## 1 · 🔴 One thing I must raise rather than execute: the Election Manifesto does not exist

**The instruction says the Election Manifesto is the canonical home for Election business rules, and that Session 2 may prepare its authoritative wording.**

> **There is no Election Manifesto artifact in this repository.** A filename search across `docs/`, `architecture/` and `engineering/` for *manifesto* returned **nothing** — measured earlier in this session, and consistent with the materially-corrected `Finding 0` from the constitutional review, which established that **election constitutional authority lives in `ElectionConstitution.php` and the accepted ADRs (`001`, `003`, `004`, `005`)** — **not in a manifesto.**

**So the canonical home named in the instruction is either:**

| | Candidate canonical home | Status |
|---|---|---|
| **(a)** | `ElectionConstitution.php` + the accepted ADRs | **exists and is authoritative** — but it contains **no voter-level rule at all** (`ElectionMembership` appears **0** times; `admit`/`restore`/`revoke` **0**) |
| **(b)** | A new **Election Manifesto** document | **does not exist.** Creating it is a governance act, and **I have not created it** |

### 1.1 And the failure mode you warned about has already happened

> **You wrote: *"Do not allow the governance reports themselves to become a second source of Election rules."***
>
> 🔴 **They already are.** The adopted rules of this programme — `A-1`…`A-8`, `EO-1`…`EO-12`, the eleven hierarchy clauses, `Q-A0` = `F1`, `Q-B1`'s closure — **exist only inside Session 2 review documents and `PBDIGIT-68`.** **None is recorded in the Constitution, in an ADR, or in any artifact whose type is "authority".**
>
> **I am reporting this rather than fixing it**, because choosing the canonical home and migrating rules into it is a governance act — and doing it unasked would be the very duplication the instruction forbids.

**Consequence for Session 3 and Session 1:** they must currently read **review documents** to learn the authoritative rules. That works, and it is fragile: **a review is a record of an investigation, not a durable statement of authority.**

> **`D-MANIFEST` — PRODUCT OWNER / ARB DECISION REQUIRED:** *where is the canonical home for Election business rules — `ElectionConstitution` extended to the voter level, a new Election Manifesto, or ADRs — and who migrates the already-adopted rules into it?*

**I have prepared no Manifesto wording, because the artifact's existence and type are undecided. Once decided, preparing the wording is one focused commission.**

---

## 2 · Final governance status

| Item | Status | Recorded in |
|---|---|---|
| **`D-ENT-1` / Model B** | ✅ **ADOPTED** | `PBDIGIT-68` |
| **`Q-A0`** | ✅ **= `F1`** — existence not continuously dependent on organisation membership | two-mode report |
| **`D-ENT-2`** hierarchy refinement | ✅ **ADOPTED**, reconciled on the existence/exercisability axis | hierarchy-refinement package |
| **`Q-B1`** | ✅ **CLOSED** — Organisation Membership = the `Member` aggregate; system **conforms** (`members` 0/5) | admission gate §0.2 |
| **`CG-1`…`CG-4`** | ✅ **RECLASSIFIED** — 2 conformance questions · 1 closed · 1 evidence-only | admission gate §0.3 |
| **Election-Only-first sequencing** | ✅ **ADOPTED** | Election-Only baseline |
| **`FM-1`…`FM-15`** | ⏸️ **FROZEN** — not reopened | Election-Only baseline §2 |
| **Officer Guide** | **`D` — mixed authority; NOT promoted**, evidence only | governance-source analysis |
| **`ADR-002`** | **Accepted, UNAMENDED.** Silent on both admission questions → **not blocking**. v2 amendment proposal remains **open, not applied** | proposal v2 · application package |
| **`BR-1.12`** | 🔴 **THE ONLY BLOCKER for Election-Only admission** | §3 |
| **`D-MANIFEST`** | 🔴 **NEW — canonical home undecided** | §1 |
| **Implementation** | ⛔ **NOT AUTHORISED by Session 2** | — |

## 3 · `BR-1.12` — decision package

> ## **In Election-Only Mode, when the Election Chief imports a person as an ElectionMember, is that ElectionMembership immediately `ACTIVE`, or initially `INVITED` and requiring explicit Election Chief approval before becoming `ACTIVE`?**

**Authority: `BUSINESS RULE NOT SPECIFIED.`** `ElectionConstitution` silent (`admit` = **0** occurrences) · `ADR-002` silent · clause 10's *"directly"* addresses the absence of an **organisation prerequisite**, not an approval step · the Officer Guide's claim is **`CONTRADICTED`** and carries **no authority**.

| | **Option A — immediately `ACTIVE`** | **Option B — `INVITED` → approval → `ACTIVE`** |
|---|---|---|
| **Evidence** | **Production behaviour only** — import writes `'active'`; assignment uses the model default `'active'`; **no production path anywhere writes `'invited'`** | **Schema** (enum value) · **UI** (label, blue status pill, an `invited → active` action) · **Tests** (`makeMembership('invited')`, one asserting persistence) · **Documentation** (twice — voter list and dashboard) |
| **Officer workload** | Import is one step | Every imported voter needs an explicit approval pass |
| **Risk posture** | A mis-imported person is **immediately** a voter | A mis-imported person **cannot vote** until approved — an error-catching gate |
| **Effect on the existing estate** | Tests constructing `invited` assert an abolished state; the UI pill and action become dead; **documentation must change in two places** | An approval step must be **built** that has **never operated**; the four layers already expecting it become correct |
| **Voter's experience** | On the roll and able to vote as soon as import completes | On the roll but unable to vote until an officer acts — **and the guide already instructs officers to *"approve voters before opening voting"*** |

> **I do not choose, and I have not amended anything to make the current implementation appear authoritative.**
>
> 🔴 **The direction the discipline cuts here matters: production is the ONLY evidence for Option A.** So **keeping current behaviour is not the neutral option — it is Option A, chosen by default.** Whichever way you decide, it should be decided, not inherited.

## 4 · The four lists

### 4.1 Authorised rules

**Recorded in [`the Session-3 handover §A`](2026-08-12-handover-session2-to-session3-election-only-admission.md) — not restated here.** Eight adopted rules plus the authoritative vocabulary table *(Organisation Membership = `Member` aggregate; `organisation_users` and `user_organisation_roles` are technical association/role; the literal role value `'member'` is **not** the aggregate)*.

### 4.2 Implementation / conformance questions — **Session 3's, and not business decisions**

**Recorded in [`handover §B`](2026-08-12-handover-session2-to-session3-election-only-admission.md):** `B-1` `organisation_users` creation-vs-precondition · `B-2` `user_organisation_roles` creation · `B-3` semantics of those relationships *(`SEMANTIC NOT ESTABLISHED`)* · `B-4` whether the admission policy is actually wired *(`MECHANISM NOT ESTABLISHED` — domain policies are stubs returning `true`; `VoterQualificationPolicy` does not exist)* · `B-5` the absent FK **— closed, do not restore.**

**No implementation ticket has been created for any of them.** A conformance question is not a ticket.

### 4.3 Unresolved business decisions

| # | Decision | Blocks |
|---|---|---|
| **`BR-1.12`** | admission state | 🔴 **the Election-Only ADMISSION slice** |
| **`D-MANIFEST`** | canonical home for Election rules | ⚠️ **Corrected wording (Product Owner, 2026-08-12): distinguish TECHNICAL BLOCKING from GOVERNANCE READINESS.** It blocks no slice *technically*; **but for Election-Only ADMISSION it is part of governance readiness**, because the sequence must be `BR-1.12` decided → rule canonicalised → **then** TDD. **Otherwise the first test Session 3 writes — `it('creates an active ElectionMembership')` — encodes the business rule before it has a home.** My earlier phrasing *"blocks nothing technically"* understated that |
| `BR-1.13` | suspension actor count | the **suspension** slice only |
| `Q3` | exercisability representation | changes to non-exercisability |
| `Q-E1` · `Q-E2` | credential control · distinguishability | coherence, schedulable |
| `BR-1.1`/`1.2` · `BR-1.8` | removal · restoration | their own slices |
| `D-APPLY` (+`V-1`,`V-2`,`V-3`) | apply the `ADR-002` v2 amendment | nothing in this phase |
| `FM-1`…`FM-15` | Full Membership | **frozen** |

### 4.4 Prohibited work

**No production code · no schema · no migration · no test · no fixture change · no Full-Membership work · no suspension work · no credential change.**
Additionally: **do not restore the dropped FK** · do not create `Member` rows · do not activate `FullMembershipPolicy` · do not implement `scopeEligible()` *(returns 0 of 20; would deny every voter)* · do not rename or remove the `'member'` role value · do not vacate `status='inactive'` before a replacement enforces · do not amend `ADR-002` or the Constitution · do not promote the Officer Guide · **do not resolve `BR-1.12` implicitly by keeping current behaviour.**

## 5 · Session boundaries observed

| Session | Owns | Session 2's conduct |
|---|---|---|
| **Session 2** | **WHAT IS AUTHORITATIVE** | This closure. **No implementation, no tickets, no code.** |
| **Session 3** | **HOW IT IS IMPLEMENTED** | **Not instructed to implement any unresolved decision.** Handover explicitly confers no implementation authority |
| **Session 1** | **WHETHER IT IS VERIFIED** | **Master Matrix not read, classified, consumed or modified. No row count cited as fact** |

**No session's job was silently performed by another.**

---

## 6 · Recorded closure state

**The six items this closure records, per the Product Owner's stop condition:**

| # | State |
|---|---|
| **1** | **`BR-1.12` — OPEN.** Blocks the **Election-Only ADMISSION** implementation slice |
| **2** | **`D-MANIFEST` — OPEN.** No Election Manifesto artifact exists |
| **3** | **Election-Only-first — ADOPTED** |
| **4** | **Full Membership — FROZEN** (`FM-1`…`FM-15`); **no `Organisation Membership → ElectionMembership` lifecycle coupling may be introduced into Election-Only implementation** |
| **5** | **Session 3 boundary** — owns implementation; may proceed on authorised Election-Only slices **not** depending on `BR-1.12`; `B-1`…`B-5` remain **engineering** questions, **not** business decisions, and **must not be auto-converted into tickets** |
| **6** | **Session 1 boundary** — owns independent verification; its Master Matrix **not read, classified, consumed or modified** by Session 2, and its `SD-4` evidence consumable **only** under a separate PO/ARB decision |

### 6.1 Artifact roles — boundaries to preserve

| Artifact | Purpose |
|---|---|
| **Election Constitution** | constitutional / sovereign constraints |
| **Election Manifesto** | canonical Election **business rules** — ⚠️ **does not exist; candidate only, pending `D-MANIFEST`** |
| **ADR** | **architectural** decisions — *not* a home for every Election business rule |
| **Review documents** *(including all of Session 2's)* | investigation evidence and historical reasoning — **must not become business-rule authority** |
| **Session 3 code** | implementation |
| **Session 1 Master Matrix** | verification evidence |

**The candidate architecture — `Election Constitution → Election Manifesto → Election domain/application → Tests` — is recorded as a CANDIDATE and is NOT treated as adopted.** Adoption is `D-MANIFEST`.

### 6.2 The two suspension workflows — distinct, and neither implemented by Session 2

| | Workflow |
|---|---|
| **A** | **Election Chief** → `ElectionMembership` suspension — **both modes** |
| **B** | **Organisation** *(Organisation Chief)* → Organisation Membership removal → **automatic** `ElectionMembership` suspension — **Full Membership Mode only** |

**Not to be combined into one generic suspension business rule. Neither is implemented, and `BR-1.13` blocks workflow A's slice only — not admission.**

### 6.3 Recommended immediate next action

> **Decide `BR-1.12`, and `D-MANIFEST` alongside it.**
>
> **`D-MANIFEST` is not merely parallel housekeeping for this slice:** the admission rule needs a canonical home **before** Session 3's first test encodes it. **Sequence: `BR-1.12` decided → rule canonicalised → strict TDD.**
> **Session 3** may proceed meanwhile on authorised Election-Only slices that do not depend on `BR-1.12`.
> **Session 1** continues independent verification, unblocked.

**STOP. The next action belongs to the Product Owner / ARB.**

**Traceability (pointers only — no rules restated):** [`admission gate`](2026-08-12-election-only-admission-governance-gate.md) · [`Session-3 handover`](2026-08-12-handover-session2-to-session3-election-only-admission.md) · [`Election-Only baseline`](2026-08-12-election-only-first-governance-baseline.md) · [`readiness report`](2026-08-12-session-3-governance-readiness-report.md) · [`hierarchy refinement`](2026-08-12-arb-package-membership-hierarchy-refinement.md) · [`ADR-002 v2 proposal`](2026-08-12-adr-002-amendment-proposal-v2-from-adopted-d-ent-1.md) · [`decision register`](2026-08-12-election-governance-decision-register.md) · [`Officer Guide analysis`](2026-08-12-officer-guide-governance-source-analysis.md) · [`Session-1 handover`](2026-08-12-governance-handover-session2-to-session1.md) · `PBDIGIT-68`
