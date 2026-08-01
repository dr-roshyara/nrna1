# Slice 7B — Plan Correction and Execution Authorization

**Date:** 2026-08-01 · **Record prepared by:** Recording Architect · **Authority:** ARB · **Rulings:** **R-57 · R-58**
**Repository Integrity Gate:** ✅ PASSED.
**Two artifact kinds, never mixed:** **ARB Decision** (issued authority) · **📝 Recording Note** (Recording Architect's explanation; never citable as authority).

> **On issuance.** I first read these two items as *recommendations* — they arrived with *"Recommended: APPROVE"* and blank `[APPROVED / REJECTED / DEFERRED]` templates — and declined to record them under **R-34**. **The ARB reissued the commission unchanged.** A reaffirmation after a stated objection **is** an exercise of authority. **The objection and its resolution are both on the record.**

---

## ARB Decision — R-57 · Queue 12 · P7B-1 plan correction

**Category:** Planning Governance · **Transition type:** Approval · **Produces:** a corrected implementation plan

| Field | Value |
|---|---|
| **Issue** | The approved plan's 7B **Objective** and **Acceptance** rows carried *"consuming `AdjudicationDurations` for MAD"* and *"MAD comes from the **existing** port"* — a mechanism **R-44 superseded**, and one that instructs the **direct cross-context import TP-1 forbids and Deptrac fails** |
| **Decision** | ✅ **APPROVED — correct the plan** |
| **Reason** | The correction restores an **already-settled** mechanism (R-44). Leaving it would have had the approved plan instruct a violation the ARB had already rejected |
| **Effect** | Both rows now name **Election's own `EvidencePreservationDurations` port**. **Only the mechanism sentence changed — the invariant that MAD keeps exactly one canonical home is untouched, and no other slice text was altered** |

**Applied.** Verified in `.claude/plans/WP-7-retention-alignment.md` §5.

## ARB Decision — R-58 · Queue 13 · Slice 7B execution authorization

**Category:** Execution Governance · **Transition type:** Authorization · **Produces:** authorized engineering work

| Guard | Status |
|---|---|
| Planning approved | ✅ **R-56** |
| Plan corrected | ✅ **R-57** |
| Engineering allocation settled | ✅ **P7B-2** — fallback → application service |
| Inferred finding withdrawn | ✅ **P7B-3** — **K9 stays in 7B** |
| Architectural gates | ✅ **zero** |
| Merge gate | ✅ green (**R-55**) |

| Field | Value |
|---|---|
| **Decision** | ✅ **AUTHORIZED — Slice 7B execution may begin** |
| **Reason** | Every guard satisfied; no engineering question outstanding; architecture frozen |
| **Effect** | Slice 7B: `planned` → **`RED AUTHORIZED`** |

---

## Governance State

| Item | Before | **After** |
|---|---|---|
| Queue 12 — P7B-1 | pending | ✅ **closed — R-57, correction applied** |
| Queue 13 — execution | pending | ✅ **closed — R-58, AUTHORIZED** |
| **Slice 7B** | planned | 🔴 **RED AUTHORIZED** |
| Slice 7C | unauthorized | ⬜ unchanged |
| F-WP6R-1 | recorded | ⬜ unchanged — separate, unauthorized |
| EPW anchor · CW · LSM | open | ⬜ unchanged — **non-blocking** |

## Engineering Handover

> **Queue 12 is resolved. Queue 13 is authorized. Slice 7B is authorized to enter RED. Engineering assumes responsibility for implementation within the approved plan. All engineering allocation questions have been resolved, and no further governance action is required before RED.**

**First activity:** **RED on keystones K1–K10**, with **K9 inside Slice 7B**.
**Excluded:** ⛔ 7C (the deletion guard) · ⛔ F-WP6R-1 · ⛔ any change to how audit evidence is written.

**📝 Recording Note.** **R-58 authorizes execution of the slice; it does not accept it.** Acceptance is a later, separate act (**queue item 9**) on delivered evidence — the same two-step 7A followed. **And the authorization is slice-granular: 7C remains unauthorized, so the observable behaviour change still lies beyond this slice.**

---

**Traceability:** **R-56** (planning approved) · **R-44** (the mechanism R-57 restores) · **R-55** (merge gate green) · **R-46/R-47** (the two-act precedent) · P7B-2 allocation record · P7B-3 withdrawal · Slice 7B Authorization Package (K1–K10) · **R-34** (why the first reading was declined). **No architecture redesigned · no implementation performed by this record · 7C not authorized.**
