# ARB Transition Authorization Record — WP-6 → WP-7

**Date:** 2026-08-01 · **Record prepared by:** Recording Architect · **Source package:** `2026-08-01-arb-transition-authorization-package.md`
**Status:** ✅ **RECORDED — five authority decisions issued and transcribed.**
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 16 ahead.

> **Heading adopted: "Recorded Authority Decisions", not "ARB Rulings"** — *(refinement adopted)*. **This programme's model distinguishes the ARB from the Decision Authority**, and **R4 was issued by the Decision Authority under EP-01**, not by the ARB. Labelling all five "ARB Rulings" would have implied the ARB exercised another authority's role — **the precise error the five-vote split existed to prevent.** Each decision below names its own issuing authority.
>
> **The Recording Architect records authority and never creates it.** Every decision, reason and effect below is transcribed from the issuing authority. **Nothing here is inferred, and no evidence has been reinterpreted.**

---

## 1. Recorded Authority Decisions

### Ruling R1 — WP-6 Acceptance · *Delivery Governance · Approval*
**Issuing authority: ARB**

| Field | Value |
|---|---|
| **Evidence reviewed** | WP-6 plan §Closure Package — **3/3** authorized items delivered · **10 tests, 22 assertions** · all gates pass · structural integrity accepted · governance integrity accepted · authority reviews complete |
| **Decision** | **WP-6 is ACCEPTED** |
| **Reason** | The closure package evidences complete delivery of the approved scope, with all gates passing and structural, governance and authority verification accepted. |
| **Effect** | **WP-6 is CLOSED / ACCEPTED. WP-7 entry conditions are satisfied.** |

### Ruling R2 — A-1 Ratification · *Architecture Governance · Ratification*
**Issuing authority: ARB**

| Field | Value |
|---|---|
| **Evidence reviewed** | Architecture–Enforcement Alignment Commission — invariant vs mechanism; option (d) resolves G-1 without a domain-model change and without a Deptrac change |
| **Decision** | **A-1 is RATIFIED.** Option (d) — **Election declares its own consumer-side port** — is the approved realization. |
| **Reason** | *"MAD has exactly one home"* is the **invariant**. *"Consume Adjudication's existing port"* was a **mechanism**, never an architectural decision. **The invariant is binding; the mechanism is substitutable.** |
| **Effect** | **G-1 is RESOLVED.** Election owns the consumer-side port. **Deptrac remains unmodified. Domain model unchanged.** |

### Ruling R3 — A-2 Ratification · *Architecture Governance · Ratification*
**Issuing authority: ARB**

| Field | Value |
|---|---|
| **Evidence reviewed** | Responsibility Inventory — Election ANSWERS, Audit/Retention ACTS; consistent with the frozen allocation |
| **Decision** | **A-2 is RATIFIED** |
| **Reason** | Election **ANSWERS** (*"may this evidence be deleted yet?"*); Audit/Retention **ACTS** (the guard). Consistent with the frozen allocation; **no holder changes.** |
| **Effect** | **Guard ownership CLARIFIED.** Two responsibilities, two holders, no overlap. |

