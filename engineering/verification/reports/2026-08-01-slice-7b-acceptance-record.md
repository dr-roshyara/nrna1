# Slice 7B — Acceptance Record

**Date:** 2026-08-01 · **Record prepared by:** Recording Architect · **Authority:** ARB · **Rulings:** **R-59 · R-60**
**Evidence source:** slice 7B RED report · GREEN report · acceptance review (cited, not restated)
**Repository Integrity Gate:** ✅ PASSED.

---

## ARB Decision — R-59 · Slice 7B acceptance

**Category:** Delivery Governance · **Transition type:** Acceptance · **Produces:** an accepted work-package baseline

| Field | Value |
|---|---|
| **Decision** | ✅ **ACCEPTED** |
| **Reason** | Criteria 1–4 satisfied — scope · verification · architectural conformance · Definition of Done |
| **Effect** | Slice 7B **ACCEPTED and CLOSED**; the `EvidencePreservationWindow` enters the **accepted IMPLEMENTATION baseline** *(corrected — see the note below)* |

### Criterion 5 — the interim anchor, recorded as debt rather than treated as a blocker

| Field | Value |
|---|---|
| **Debt** | `anchorOf()`'s ordering — `results_published_at` → `end_date` → `archived_at` — is **executable business behaviour no authority chose** |
| **Class** | **The AP-1 defect class in a new form: an invented *rule*, not an invented *value*** |
| **Owner** | **Q-2** |
| **Resolution** | Q-2's ruling collapses the method to one named field |
| **Why accepted, not blocked** | **The authorization did not prohibit a temporary anchor policy, and acceptance criteria are not redefined retroactively after GREEN** |

**Recorded, not silent. Silent architectural debt is ungoverned debt.**

## ARB Decision — R-60 · WP-7B-R1 opened

**Category:** **Planning Governance** *(corrected — see the note below)* · **Transition type:** Approval · **Produces:** an approved work package

| Field | Value |
|---|---|
| **Decision** | ✅ **OPEN a follow-up work package — WP-7B-R1, Interim Anchor Extraction** |
| **Objective** | Extract the anchor into an **`EvidenceAnchorResolver`** port with a **`TemporaryDefaultAnchorResolver`** implementation — **behaviour unchanged** |
| **Effect** | Q-2's eventual ruling then **swaps an implementation** rather than **editing service behaviour**; the interim policy becomes a **named, replaceable** thing instead of a private method |
| **Status** | **OPENED, not delivered.** Separate from Slice 7B and **not a reopening of it** |

**The decoupling this rests on:** *acceptance of delivered work* and *evolution of the model* are **different governance acts**, and they need not be coupled.

---

## 📝 Recording Note — one discrepancy, flagged rather than silently resolved

**The issuing text placed the WP-7B-R1 motion under a heading reading *"Recording Notes — Not Part of Ruling"*, while its own handover statement (*"a separate follow-up work package … is opened"*) and its governance queue both declare the package OPEN.**

**A recording note cannot open a work package.** That is precisely the ruling/note distinction this register exists to enforce — a note explains; only a ruling acts.

**Resolved in favour of the operative text and filed as R-60**, because two independent statements declare it open and a package that exists only in a note would be unenforceable. **The discrepancy is recorded so the register never shows a work package opened by a note.**

## 📝 Two corrections from the issuing authority — applied, and one consequence flagged

**1 · R-60's category is Planning Governance, not Delivery.** **Opening or approving a work package is a *planning* act; delivery governance is the later act of tracking and *accepting* it.** Conflating them would make *opening* and *accepting* the same category — and the whole point of the two-axis classification is that they are not.

**2 · "accepted IMPLEMENTATION baseline", not "architecture baseline".** *Architecture baseline* denotes architectural **decisions**; what R-59 accepted is a **realization**. **Q-2 still owns architectural evolution here**, so the wording must not imply the design itself is frozen. *(A precision point, not a disagreement — but on this programme, wording that overstates what was frozen is exactly how a later reader inherits a false constraint.)*

### ⚠️ The consequence: **R-52 carries the identical misclassification**

`R-52` opened the WP-6 remediation package and is also typed **Delivery Governance · Approval**. **By the correction just applied to R-60, that is a planning act too.**

**Flagged, not corrected.** The issuing authority addressed R-60 only, and normalising R-52 on my own reading would be the Recording Architect amending a ruling nobody asked to amend. **Whether to correct it is the ARB's** — the register now carries the inconsistency where it can be seen.

---

## Governance State

| Item | Before | **After** |
|---|---|---|
| **Slice 7B** | GREEN, awaiting acceptance | ✅ **ACCEPTED & CLOSED — in the implementation baseline** |
| **Interim anchor** | undeclared debt | ✅ **recorded debt, owner Q-2** |
| **WP-7B-R1** | — | ✅ **OPEN — not started** |
| Slice 7C | unauthorized | ⬜ unchanged — queue 10 |
| F-WP6R-1 | recorded | ⬜ unchanged — queue 11 |
| `composer merge-gate` | PASS | ✅ unchanged |

## Governance Queue

| # | Item | Authority | Status |
|---|---|---|---|
| ~~9~~ | ~~Accept Slice 7B~~ | ARB | ✅ **closed — R-59** |
| **10** | **Authorize Slice 7C** | ARB | ⬜ open |
| **11** | **Open an F-WP6R-1 package?** | ARB | ⬜ open |
| **12** | **WP-7B-R1 — deliver + accept** | engineering → ARB | ⬜ **open — R-60** |
| — | EPW anchor · CW · LSM | Q-2 | ⬜ open, non-blocking |

## Handover

> **Slice 7B is ACCEPTED and CLOSED. The Evidence Preservation Window is part of the accepted IMPLEMENTATION baseline. The interim anchor is recorded as explicit architectural debt owned by Q-2. WP-7B-R1 is opened to extract it into a replaceable resolver. Slice 7C remains unauthorized.**

**Two candidate activities, neither started:** **WP-7B-R1** (open under R-60) · **Slice 7C** (requires authorization, queue 10). **Sequencing is the ARB's.**

---

**Traceability:** R-58 (execution) · R-57 · R-56 · slice 7B RED / GREEN / acceptance-review reports · AP-1 (the defect class the interim anchor belongs to) · Policy 2 · Q-2 (the debt's owner). **No code changed by this record · 7B not reopened · 7C not authorized.**
