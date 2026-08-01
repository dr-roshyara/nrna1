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
| **Effect** | Slice 7B **ACCEPTED and CLOSED**; the `EvidencePreservationWindow` enters the **accepted architecture baseline** |

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

**Category:** Delivery Governance · **Transition type:** Approval · **Produces:** an opened work package

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

---

## Governance State

| Item | Before | **After** |
|---|---|---|
| **Slice 7B** | GREEN, awaiting acceptance | ✅ **ACCEPTED & CLOSED — in the baseline** |
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

> **Slice 7B is ACCEPTED and CLOSED. The Evidence Preservation Window is part of the accepted architecture baseline. The interim anchor is recorded as explicit architectural debt owned by Q-2. WP-7B-R1 is opened to extract it into a replaceable resolver. Slice 7C remains unauthorized.**

**Two candidate activities, neither started:** **WP-7B-R1** (open under R-60) · **Slice 7C** (requires authorization, queue 10). **Sequencing is the ARB's.**

---

**Traceability:** R-58 (execution) · R-57 · R-56 · slice 7B RED / GREEN / acceptance-review reports · AP-1 (the defect class the interim anchor belongs to) · Policy 2 · Q-2 (the debt's owner). **No code changed by this record · 7B not reopened · 7C not authorized.**
