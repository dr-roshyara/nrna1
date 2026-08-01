# WP-7C — Engineering Readiness and Execution Contract

**Date:** 2026-08-01 · **Prepared by:** Engineering AI, under Principal Architect discipline
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit`
**Status:** readiness artifact. **Slice 7C is unauthorized. No implementation has begun.**

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
| **Business capability** | *audit evidence is not deleted while it is still constitutionally required* |
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

## Standing position

**Slice 7C is architecturally ready and governance-blocked. The first authorized activity is RED, and it begins only after C-1…C-4 are disposed and authorization issues.** **No implementation has been performed.**

---

**Traceability:** `.claude/plans/WP-7-retention-alignment.md` (capability · ownership · slice definition · invariants · placement principle) · **R-59** (7B accepted) · **R-60** (WP-7B-R1 open) · **R-44** (Election's own port) · **AP-1 · AP-2** (WP-6 findings) · **Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) · `2026-08-01-slice-7c-preauthorization-verification.md` Rev 2 · `2026-08-01-slice-7c-arb-disposition-package.md` (C-1…C-4) · `2026-08-01-programme-state-convergence-package.md` (PS-11 the sole product blocker) · `composer merge-gate` (PASS). **No implementation · no architecture reopened · no governance artifact produced · Slice 7C unauthorized.**
