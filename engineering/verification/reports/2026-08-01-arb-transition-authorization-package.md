# ARB Transition Authorization Package — WP-6 → WP-7

**Date prepared:** 2026-08-01 · **Prepared by:** Recording Architect · **Purpose:** evidence and recommendations for the ARB Transition Authorization session
**Status:** 📋 **PREPARED — AWAITING ARB AUTHORITY.** No outcome recorded · no state transition recorded.
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 14 ahead.

> ## ✍️ CORRECTION — this artifact was misnamed, and the misnomer WAS the boundary crossing
>
> It was previously `…-arb-transition-session-record.md`. **I created an artifact called a *Record* before there was anything to record** — collapsing **preparation** and **recording** in the filename itself, while the body correctly refused to record anything. **The body held the line; the name did not.**
>
> **Renamed to *Package* (history preserved via `git mv`).** The two artifacts are now distinct:
>
> | Artifact | Contains | Created |
> |---|---|---|
> | **A · Authorization PACKAGE** *(this file)* | evidence · recommendations · traceability · **blank** templates | **before** the session |
> | **B · Authorization RECORD** | actual rulings · outcomes · state transitions | **after** the session — **§7 is its blank template** |

---

## 1. Evidence Summary and Recommendations

**Recommendations are the architect's. They are not votes, and they bind nothing.**

| # | Decision | Evidence | Recommendation |
|---|---|---|---|
| **D1** | **WP-6 Acceptance** | WP-6 closure package — **3/3** authorized items delivered · keystones **10 tests / 22 assertions** · contexts **91 / 260** · PHPStan max clean (**4 root fixes, none suppressed**) · Deptrac **0** · Architecture **146 green** · APR · ADPR · AGIR (structural pass; governance pass after correction) · authority verification · dev guide + operational record. **Verified complete.** | **Accept** within the approved scope |
| **D2** | **A-1 Ratification** | Architecture–Enforcement Alignment Commission — the **invariant vs mechanism** distinction; option (d) resolves G-1 **without** a domain-model change and **without** a Deptrac change | **Ratify** — rule that the **invariant** was binding |
| **D3** | **A-2 Ratification** | Responsibility Inventory — **Election ANSWERS, Audit/Retention ACTS**; consistent with the frozen *"Consumption (acting on the answer)"*; **no holder changes** | **Ratify** |
| **D4a** | **WP-7 Plan Approval (EP-01)** | `.claude/plans/WP-7-retention-alignment.md` — complete and consistent with the frozen architecture; **current text, amended 2026-08-01** | **Approve** |
| **D4b** | **Slice 7A Authorization** | Guard `WP-6 ACCEPTED ∧ PLAN APPROVED`; **zero architectural gates remain** | **Authorize** |

### Two evidence limits, labelled rather than hidden

- **D1** — *"Deptrac 0 / Architecture 146 green"* **does not cover the AP-1/AP-2 defect class** (*a business value was invented*). **No automated gate does.** Both defects were nonetheless found and fixed before acceptance.
- **D2** — *"Deptrac passes unmodified"* is an **analytical prediction, not an executed result** (no code exists). Structurally sound; **not empirically demonstrated.**

### Rejection branch for D2 — so the ARB rules knowingly

Rejecting A-1 means ruling that *the sentence* was binding → a direct cross-context import → **a TP-1 violation Deptrac would correctly fail** → requiring **option (c) (relaxing the approved Deptrac model) or a plan revision. Neither is on this agenda, and option (c) is NOT pre-authorized by this package.** **D4a and D4b could not then proceed.**

## 2. Governance Traceability (pre-ARB)

| # | Decision | **Category** | **Transition type** | **Produces** | Authority |
|---|---|---|---|---|---|
| D1 | WP-6 Acceptance | **Delivery Governance** | **Approval** | accepted work-package baseline | ARB |
| D2 | A-1 Ratification | **Architecture Governance** | **Ratification** | architectural decision | ARB |
| D3 | A-2 Ratification | **Architecture Governance** | **Ratification** | architectural decision | ARB |
| D4a | WP-7 Plan Approval | **Planning Governance** | **Approval** | approved implementation plan | **Decision Authority** |
| D4b | Slice 7A Authorization | **Execution Governance** | **Authorization** | authorized engineering work | ARB |

