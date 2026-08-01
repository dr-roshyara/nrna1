# WP-7C — Engineering Readiness and Execution Contract

**Date:** 2026-08-01 · **Prepared by:** Engineering AI, under Principal Architect discipline
**Classification:** **Work Plan** — a pre-approval execution-state artifact. **Role: Runtime** (ES-004.3), therefore the **runtime mount** (ES-005.1), not a documentation root.
**Note:** at EP-01 approval its content is promoted into the governed WP-7 Engineering Plan (ES-004.2, Plan Concept Decision Paper). **It exists beside `WP-7-retention-alignment.md`, not instead of it.**
**Status:** ⏸️ **AWAITING AUTHORIZATION — work plan FROZEN.** No further architectural, governance or methodology change until authorization issues. **Slice 7C is unauthorized. No implementation has begun.**

> **Correction carried forward.** My earlier statement *"WP-8 is undefined"* overreached. **The evidence supports only: no accepted artifact defining WP-8 was found in the sources examined** (the WP-7 plan, the backlog, CONTEXT). **Absence in searched artifacts is not non-existence** — and the narrower claim is the one the evidence supports. **Consequence, also narrowed: if WP-8 is to become the next authorized work package, it should be defined before authorization. It is not a blocker today.**

---

## Phase 1 — Product Baseline

*From accepted artifacts only.*

| | |
|---|---|
| **Current work package** | **WP-7 — Retention Alignment** (EPIC-004): *`audit:cleanup` becomes EPW-aware* |
| **Slice status** | **7A ✅ accepted · 7B ✅ accepted and closed (R-59) · 7C ⬜ unauthorized** |
| **Authorized implementation boundary** | **None. No engineering activity is currently authorized.** The *proposed* boundary is the deletion guard inside `AuditCleanup`, consuming the Evidence Preservation Window Resolution capability |
| **Remaining governance prerequisite** | **PS-11 only** — C-1 authorization wording · C-2 release governance · C-3 amendment of accepted tests · C-4 identifier allocation |
| **First engineering activity after authorization** | **RED for Slice 7C** |

**Nothing else gates the product.** Nineteen other programme findings are open and none touches a product code path — `composer merge-gate` passes with all of them outstanding.

## Phase 2 — Engineering Readiness Checklist

### Entry criteria

- [ ] **Slice 7C authorization issued** (C-1…C-4 disposed)
- [x] **Merge gate green** — `composer merge-gate` PASS; 266 tests · 665 assertions · 0 failures
- [x] **Working tree clean**
- [x] **7B artifacts present** — window VO · resolution service · durations port · 4 test files
- [x] **Baseline recorded** — so RED is distinguishable from pre-existing state

### Required tests — the approved list, verbatim

| # | Test |
|---|---|
| 1 | **open window ⇒ retained** |
| 2 | **closed window ⇒ deleted** |
| 3 | **unmappable folder ⇒ retained** |
| 4 | **`--days` no longer overrides the invariant** |

**Plus, gated on C-3:** amend `tests/Feature/Audit/AuditCleanupTest.php`, whose fixtures create directories with no `Election` records and therefore assert deletion where the approved criterion requires retention. **Amendment limited to what the criterion requires.**

### Architectural invariants engineering must preserve

| Invariant | Source |
|---|---|
| **No duration is defined, defaulted, or clamped by WP-7** — fail closed instead | **AP-1** |
| **MAD has exactly one home; it is not copied into a retention config** | **AP-2** |
| **No context crossing introduced** — no event added, changed, or retired | frozen contract · TP-1 |
| **Artifact A only** — the Election Event Journal (`logs/audit/`); artifacts B and C are different concepts | domain boundary commission |
| **The VO takes business values only** — never a port, config, model, or clock; `is-open-at-T` takes the instant as an argument | construction commission |
| **The absent-anchor fallback lives in the application service, not the VO** | P7B-2 |
| **Folder→election resolution, traversal, deletion and the CLI stay ungated** — they carry no policy | placement principle |

### Expected RED scope

**4 new tests · 1 existing test file amended · 0 new ports · 0 new domain terms.**

### Verification gates

`composer merge-gate` — Architecture fitness · Deptrac · greenfield PHPStan · widened regression · **plus** developer guide (Definition of Done) and the cross-slice operational record.

