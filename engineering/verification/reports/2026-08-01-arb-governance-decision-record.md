# ARB Governance Decision Record — WP-7 Slice 7A · WP-6 Remediation Strategy · Slice 7B

**Date:** 2026-08-01 · **Record prepared by:** Recording Architect · **Authority:** Architecture Review Board
**Status:** ✅ **RECORDED — four decisions issued and transcribed.** Filed as **R-48 … R-51**.
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`.

> **On issuance.** I first read the four items as *recommendations* and declined to record them, because **R-34** holds that *nothing becomes a ruling by inference from praise or suggestion*. **The ARB reissued the session unchanged.** A reaffirmation after a stated objection **is** an exercise of authority, so the decisions are recorded here. **The objection and its resolution are both on the record** — that is the audit trail, not an aside.
>
> **The Recording Architect records authority and never creates it.** No evidence was regenerated and no implementation evidence reinterpreted.

---

## 1. Decision 1 — WP-7 Slice 7A Acceptance

**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** Approval · **Produces:** accepted work-package baseline

| Field | Value |
|---|---|
| **Evidence reviewed** | Slice 7A GREEN report — 11/11 keystones · Deptrac **0** (`deptrac.yaml` unmodified) · PHPStan max **clean** · Architecture suite **149 green** · developer guide filed · RED report filed |
| **Decision** | ✅ **ACCEPTED** |
| **Reason** | The authorized scope was completed, the implementation respected the architectural boundaries, and the remaining merge-gate issue lies outside Slice 7A's scope. |
| **Effect** | **Slice 7A is ACCEPTED and CLOSED. Its implementation becomes part of the accepted architecture baseline.** |

**Recorded alongside the acceptance, because it does not disappear on acceptance:** 7A **never demonstrated a complete `composer merge-gate`**, and does not claim to have. The gate remains blocked by **F-7A-1**. **Acceptance is of 7A's authorized scope, not of a green gate.**

## 2. Decision 2 — WP-6 Remediation Strategy

**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** Approval · **Produces:** an authorized investigation track

| Field | Value |
|---|---|
| **Evidence reviewed** | F-7A-1 — the interface/test inconsistency, its provenance from commit dates, and the explicit limits on what those dates prove |
| **Decision** | ✅ **OPTION B — HISTORICAL INVESTIGATION FIRST** |
| **Reason** | There is evidence of a current inconsistency, but whether it affects the earlier acceptance is a **historical** question. Reproducing the historical gate before opening remediation **keeps evidence collection separate from repair.** |
| **Effect** | No remediation work package is opened yet. A **historical reproduction** track is authorized under Decision 3. **Remediation remains an open question, to be decided on reproduced evidence.** |

> ### ⚠️ Recorded so Option B is not later misread
>
> **Reproduction is not a precondition for repair; it is a precondition for judging R-43.** The merge gate stays **red either way**, so **no slice can demonstrate a complete gate until remediation happens** — whenever the ARB opens it. **Option B answers "was the earlier acceptance evidence sound?", not "is the gate usable now?"**

## 3. Decision 3 — Historical Reproduction *(conditional on D2 = Option B — condition met)*

**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** Authorization · **Produces:** authorized evidence-collection work

| Field | Value |
|---|---|
| **Decision** | ✅ **AUTHORIZED** |
| **Reason** | A narrowly scoped evidence-gathering activity with no production changes, allowing a future remediation decision to rest on reproduced evidence. |
| **Purpose** | Reproduce `composer merge-gate` **at the recorded WP-6 closure commit** and **report only the reproduced evidence.** |
| **Explicit constraints** | ⛔ no production changes · ⛔ no test repairs · ⛔ no governance edits · **evidence collection only** |

**All three outcomes are legitimate findings, recorded in advance so none is treated as a failure:**

| Outcome | Meaning |
|---|---|
| **Reproduced GREEN** | no historical issue — the inconsistency arose after closure |
| **Reproduced RED** | the ARB investigates the acceptance evidence |
| **Reproduction impossible** | **historical uncertainty remains — itself a finding**, not a failed task |

## 4. Decision 4 — Slice 7B Authorization

**Authority:** ARB · **Category:** Execution Governance · **Transition type:** Authorization · **Produces:** authorized engineering work
**Guard: Slice 7A accepted — ✅ satisfied by Decision 1.**

| Field | Value |
|---|---|
| **Decision** | ✅ **AUTHORIZED** |
| **Reason** | Slice 7A is complete within its authorized scope. Slice 7B proceeds independently while any WP-6 investigation or remediation remains a **separate governance track**. |
| **Effect** | **Preparation of the Slice 7B Authorization Package is authorized. Implementation is NOT begun by this decision** — authorization permits execution *within* 7B's approved scope; it does not itself perform it. |

**Dependency recorded rather than left implicit:** had D1 been rejected, **D4 would have been unreachable, not merely deferred** — the guard is a precondition, not a preference.

## 5. Updated Governance State

| State | Before | **After** |
|---|---|---|
| **WP-7 Slice 7A** | GREEN, awaiting acceptance | ✅ **ACCEPTED / CLOSED — in the architecture baseline** |
| **WP-6 remediation** | undecided | 🔍 **DEFERRED pending reproduction** (Option B) |
| **Historical reproduction** | not authorized | ✅ **AUTHORIZED — evidence collection only** |
| **WP-7 Slice 7B** | not authorized | ✅ **AUTHORIZED — preparation may begin** |
| **WP-7 Slice 7C** | not authorized | ⬜ **unchanged — still not authorized** |
| **`composer merge-gate`** | red (F-7A-1) | ⛔ **unchanged — still red.** No decision here repairs it |

## 6. Updated Governance Queue

| # | Item | Authority | Status |
|---|---|---|---|
| ~~1~~ | ~~Accept Slice 7A~~ | ARB | ✅ **closed — R-48** |
| ~~2~~ | ~~WP-6 remediation strategy~~ | ARB | ✅ **closed — R-49 (Option B)** |
| ~~3~~ | ~~Historical reproduction~~ | ARB | ✅ **closed — R-50 (authorized)** |
| ~~4~~ | ~~Slice 7B authorization~~ | ARB | ✅ **closed — R-51** |
| **5** | **Open WP-6 remediation?** | **ARB** | 🔒 **BLOCKED — awaits reproduced evidence** |
| **6** | **Note against R-43?** | **ARB** | 🔒 **BLOCKED — awaits reproduced evidence** |
| **7** | **Accept Slice 7B** | ARB | ⬜ after 7B is delivered |
| **8** | **Authorize Slice 7C** | ARB | ⬜ after 7B acceptance |
| — | Layer Verification Rule adoption | Decision Authority | ⬜ **still unruled — remains PROPOSED, non-binding** |
| — | EPW anchor · CW · LSM values | Q-2 / ARB | ⬜ non-blocking — fail-closed covers them |

**The queue shortened by four and grew by two.** Items 5 and 6 are **new and blocked** — Option B converts one open question into two that cannot be answered until the reproduction runs. **That is the cost of separating evidence from repair, and it is the intended cost.**

## 7. Required Follow-Up Actions

**Two authorized engineering activities now exist. They are independent and must not be merged.**

| # | Activity | Authorized by | Constraints |
|---|---|---|---|
| **A** | **Historical reproduction** — check out the recorded WP-6 closure commit, run `composer merge-gate`, report only what was observed | **R-50** | ⛔ no production changes · ⛔ no test repairs · ⛔ no governance edits · **read-only investigation** |
| **B** | **Prepare the Slice 7B Authorization Package** — evidence, scope, keystones, blank decision templates | **R-51** | ⛔ **no implementation** · scope is 7B's plan text only (EPW value object + the anchor) · **7C stays out** |

**Not authorized by anything here:** repairing `AdjudicationProcessManagerTest` · starting 7B implementation · touching 7C · amending R-43.

**Sequencing is engineering's to choose** — the two activities are independent, and neither blocks the other.

---

## Filing

| Ruling | Decision | Category · Type | Authority |
|---|---|---|---|
| **R-48** | Slice 7A **ACCEPTED** | Delivery · Approval | ARB |
| **R-49** | WP-6 remediation → **Option B** | Delivery · Approval | ARB |
| **R-50** | Historical reproduction **AUTHORIZED** | Delivery · Authorization | ARB |
| **R-51** | Slice 7B **AUTHORIZED** (preparation) | Execution · Authorization | ARB |

Filed append-only to `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` *(prior last entry R-47)*.

---

**Traceability:** Slice 7A RED and GREEN reports · F-7A-1 (and the corrected scoping of its historical claim) · governance queue as recorded in CONTEXT · R-43 (WP-6 acceptance — **not amended by any decision here**) · R-46/R-47 (the plan and 7A's authorization) · **R-34** (authority only by explicit issuance — and the reason the first reading was declined). **No architecture redesigned · no review reopened · no implementation evidence reinterpreted · WP-6 not silently repaired · 7B implementation not begun · remediation and historical investigation kept separate.**
