# EPIC-001 Retrospective — Record of Rulings (2026-07-11)

**Kind:** retrospective record (ruling session, NOT design). **Inputs:** `EPIC-001_Retrospective_Input.md` (12 observations · 7 promotion candidates · 3 deletion candidates) · `../architecture/design/EPIC-002_Context_Dependency_Map_Draft.md`.
**Authority:** conducted under the ARB's delegated instruction (2026-07-11 refined prompt, Step 1); every ruling below is evidence-cited and subject to ARB confirmation on review of this record. Promotion ladder enforced throughout: *observation → repeated observation → practice → candidate standard → approved standard* — insufficient evidence ⇒ explicit deferral, never approval-by-momentum.

---

## 1. Rulings on promotion candidates

| # | Candidate | RULING | Evidence basis (ladder position) |
|---|-----------|--------|----------------------------------|
| P-1 | *"Quality gates are permitted to reveal latent defects. They are not responsible for introducing them."* | **PROMOTED — approved engineering principle.** Rule text to live ONCE in `Implementation_Process_v1.1_Draft.md` (scheduled follow-up, not edited in this step). | 4 independent instances: 7A (Deptrac exposed config-encoding issue) · 7C (Infection exposed latent PHPUnit-9 coverage schema) · F-7D-1 (gate exposed Composer timeout) · F-7D-2 (validation exposed untrustworthy measurement model). Repeated observation, already load-bearing in ARB rulings. |
| P-2 | Evidence-Preconditions checklist for long-running experiments | **DEFERRED** (explicitly — not approved, not rejected). Re-evaluate at the next retrospective after a second independent use. | Single observation (F-7D-2 execution contract). One successful experiment ≠ candidacy. |
| P-3 | *"The platform owns orchestration, not the third-party tool."* | **PROMOTED — approved engineering principle** (same landing path as P-1). | 3 independent committed instances: two-step Infection invocation (IDD §2c-iii) · stable-interface R3 owning invocation semantics (7D) · CI workflows containing zero gate logic (7E). |
| P-4 | EP-01-Light form (Objective · Classification · Risk · Files · Expected evidence · Not changing) | **ADOPTED as working practice** (one rung: repeated observation → practice). Standard-hood re-evaluated at the next retrospective. | Used twice, both ARB-approved without rework (F-7D-2 experiment resume · threads repair). Already operative via `.claude/MEMORY.md` standing rule. |
| P-5 | PASS-AFTER-CORRECTION qualification lifecycle (F-…/CR-…/OQ-… separation) | **DEFERRED for the product track** — adopt on first product-track qualification that encounters an in-run finding (trigger-based adoption), not before. | Approved on the engineering-platform track (OQ records); product-track uses: zero. |
| P-6 | Documentation-consolidation rule: **IDD = implementation decisions · ADR = architectural decisions · retrospective = lessons · CONTEXT = current state only** | **ADOPTED as the documentation rule.** The consolidation *sweep* is scheduled as Stream B documentation work (dependency map §6) — an EPIC-000 work item, not executed inside this ruling session. | ARB-directed at PB-007 closure; evidence O-10 (rulings duplicated across four record types). |
| P-7 | Measurement-reporting convention: a quality/mutation number is always reported **with its execution model** | **ADOPTED as practice, scoped to mutation/quality metrics** (records existing reality: the format is already in use in IDD §2c/§2e, ENG-004, PROGRAM_STATUS). Generalization to all engineering measurements **deferred** (single originating incident). | One originating finding (F-7D-2), format repeated across 4 documents. |

## 2. Rulings on deletion candidates (deletion goal: ≥1 removal = success)