## Phase 3 — DDD Protection Plan

*Protection, not redesign. Every row is an existing decision restated so implementation cannot drift from it.*

### Strategic

| | |
|---|---|
| **Owning bounded context / capability owner** | **Audit / Retention** — consuming the Evidence Preservation Window Resolution capability (Election). **No ownership moves.** |
| **Business capability** | **prevent deletion of constitutionally retained audit evidence** — *evidence is not deleted while it is still constitutionally required* |
| **Owning bounded context** | **Audit / Retention owns the guard.** Adjudication and Q-2 are **providers of parameters**, not participants |
| **Capability ownership** | **WP-7 owns one thing: the deletion guard.** It defines no duration and creates no crossing |
| **Ubiquitous language** | **Unchanged.** Verified: no new domain term, no rename, no changed meaning. *"Published language — untouched"* |
| **Strategic invariants** | **Policy 2 Retention Invariant** — *evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved* · **EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin**, attached to the election instance |

### Tactical

| Element | Position |
|---|---|
| **Aggregates** | **None introduced.** 7C adds no aggregate and no entity |
| **Value object** | `EvidencePreservationWindow` — private constructor + `forElection()` factory; `closesAt()`, `isOpenAt()`. **Unchanged by 7C** |
| **Application service** | `ResolvesEvidencePreservationWindow` — owns the absent-anchor fallback; exposes `isOpenFor()`. **Unchanged by 7C; consumed only** |
| **Ports** | `EvidencePreservationDurations` — **Election's own** port (R-44). **7C adds none** |
| **Repositories** | **None.** The election is read Infrastructure-side |
| **Dependency direction** | **Infrastructure → Application → Domain.** `AuditCleanup` (Infrastructure) calls the application service, which uses the domain VO. **Never reversed** |
| **Infrastructure boundary** | `AuditCleanup` · the folder→election parser · `ElectionAuditService`'s layout `{slug}_{Ymd}_{Hi}`. **Ungated by design — they carry no policy** |

## Phase 4 — Engineering Execution Contract

| Engineering **may** change | Engineering **must not** change |
|---|---|
| `app/Console/Commands/AuditCleanup.php` — add the guard | `EvidencePreservationWindow` · `ResolvesEvidencePreservationWindow` · `EvidencePreservationDurations` |
| `tests/Feature/Audit/AuditCleanupTest.php` — **only as the approved criterion requires**, gated on C-3 | Config keys and any duration value |
| New 7C tests | The audit folder layout |
| The developer guide for this area | **How deletion is performed** — traversal, filesystem removal, reporting |
| | Anything in the Adjudication context |

| Requires **governance** | Requires **architecture review** |
|---|---|
| Release (announcement owner) — C-2 | Any new context crossing |
| Amending accepted tests — C-3 | Any new domain term or renamed concept |
| Defining, defaulting, or clamping any duration | Any change to the capability's consumed surface |
| Any new port | Any new aggregate, entity, or repository |

**If engineering finds it needs anything in the right-hand columns, it stops and refers.** **The slice was verified to need none of them.**

## Phase 5 — Post-Implementation Evidence Plan

**Defines how the work will be evaluated after ACCEPT. It promotes nothing and predicts no outcome.**

| Outcome | Criterion |
|---|---|
| **No reusable knowledge** | **The default.** The slice implemented an approved design without revealing anything general. **Most slices land here, and recording that is a result, not a failure** |
| **PKS observation** | The work revealed something about **how engineering knowledge behaves** — a gap, a recurrence, a measurement — as distinct from something about the product |
| **Repeated operational evidence** | The slice is the **Nth** occurrence of an already-recorded pattern. **Recurrence is the trigger, not novelty** |
| **KnowledgeOS candidate** | The lesson is **cross-product** — adoptable unchanged by another project. **One corpus is one observation: promotion needs another repository to produce the same finding** |

**Evaluation happens once, at ACCEPT, against these four criteria.** **A candidate that is cross-product and unqualified will resolve to `PENDING` under the placement rule** — the unruled classification that has already arisen three times. **If it does, it is recorded inside its evidence artifact, not given an invented home.**

---

## Governance Closure — which decisions actually gate ENGINEERING

**Narrowed strictly to engineering blockers. No architecture reopened, no new governance question introduced.**

