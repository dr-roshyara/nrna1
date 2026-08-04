# Program Status — Health Dashboard & One-Page Report

**Date:** 2026-08-04 · **Audience:** first thing anyone opens · detail: `backlog/BACKLOG.md` · runtime state: `.claude/CONTEXT.md` · rulings: `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`

> ## Where we are now, in one sentence
>
> **EPIC-001 is closed; EPIC-004 (Adjudication tactical) has delivered and had accepted everything it was authorized to build; the WP-4 engineering commission is CLOSED and ARCHIVED (`R-98`); and the programme is now GOVERNANCE-DRIVEN — the next act belongs to the Contestation Domain Owner, the Board, or Execution Governance, not to engineering.**

**Engineering is in REPOSITORY STEWARDSHIP MODE — for the CURRENT AUTHORIZED SCOPE, not permanently.** It may explain evidence, maintain traceability, correct factual defects and preserve repository integrity. **It may not continue discovery, extend modelling, invent governance, or implement without authorization.** **When a new implementation commission is authorized, engineering leaves this mode legitimately — stewardship is a state of the current scope, not a standing constraint on engineering.**

---

## Program Health Dashboard

```text
Architecture        100%   (Blueprint v1.0 FROZEN; Deptrac    Current Ticket    none — NO engineering
                            fail-mode 0 violations)                              commission is open (R-98)
Research            100%   (EPIC-001 evidence chain complete)  Current Phase     EPIC-004 governance
DDD Discovery       100%   (greenfield core: 3 contexts                          disposition
                            mature + Shared platform)          Current Risk      LOW (engineering)
Implementation      EPIC-001 CLOSED (2026-07-11)                                 MEDIUM (one unrepaired
                    EPIC-004 delivered to its authorized                          transaction boundary)
                    scope — see the ruling table below         Blocked           NO for governance
Quality Gates       100%   wired (merge gate PASS · CI                           YES for tactical work
                            workflows · mutation = measured,                      (awaits Q1–Q4)
                            non-blocking, validated baseline)  Next Milestone    Domain Owner answers
Documentation       guides current through guide 08                                Q1–Q4 (WP-4C-2)
Technical Debt      AD-006 open · one UNREPAIRED defect
                    recorded, unauthorized to fix (D-1..D-4)
```

*(Derived, not estimated: Implementation state = closure/acceptance rulings · Quality Gates = wired gate types / required (7/7) · Debt = closed / raised. **`EPIC-004` is deliberately NOT given a percentage** — its remaining work is gated on a business decision, so a completion figure would imply an engineering denominator that does not exist.)*

---

## Governance state — every open item has exactly one owner