> ### ⚠️ This typing CORRECTS a defect in my earlier table — worth naming, not quietly swapping
>
> I had typed D4a as *"Planning"* and D4b as *"Execution"*. **Those merely restated the category column** — which **violates the very independence I claimed for the two axes.** A column derivable from its neighbour is not a second dimension.
>
> **The corrected set — Approval · Ratification · Approval · Authorization — is genuinely independent, and the table now carries its own proof: D1 and D4a share the type *Approval* across two different categories.**
>
> *(Category definitions live once, in `engineering/knowledge/methodology/Layer_Verification_Rule.md` §3, and are cited here, never restated.)*

## 3. Governance State Before the ARB Session

| State | Status | Category |
|---|---|---|
| **WP-6** | `PENDING ACCEPTANCE` | Delivery Governance |
| **G-1** | `PENDING RESOLUTION` *(analysed; realization recommended, unratified)* | Architecture Governance |
| **Guard ownership** | `PENDING CLARIFICATION` | Architecture Governance |
| **WP-7 plan** | `PENDING APPROVAL` *(awaiting EP-01)* | Planning Governance |
| **WP-7 execution** | `PENDING AUTHORIZATION` | Execution Governance |
| **RED** | `BLOCKED` | — |

**The guard:**

> **`WP-6 ACCEPTED` ∧ `WP-7 PLAN APPROVED` ⇒ execution authorization is LEGAL.** Neither alone suffices. **`PLAN APPROVED` is itself guarded by `G-1 RESOLVED`.**

**Why stated as a guard rather than a dependency:** it makes `execution AUTHORIZED ∧ plan PENDING APPROVAL` **unreachable** rather than merely discouraged — **EP-01 enforced by the model instead of by memory.**

## 4. Decision Dependencies — verified (DAG, single sink, no cycles)

- **D1 ⟂ D2** — *delivered Adjudication code* vs *the reading of a WP-7 plan sentence*. **Neither is evidence for the other**; grantable in either order, or in the same session.
- **D4a ← D2** — a **content** prerequisite, not an execution one: the plan text already contains A-1's substitution and, on rejection, reverts to a mechanism that fails Deptrac.
- **D4b ← D1 ∧ D4a.**
- **D3 is UNRELATED to D4a and D4b** — it gates **slice 7B**; **7A is unaffected either way.**

## 5. Decision Templates — **BLANK**

*(One per decision. Nothing pre-filled. **Rationale is required, not optional:** six months on it is the only part that explains **why**, and an outcome without one is unauditable.)*

### Decision — D1 — WP-6 Acceptance · *Delivery Governance · Approval*
| Field | Value |
|---|---|
| Evidence reviewed | *(§1)* |
| Decision | ☐ ACCEPTED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — D2 — A-1 Ratification · *Architecture Governance · Ratification*
| Field | Value |
|---|---|
| Evidence reviewed | *(§1)* |
| Decision | ☐ ACCEPTED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — D3 — A-2 Ratification · *Architecture Governance · Ratification*
| Field | Value |
|---|---|
| Evidence reviewed | *(§1)* |
| Decision | ☐ ACCEPTED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — D4a — WP-7 Plan Approval (EP-01) · *Planning Governance · Approval*
| Field | Value |
|---|---|
| Evidence reviewed | *(§1)* |
| Decision | ☐ ACCEPTED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — D4b — Slice 7A Authorization · *Execution Governance · Authorization*
| Field | Value |
|---|---|
| Evidence reviewed | *(§1)* |
| Guard satisfied? | ☐ `WP-6 ACCEPTED` ☐ `PLAN APPROVED` |
| Decision | ☐ ACCEPTED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Optional — Layer Verification Rule module adoption *(non-blocking; WP-7 does not depend on it)*
| Field | Value |
|---|---|
| Decision | ☐ ADOPTED ☐ DEFERRED |
| Reason | |

## 6. First Authorized Activity — **conditional**

> **Condition — if D1, D2, D4a and D4b are approved**, the first authorized engineering activity is:
>
> **RED at slice 7A** — failing tests for retention-duration resolution: **per election type** · **organisation override** · **fail closed on missing or invalid** — against **Election's own `EvidencePreservationDurations` port**, mirroring the verified `AdjudicationDurations` shape. **Plus the C-1 guard** *(engineering's call — the one constraint whose manual enforcement is insufficient)* **and, recommended, R-D1** *(both adapters resolve the same MAD for the same `(electionType, organisationId)`)*.
>
> **7A is inert** — nothing consumes it; **no observable behaviour changes until 7C.**