| | Decision | Gates RED? | Why |
|---|---|---|---|
| **C-1** | authorization wording | ✅ **YES** | **The authorization cannot issue without its text, and engineering cannot begin without the authorization.** Its wording also fixes what engineering may change |
| **C-3** | does authorization cover amending accepted tests? | ✅ **YES** | **RED includes amending `AuditCleanupTest`.** Until this is settled, engineering does not know whether it may touch that file — **it constrains RED's scope directly** |
| **C-4** | identifier allocation | ⚠️ **transitively** | A ruling needs an identifier to exist as a ruling, so it gates the **issuing act**. **It constrains nothing about engineering's scope and is trivially satisfiable** |
| **C-2** | release governance | ❌ **NO** | **Release follows ACCEPT.** Naming an announcement owner is a **pre-release** prerequisite, not a pre-engineering one. **It does not gate RED, GREEN, VERIFY or ACCEPT** |

> ### The critical path is three decisions, not four.
>
> **C-1 and C-3 gate engineering. C-4 gates the paperwork of issuing. C-2 gates release, which is downstream of everything in this slice.**
>
> **Whether to defer C-2 is the ARB's call, not architecture's.** **What is stated here is only the classification: C-2 is not an engineering blocker.**

## Authorization Readiness Statement

**Every architectural prerequisite is satisfied. Verified, not assumed.**

| Prerequisite | Status | Evidence |
|---|---|---|
| **Bounded-context ownership** | ✅ **Audit / Retention owns the guard.** Adjudication and Q-2 are **providers of parameters**; the consumer is the guard in `AuditCleanup`. **No crossing, no consumer, no producer added** | plan §1, §3 |
| **Capability ownership** | ✅ **WP-7 owns one thing: the deletion guard.** 7C consumes the **Evidence Preservation Window Resolution** capability and no other | plan §1 · Rev 2 verification |
| **Strategic invariants** | ✅ **Policy 2 Retention Invariant · EPW = CW + MAD + LSM** — preserved, not touched. **Published and Ubiquitous Language unchanged** | plan §1 · A5 |
| **Tactical boundaries** | ✅ **No aggregate, entity, repository or port introduced.** VO · service · port **consumed only**. Dependency direction Infrastructure → Application → Domain, **never reversed** | Phase 3 above |
| **Engineering readiness** | ✅ merge gate **PASS** (266 tests · 665 assertions · 0 failures) · working tree clean · 7B artifacts present · RED scope known | Rev 2 verification |
| **Authorization** | ⬜ **ABSENT** | C-1, C-3 undisposed |

> **Architecture does not block Slice 7C. The sole remaining prerequisite is governance, and it is two decisions on the critical path.**

**Nothing was redesigned to produce this statement.**

## ARB Authorization Package — minimum required to dispose C-1 and C-3

**Architecture has finished speaking. This is its input to governance; the decisions are the ARB's.**

### C-1 · authorization wording

> **Architecture's requirement: the authorization wording must not invalidate the already-approved acceptance criteria** — *nothing inside an open EPW is deleted* and *`--days` no longer overrides the invariant*.

**The wording is governance's to draft.**

### C-3 · amending accepted tests

> **Architecture's finding: the amendment is unavoidable.** `AuditCleanupTest` creates directories with no `Election` records, which the approved criterion requires to be **retained**, while three of its methods assert deletion.

**Whether the authorization covers it explicitly, implicitly, or by separate review is governance's to decide.**

### Ready to issue — two slots, both governance's

> **Slice 7C is authorized to implement the decision service that determines whether evidence may be deleted**, consuming only the **Evidence Preservation Window Resolution** capability.
>
> **⟨C-1 wording⟩** · **⟨C-3 disposition on amending `tests/Feature/Audit/AuditCleanupTest.php`⟩**
>
> **Slice 7C shall not absorb, implement or anticipate WP-7B-R1** (independent under R-60).
>
> **Authorization covers implementation and acceptance only; release requires a named announcement owner (C-2, off the engineering critical path).**
>
> **Sequence: RED → GREEN → VERIFY → ACCEPT. WP-7 closes on acceptance.**

**C-4 is needed only to record the ruling under a non-reused identifier.**

## RED Backlog — prepared, NOT executed

**No test written · no code written · no production source modified.**