| Item | State | Ruling | Next owner |
|---|---|---|---|
| **WP-4B** (conclude→issue seam) | **ACCEPTED** | `R-87` | — |
| **WP-4C-1** (`AdjudicationFailureDeclared`) | **ACCEPTED** — D1+D2 only | `R-93` | — |
| **Seam-wiring constitutional review** | **ADOPTED** — ⚠️ adopting the *review* is **not** authorizing the *repair* | `R-92` | — |
| **PB-006 programme reporting** | **ANNOTATED** at `IMPLEMENTATION_PROGRESS.md:58` | `R-94` | — |
| **WP-4 engineering commission** | **CLOSED · ARCHIVED** | `R-98` | — |
| **WP-4C-2** (Q1–Q4) | **AWAITING BUSINESS DECISION** — engineering must **not** answer | `R-90`·`R-97` | **Contestation Domain Owner** |
| **Signal classification (ENG-012) · `enforceHorizon()` (ER-08)** | **PLAN authorized · implement NOT** | `R-95` | Execution Governance |
| **Transaction-boundary repair** | **UNAUTHORIZED — wire no production caller ahead of it** | `R-91` held · D-1…D-4 | Decision Authority |
| **D3** (catalog) · **D4** (publication call site) | **HELD** | `R-97` | Board — **E1** · **E2** |
| **COL-5a mechanism** | **RE-ANCHORED to COL-1** — accepted architecture subsumes it; owner **vacated**, no strategic-architecture backlog exists | `R-96` (corrected) | governance must name a home |
| **WP-8** | **DEFERRED** — do not reopen because nearby work closed | `R-79` | — |
| **`Engineering Verification Framework v1.x`** | **✅ CLOSED · EFFECTIVE** — referent named 2026-08-04; extent = the `engineering/verification/` tree (145 artifacts). **Future audits are USES of it, not work on it.** ⚠️ **No specification document defines it** — ‘v1.x’ is the practice as exercised, and that gap is named, not filled | `R-99` | — |
| **Unpushed local commits** | **MEASURE, NEVER CARRY:** `git rev-list --count @{u}..HEAD`. **Operational, not a governance gate.** *(A literal count was recorded here and was stale within one commit — removed, since this document's rule is that every figure is derived.)* | — | repository operator |

---

## The four decision gates — engineering resumes on one of these, and on nothing else

| | Event | What it opens |
|---|---|---|
| **A** | Domain Owner supplies **Q1–Q4** | record verbatim · classify · verify · produce a verification report |
| **B** | ARB authorizes a **new WP-4C-2 Tactical DDD Commission** | modelling, under a fresh mandate |
| **C** | Execution Governance authorizes **implementation** | code for ENG-012 / ER-08, after a reviewed plan |
| **D** | Board releases **D3 / D4** (E1 / E2) | the carried WP-4C-1 deliverables |

**If none occurs: stop, preserve repository integrity, answer factual questions only.**

---

## What happens after Q1–Q4 — and why it is not "then engineering codes it"

```
Q1–Q4 answered (Domain Owner)
        ↓
ARB reviews the evidence — is uncertainty sufficiently reduced?
        ↓
ARB authorizes Tactical DDD
        ↓
NEW WP-4C-2 Tactical DDD Commission  ← a fresh mandate, NOT a continuation of discovery
        ↓
model: responsibilities · aggregate boundaries · states · transitions · invariants · events
        ↓
Tactical DDD accepted
        ↓
ARB authorizes Implementation  →  Implementation Commission opens
```

**The rule this sequence protects (adopted, `R-100`):** discovery, business clarification, tactical modelling and implementation must never become one continuous activity. **Without an explicit tactical stage the business decision goes straight to code, and the model becomes implied rather than explicit — which is how code and model drift apart.**

**Tactical DDD's own obligations are NOT restated here.** Canonical home: `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` (ADOPTED, ARB 2026-07-26) · PublicDigit binding: `docs/architecture/governance/DDD_PRINCIPLES.md`. **The methodology is FROZEN (2026-08-01) — this section is a pointer, and adds no principle.**

**The specific modelling questions WP-4C-2 will face** (recorded as forward scope, decided by nobody yet): which state the `Challenge` enters · whether that destination is an existing state or a new one · which event announces the transfer of responsibility · which invariants the `Challenge` must maintain · how Contestation consumes the event. **These are modelling decisions, and each of them presupposes an answer to Q1–Q4.**

---

## Milestones and burn-up

```text
Milestones             M1 ✔ (2026-07-07) · M2 ✔ (2026-07-09) · M3 ✔ (2026-07-10)
                       EPIC-001 formally CLOSED ✔ (2026-07-11, explicit ARB decision)
Current branch         feature/pb003  (⚠️ a second agent session commits here concurrently)

Program burn-up (EPIC-001 greenfield core — unchanged, closed)
  Architecture     ██████████████████████████ 100%
  Infrastructure   ██████████████████████████ 100%   (M1: registry+relay+inbox+dispatcher+provenance)
  Domain           ██████████████████████████ 100%   (greenfield core: Contestation·Adjudication·Election)
  Application      ██████████████████████████ 100%   (reactions·handlers·translators — correction loop)
  Integration      ██████████████████████████ 100%   (M3: IT-1..8 over the REAL path · merge gate · CI)
  Migration        ░░░░░░░░░░░░░░░░░░░░░░░░░░   0%   (EPIC-006, by design)

⚠️ SCOPE NOTE on "Integration 100%" — the IT-1..8 evidence is scoped to what PB-006 accepted on
   2026-07-10. Two terminal exits were introduced AFTER that closure (AdjudicationExpired 2026-07-31 ·
   AdjudicationFailureDeclared 2026-08-04) and are OUTSIDE that accepted boundary. PB-006 is neither
   reopened nor re-verified; WP-8 validates the failure-declared branch. (R-94)

Health   Architecture 🟢 · Tests 🟢 · Debt 🟢 · Governance 🟢 · Migration 🟡 (by design) ·
         Production 🟡 (gate+CI wired; no deploy doc) · Research 🟢 ·
         Correction-loop completeness 🟡 — of three terminal outcomes, only DeterminationIssued has a
         production consumer; AdjudicationExpired and AdjudicationFailureDeclared announce to nobody yet

Mutation baseline (validated, F-7D-2)   MSI 50% · Mutation Code Coverage 77% · Test Strength 65%
                                        (8-thread 75%/96% figures REJECTED — failed evidence validation)

Remaining sequence     1) ✔ EPIC-001 retrospective · 2) ✔ EPIC-001 formal closure
                       3) 🔄 EPIC-004 Adjudication tactical — authorized scope DELIVERED and ACCEPTED
                       4) ⏸ WP-4C-2 — awaiting Q1–Q4 (Domain Owner), then a NEW tactical commission
Risks in focus         one unrepaired transaction boundary (unauthorized to fix) · deployment doc
                       outstanding · two terminal events with no production consumer
```

*Rule (unchanged): every number above is derived — closure rulings, gate outputs, or WBS. Nothing hand-estimated. Where a figure would require an invented denominator, **no figure is given**.*