### Ruling R4 — WP-7 Plan Approval (EP-01) · *Planning Governance · Approval*
**Issuing authority: DECISION AUTHORITY** *(not the ARB — EP-01 plan approval is the Decision Authority's act)*

| Field | Value |
|---|---|
| **Evidence reviewed** | `.claude/plans/WP-7-retention-alignment.md` — complete and consistent with the frozen architecture |
| **Decision** | **The WP-7 EP-01 Plan is APPROVED** |
| **Reason** | The plan is complete and consistent with the frozen architecture. |
| **Effect** | **The WP-7 plan is the binding statement of intended work.** |

### Ruling R5 — Slice 7A Authorization · *Execution Governance · Authorization*
**Issuing authority: ARB**

| Field | Value |
|---|---|
| **Evidence reviewed** | Guard `WP-6 ACCEPTED ∧ PLAN APPROVED`; zero architectural gates remain |
| **Guard satisfied?** | ✅ **YES** — R1 established `WP-6 ACCEPTED`; R4 established `PLAN APPROVED`. *(`PLAN APPROVED`'s own guard `G-1 RESOLVED` was established by R2.)* |
| **Decision** | **Execution of Slice 7A is AUTHORIZED** |
| **Reason** | The guard `WP-6 ACCEPTED ∧ PLAN APPROVED` is satisfied. Zero architectural gates remain. |
| **Effect** | **WP-7 opens. RED begins at slice 7A with no further architectural act.** |

### Item not put to a decision — recorded as such
**Adoption of the `Layer Verification Rule` methodology module** was offered as optional and **non-blocking**; **no ruling was issued.** It therefore **remains 🟡 PROPOSED — NOT ADOPTED, and non-binding** (R-34: authority only by explicit issuance). **WP-7 does not depend on it. Silence is not adoption**, and recording it as unruled is part of recording the session faithfully.

## 2. Governance Traceability

| Ruling | Category | Transition type | Produces | Issuing authority |
|---|---|---|---|---|
| **R1** | Delivery Governance | **Approval** | accepted work-package baseline | ARB |
| **R2** | Architecture Governance | **Ratification** | architectural decision | ARB |
| **R3** | Architecture Governance | **Ratification** | architectural decision | ARB |
| **R4** | Planning Governance | **Approval** | approved implementation plan | **Decision Authority** |
| **R5** | Execution Governance | **Authorization** | authorized engineering work | ARB |

*(Category definitions live once, in `engineering/knowledge/methodology/Layer_Verification_Rule.md` §3 — cited, never restated. The package's recorded caveat on axis independence stands unchanged and is not reopened here.)*

## 3. Governance State After the Session

| State | Before | **After** |
|---|---|---|
| **WP-6** | `PENDING ACCEPTANCE` | ✅ **ACCEPTED** |
| **G-1** | `PENDING RESOLUTION` | ✅ **RESOLVED** |
| **Guard ownership** | `PENDING CLARIFICATION` | ✅ **CLARIFIED** |
| **WP-7 plan** | `PENDING APPROVAL` | ✅ **APPROVED** |
| **WP-7 execution** | `PENDING AUTHORIZATION` | ✅ **AUTHORIZED** *(slice 7A)* |
| **RED** | `BLOCKED` | ✅ **AUTHORIZED — slice 7A** |

**Every transition in the machine has now fired, and each fired by its own authority act.** No state changed by inference.

## 4. Deferred, Rejected or Unruled Items

| Item | Outcome | What it awaits |
|---|---|---|
| Layer Verification Rule module adoption | **UNRULED** — remains PROPOSED, non-binding | an explicit Decision Authority ruling (R-34) |
| **C-1 automation** | **not a governance item** — engineering's call | deliverable **inside 7A**; engineering judged it recommended-blocking |
| EPW anchor · CW · LSM values | outstanding **business** decisions | Q-2 — **non-blocking**; fail-closed covers every case |
| Slices **7B** and **7C** | **NOT authorized** — R5 authorized **7A only** | their own gates *(7B additionally rests on R3, now ratified)* |
| Governance register (AT-EVT-001 · DC-1 · H-1..H-3 · A-1/A-2/A-4 · B/C series · AD-007..010) | ARB / backlog | **none is a WP-7 dependency** |
| 7C release announcement | operational | an announcement owner outside engineering |

## 5. Handover

> **Governance authorization is complete for the approved scope. Responsibility for execution transfers to engineering in accordance with the approved WP-7 plan.** *(Governance does not disappear — it has completed this transition.)*

**First authorized engineering activity — RED at slice 7A:**

> Failing tests for retention-duration resolution: **per election type** · **organisation override** · **fail closed on missing or invalid** — against **Election's own `EvidencePreservationDurations` port** (per R2), mirroring the verified `AdjudicationDurations` shape.
>
> **In the same slice:** the **C-1** guard, and — recommended — **R-D1** *(both adapters resolve the same MAD for the same `(electionType, organisationId)`)*.
>
> **7A is inert:** nothing consumes it; **no observable behaviour changes until 7C, which is not authorized.**

**Authorization boundary, stated so it cannot be exceeded by momentum:** **R5 authorizes slice 7A. It does not authorize 7B or 7C.**

## 6. Filing

| Field | Value |
|---|---|
| Rulings filed to | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` as **R-43 … R-47** *(append-only; the register's last prior entry was R-42)* |
| Precedent | **R-39** |
| Record location | `engineering/verification/reports/2026-08-01-arb-transition-authorization-record.md` |
| Source package | `engineering/verification/reports/2026-08-01-arb-transition-authorization-package.md` — **unmodified by this commission** |

---

> ## **The ARB Transition Authorization Record is complete. The five rulings are recorded. The governance state is updated. Governance authorization is complete for the approved scope, and responsibility for execution transfers to engineering in accordance with the approved WP-7 plan (Slice 7A). RED may begin.**

---

**Traceability:** ARB Transition Authorization Package (the evidence source, unmodified) · WP-6 closure package · architecture–enforcement alignment commission (A-1, A-2) · implementation guard commission (C-1) · decision dependency verification (the DAG the guard encodes) · `.claude/CLAUDE.md` §EP-01 · **R-34** (authority only by explicit issuance — the basis for recording the unruled item as unruled) · **R-39** (rulings-register precedent). **No evidence reinterpreted · no review performed · no architecture redesigned · no decision added · the package unmodified.**