**After the outstanding authority decisions are exercised, no further governance PREPARATION is required before RED.** *(Preparation, authority and execution are separate phases. What is complete is **preparation** — governance itself continues.)*

## 7. Recording Template — for use **AFTER** the session. **Left blank.**

```markdown
# ARB Transition Authorization Record — WP-6 → WP-7

**Date:** [ ]   **Authority:** Architecture Review Board / Decision Authority
**Record prepared by:** Recording Architect
**Source package:** 2026-08-01-arb-transition-authorization-package.md

## Rulings

### Ruling 1 — WP-6 Acceptance            [Delivery Governance · Approval]
| Field | Value |
|---|---|
| Evidence reviewed | |
| Decision | |
| Reason (ARB rationale) | |
| Effect | |

### Ruling 2 — A-1 Ratification            [Architecture Governance · Ratification]
| Field | Value |
|---|---|
| Evidence reviewed | |
| Decision | |
| Reason (ARB rationale) | |
| Effect | |

### Ruling 3 — A-2 Ratification            [Architecture Governance · Ratification]
| Field | Value |
|---|---|
| Evidence reviewed | |
| Decision | |
| Reason (ARB rationale) | |
| Effect | |

### Ruling 4a — WP-7 Plan Approval         [Planning Governance · Approval]
| Field | Value |
|---|---|
| Evidence reviewed | |
| Decision | |
| Reason (Decision Authority rationale) | |
| Effect | |

### Ruling 4b — Slice 7A Authorization     [Execution Governance · Authorization]
| Field | Value |
|---|---|
| Evidence reviewed | |
| Guard satisfied? (WP-6 ACCEPTED ∧ PLAN APPROVED) | |
| Decision | |
| Reason (ARB rationale) | |
| Effect | |

## Governance State After the Session

| State | Before | After |
|---|---|---|
| WP-6 | PENDING ACCEPTANCE | |
| G-1 | PENDING RESOLUTION | |
| Guard ownership | PENDING CLARIFICATION | |
| WP-7 plan | PENDING APPROVAL | |
| WP-7 execution | PENDING AUTHORIZATION | |
| RED | BLOCKED | |

## Deferred or Rejected Items — and the evidence each awaits

| Item | Outcome | What it awaits |
|---|---|---|

## Handover

Governance responsibility transfers to engineering **only if D1 ∧ D2 ∧ D4a ∧ D4b are approved**.

**First authorized engineering activity:** [ ]
**Filed to the rulings register:** ☐  (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, per the R-39 precedent)
```

---

## Package completion status

| Criterion | Status |
|---|---|
| Evidence prepared per decision | ✅ **with two limits labelled** |
| Recommendations explicit and marked non-binding | ✅ |
| Governance traceability — category · type · produces · authority | ✅ **and one earlier typing defect corrected** |
| Dependencies verified | ✅ DAG, single sink, no cycles |
| Decision templates **blank** | ✅ |
| Recording template included and **blank** | ✅ §7 |
| **No outcome recorded** | ✅ |
| **No state transition recorded** | ✅ — §3 is the state **before**, and it is unchanged |
| **No authority issued** | ✅ |

**Three roles, three acts:** the **Authority** decides · the **Recording Architect** records · **engineering** executes. **Recording is not deciding, and deciding is not executing.**

**This commission ends at the boundary before authority is exercised.**

---

**Traceability:** ARB session decision pack v2 · decision dependency verification (DD-1/DD-2/DD-3) · ARB transition authorization commission · WP-6 closure package · architecture–enforcement alignment commission (A-1, A-2) · implementation guard commission (C-1, gate-coverage limits) · `engineering/knowledge/methodology/Layer_Verification_Rule.md` §3 (canonical governance categories — cited, not restated) · `.claude/CLAUDE.md` §EP-01 · **R-34** (authority only by explicit issuance) · **R-39** (rulings-register precedent). **Renamed from `…-arb-transition-session-record.md` via `git mv`; history preserved.**