| # | Candidate | RULING | Basis |
|---|-----------|--------|-------|
| D-1 | Per-ticket estimated-WBS mechanics (`(est.)` counts, 103-item style) | **DELETED** — retired for future epics. Tracking method going forward = what EPIC-001 actually used: IDD slices + session-log gate records + closure rulings. Process-text change recorded as a PI item for `Implementation_Process_v1.1_Draft.md`. | O-9: four consecutive tickets tracked by slices; estimates never matched reality; the sync had to supersede them anyway. |
| D-2 | Unused hooks/scripts | **RESOLVED BY REFERENCE — no separate deletion here.** Inventory finding: the one known-broken asset (AST-008 plan-renamer: documented broken `jq` shim, fires only on PlanCreate, contradicts the descriptive-filename convention) already has an owner — platform-track slice **C2 (unwire + deprecate)**, re-sequenced post-qualification (R-25). Double ownership is refused. All other hooks show positive usage evidence during EPIC-001 (dev-guide reminder and discipline tripwire fired and were heeded). | AIP plan C2 record · session-log usage evidence. |
| D-3 | Gate-work mirrored as parallel debt rows (the F-1/F-2 pattern) | **DELETED — pattern retired.** Capability work is tracked as tickets only; the debt table is reserved for genuine defects/debt and never mirrors planned ticket scope. PI item for v1.1 draft. | O-8: F-1/F-2 duplicated PB-007's scope on two boards and went stale independently. |

**Deletion goal: SATISFIED** (two patterns retired: D-1, D-3).

## 3. Standing questions — answered from evidence

- **Used (and earned their keep):** IDD-per-ticket with ARB freeze · RED-first TDD · fitness suites · the review loop (propose → refine → freeze) · session logs as reconstruction source · evidence labels (Observed/Derived/Interpreted) · EP-01-Light (late arrival, immediately useful).
- **Unused / broken:** per-ticket WBS trackers after PB-003 (→ D-1) · AST-008 (→ C2).
- **Hurt:** the shared test database (cross-session race O-6 AND the F-7D-2 false-kill mechanism — one root cause, two symptoms) · boards drifting 5 closures behind implementation (O-8) · ruling duplication across record types (O-10 → P-6).
- **Simplified development:** the stable gate interface (one command) · owner-hosts-the-guard · opaque refs (ADR-T16) — which made migration sequencing a value decision instead of a topological constraint (dependency map, load-bearing observation).
- **Surprised us:** F-7D-2 — the attractive number was the artifact (Test Strength 96→65) · `--threads=max` silently degrading to 1 · the first-ever full-suite run exposing legacy debt invisible to per-slice gates (O-11).

## 4. Scheduling outcomes (recorded for Step 2 board reflection — no new design)

1. **Stream B documentation consolidation** (P-6 sweep: stale c4 · superseded tables · ruling de-duplication) — new EPIC-000 item, scheduled after formal closure.
2. **F-7C-1..3** (dead test class + 2 non-parsing scaffold files) — small EPIC-000 cleanup batch; **F-7C-4/5** (legacy suite debt) — EPIC-006 territory; **F-7C-6** (57 risky) — remains recorded, framework-level.
3. **ENG-004** (Mutation Ratchet 1, TEST_TOKEN sub-item) — stays backlog; earliest sensible slot is after EPIC-002 Strategic Discovery.
4. **First real CI run** — at next push (PB-007 §5 open item). **Deployment doc** — precondition for any production milestone.
5. **Architecture Gap Analysis** as repeatable per-BC activity (map §6) — noted as a process candidate; enters v1.1 draft discussion with the PI items above.

## 5. Formal closure statement

> **EPIC-001 (Greenfield Core) is formally closed as of 2026-07-11**, subject to ARB confirmation on review of this record. Implementation: 7/7 tickets closed with executed evidence (correction loop end-to-end over the real path · blocking merge gate PASS and wired into CI · validated mutation baseline). Qualifications: Architecture · DDD · Trustworthiness · EP-02 — all passed on executed evidence (PB-007 IDD §6). Retrospective: conducted, all candidates ruled (2 promoted · 3 adopted as practice/rule · 2 deferred explicitly · 2 patterns deleted · 1 resolved by reference), deletion goal satisfied. **Reopen conditions:** defects · compatibility fixes · retrospective promotions — nothing else. Board propagation of this closure is Step 2.

---
*Next step (after review of this record): **Step 2 — Formal EPIC-001 Closure** (board propagation only). EPIC-002 does not begin in either step.*
