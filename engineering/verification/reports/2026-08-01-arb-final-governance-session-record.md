# ARB Final Governance Session — Decision Record

**Date:** 2026-08-01 · **Record prepared by:** Recording Architect · **Authority:** Architecture Review Board
**Status:** ✅ **RECORDED — three decisions issued.** Filed as **R-52 … R-54**.
**📌 CANONICAL for this session.** The preceding session's decisions (R-48…R-51) live in `2026-08-01-arb-governance-decision-record.md`; **this record does not restate them.**
**Two artifact kinds appear below and are never mixed:** **ARB Decision** (issued authority) · **📝 Recording Note** (the Recording Architect's explanation — *not* adopted by the ARB, never citable as authority).
**Repository Integrity Gate:** ✅ PASSED — working tree clean.

---

## Decision 5 — WP-6 Remediation · **R-52**

**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** Approval · **Produces:** an opened work package

#### ARB Decision

| Field | Value |
|---|---|
| **Evidence reviewed** | Historical Reproduction Report (R-50) — outcome **REPRODUCED RED**; six attributes identical to the current failure; conclusion bound to the documented protocol |
| **Decision** | ✅ **OPEN A SEPARATE WP-6 REMEDIATION WORK PACKAGE** |
| **Scope** | Repair `AdjudicationProcessManagerTest` against the 5-argument constructor; restore 4 dead tests; unblock the merge gate |
| **Reason** | The gate is red at closure **and** today; remediation does not block WP-7; no slice can demonstrate a complete gate until it is done; the track stays **independent of WP-7** |
| **Effect** | WP-6 remediation: `DEFERRED` → **`OPEN` (separate track)** |

#### 📝 Recording Note — *not part of the ruling*
- The package is **opened, not delivered.** No repair has been performed.
- The in-memory double's missing method was repaired earlier as a disclosed mechanical fix; **the stale constructor call in the test file was deliberately left**, and is this package's actual work.

## Decision 6 — Governance Note against R-43 · **R-53**

**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** Ratification · **Produces:** an annotation on an existing ruling

#### ARB Decision

| Field | Value |
|---|---|
| **Evidence reviewed** | Historical Reproduction Report (R-50) |
| **Decision** | ✅ **ATTACH AN EXPLANATORY GOVERNANCE NOTE TO R-43 — this is an ANNOTATION, NOT AN AMENDMENT** |
| **Reason** | The reproduced evidence bears on R-43's evidence line and should be discoverable from it, while **R-43's decision text remains untouched** |
| **Effect** | R-43 carries a forward-pointing annotation to R-53. **R-43 itself is unchanged, and the acceptance stands.** |

**Note text, as adopted:**

> Reproduced evidence from the historical reproduction (R-50) establishes that, under the documented reproduction protocol at the WP-6 closure commit `22d604844`, the GreenfieldCore suite terminated with a fatal error and the merge gate would not have completed successfully under those reproduced conditions.
>
> This note **does not amend R-43**, nor does it determine whether the gate was **actually executed** at the time of acceptance. It records only what the reproduced evidence establishes.
>
> **Whether the acceptance evidence was FALSE (the gate was run and misreported) or UNSUPPORTED (the gate was not run) remains undetermined from repository evidence and is not resolved by this note.**

#### 📝 Recording Note — *not part of the ruling*
- **Mechanics, because append-only registers make this non-obvious:** the note is carried by **R-53 as its own register row**, and R-43's row receives a **minimal forward-pointer annotation**. Under **ES-004.3**, status annotations are permitted where decision text and history are not; **no character of R-43's decision text was altered.**
- This also closes, for this one case, the known weakness recorded in MEMORY: *supersession and qualification are forward-linked only, so a reader arriving at the old ruling gets no pointer onward.* **A reader arriving at R-43 now finds the note.**

## Decision 7 — Slice 7B Preparation · **R-54**

**Authority:** ARB · **Category:** Execution Governance · **Transition type:** Authorization · **Produces:** an active preparation activity

#### ARB Decision

| Field | Value |
|---|---|
| **Guard** | Slice 7A accepted ✅ (**R-48**) · preparation previously authorized ✅ (**R-51**) · **WP-6 remediation is a separate track and does not block 7B** |
| **Decision** | ✅ **PREPARATION OF THE SLICE 7B AUTHORIZATION PACKAGE IS ACTIVE — it may proceed** |
| **Scope** | EPW Value Object + the anchor. Deliverables: scope statement · evidence trace · keystones · decision templates · verification strategy |
| **Constraint** | ⛔ **PREPARATION ONLY — no implementation.** 7C excluded |
| **Effect** | Slice 7B preparation: `AUTHORIZED (not started)` → **`ACTIVE`** |

#### 📝 Recording Note — *not part of the ruling*
- **R-54 does not re-authorize what R-51 already authorized** — it moves the activity from *authorized* to *active*. The distinction matters: **R-51 granted permission; R-54 starts the clock.**
- Implementation of 7B still requires its own EP-01/ARB passage. **Preparing the package is not approving it.**

---

## Updated Governance State

| Item | Before | **After** |
|---|---|---|
| **Slice 7A** | ACCEPTED & CLOSED | ✅ unchanged |
| **WP-6 remediation** | DEFERRED pending reproduction | ✅ **OPEN — separate track, not started** |
| **R-43** | no annotation | ✅ **annotated (not amended); acceptance stands** |
| **Historical reproduction** | AUTHORIZED | ✅ **COMPLETE — evidence frozen** |
| **Slice 7B preparation** | authorized, not started | ✅ **ACTIVE** |
| **Slice 7C** | not authorized | ⬜ **unchanged** |
| **`composer merge-gate`** | red | ⛔ **still red** — R-52 opens the work; it does not perform it |

## Updated Governance Queue

| # | Item | Authority | Status |
|---|---|---|---|
| ~~5~~ | ~~Open WP-6 remediation?~~ | ARB | ✅ **closed — R-52 (opened)** |
| ~~6~~ | ~~Note against R-43?~~ | ARB | ✅ **closed — R-53 (annotated)** |
| ~~7~~ | ~~Slice 7B preparation~~ | ARB | ✅ **closed — R-54 (active)** |
| **8** | **Deliver + accept WP-6 remediation** | engineering → ARB | ⬜ open |
| **9** | **Accept Slice 7B** | ARB | ⬜ after 7B is delivered |
| **10** | **Authorize Slice 7C** | ARB | ⬜ after 7B acceptance |
| — | Layer Verification Rule adoption | Decision Authority | ⬜ **still unruled — PROPOSED, non-binding** |
| — | EPW anchor · CW · LSM values | Q-2 / ARB | ⬜ non-blocking — fail-closed covers them |

**No queue item is blocked.** For the first time in this programme's recent history, **every open item is actionable by its owner.**

## Two Authorized Engineering Tracks — independent, not to be merged

| Track | Activity | Authorization | Constraints |
|---|---|---|---|
| **WP-6** | Repair `AdjudicationProcessManagerTest` (5-arg constructor); restore 4 tests; re-run the gate | **R-52** | independent of WP-7; **repair only** |
| **WP-7** | Prepare the Slice 7B Authorization Package | **R-54** | ⛔ **no implementation**; 7C excluded |

---

**Traceability:** R-50 historical reproduction report (frozen evidence) · **R-43** (annotated, **not amended**) · R-48/R-49/R-51 (prior session) · **ES-004.3** (status annotations permitted; decision text and history never rewritten) · **R-34** (authority only by explicit issuance). **No architecture redesigned · no report reopened · no remediation performed · no 7B implementation begun · tracks kept separate.**