| | |
|---|---|
| **Business capability** | *audit evidence is not deleted while it is still constitutionally required* |
| **Acceptance criterion** | nothing inside an open EPW is deleted · deletion resumes after closure · an unresolvable folder→election mapping is not deleted |
| **Required behaviour** | `audit:cleanup` consults each folder's election's preservation window and **refuses deletion while that window is open**; the age cutoff can no longer override the invariant |
| **Tests** | the four below express that behaviour — they follow from it, they do not define it |

### New failing tests (4) — the approved list

| # | Test | Intent |
|---|---|---|
| 1 | open window ⇒ **retained** | a folder whose election's EPW is open survives cleanup |
| 2 | closed window ⇒ **deleted** | the guard releases once the window closes — it is a guard, not a freeze |
| 3 | unmappable folder ⇒ **retained** | fail closed when folder→election resolution yields nothing |
| 4 | **`--days` no longer overrides the invariant** | an age cutoff cannot delete inside an open window |

### Existing tests requiring authorized amendment (gated on C-3)

**`tests/Feature/Audit/AuditCleanupTest.php`** — six methods; **fixtures create bare directories with no `Election` records.**

| Method | Expected effect |
|---|---|
| `test_it_deletes_folders_older_than_specified_days` | **will fail** — asserts deletion of an unmappable folder |
| `test_it_respects_custom_retention_days` | **will fail** — same |
| `test_it_reports_deletion_count` | **will fail** — same |
| `test_it_keeps_folders_within_retention_window` | **expected to pass** — asserts retention |
| `test_it_handles_empty_audit_directory` | **expected to pass** — no folders |
| `test_it_handles_nonexistent_audit_directory` | **expected to pass** — no directory |

**Amendment limited to giving the three deletion-asserting fixtures a resolvable election with a closed window.** **No assertion is weakened; no test is deleted.**

### Implementation boundary

**`app/Console/Commands/AuditCleanup.php` only.** Folder traversal, filesystem removal and reporting unchanged — the guard gates the **decision**.

### Acceptance criteria

**Nothing inside an open EPW is deleted · deletion resumes after closure · an unresolvable folder→election mapping is not deleted · `composer merge-gate` green · developer guide updated.**

## Architectural Handover — issued 2026-08-01

> **Architectural verification is complete for WP-7C. No further architectural refinement is warranted unless implementation exposes a genuine deficiency. The architecture has fulfilled its role for this work package; the next meaningful artifacts come from engineering execution, and any future architectural evolution is driven by the operational evidence that execution produces.**

**Standing constraint from here:** *do not propose architectural refinements unless implementation exposes new evidence that the current architecture is insufficient.* **Already binding via ES-002.1** (only a NO carrying implementation evidence of insufficiency opens an ADR/ARB discussion) **and the methodology freeze**; recorded here as the handover's terms, not as a third home.

**Expected identifiers, subject to ARB issuance:** authorization **R-65** · acceptance **R-66**. **Neither exists yet; authorization has not been issued.**

### Protected boundaries — engineering stops and refers if any is touched

| Boundary | Position |
|---|---|
| Deletion decisions | **Audit / Retention owns the guard** |
| EPW Resolution capability | **Election owns it** — consumed, not modified |
| Adjudication | **unchanged** — supplies MAD as configuration |
| Bounded-context ownership · capability ownership | **no change** |
| Tactical model | **no change** — VO · service · port consumed only |

**The slice was verified to need none of them.**

## Standing position

**Slice 7C is architecturally ready and governance-blocked. The first authorized activity is RED, and it begins only after C-1…C-4 are disposed and authorization issues.** **No implementation has been performed.**

---

**Traceability:** `.claude/plans/WP-7-retention-alignment.md` (capability · ownership · slice definition · invariants · placement principle) · **R-59** (7B accepted) · **R-60** (WP-7B-R1 open) · **R-44** (Election's own port) · **AP-1 · AP-2** (WP-6 findings) · **Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) · `2026-08-01-slice-7c-preauthorization-verification.md` Rev 2 · `2026-08-01-slice-7c-arb-disposition-package.md` (C-1…C-4) · `2026-08-01-programme-state-convergence-package.md` (PS-11 the sole product blocker) · `composer merge-gate` (PASS). **No implementation · no architecture reopened · no governance artifact produced · Slice 7C unauthorized.**
