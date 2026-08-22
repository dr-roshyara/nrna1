# Session Completion Report & Next Actor Recommendation — Operational Protocol

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **Classification:** **OPERATIONAL GOVERNANCE IMPROVEMENT**
**Status:** **ADOPTED OPERATIONAL PRACTICE** *(PO/ARB act 2026-08-22 · registration: `2026-08-22-KOS-AIP-GOV-STATE-DURABILITY-ADR-session-completion-handoff-protocol-adoption-registration.md`)* — *not* accepted architecture · *not* a workflow engine change · *not* a migration requirement · *not* an EKS-07 implementation
**Registered by:** the producing session — **self-declared, not attestable** (`INV-ATTR-1`/`INV-ATTR-2`)

> ### ⭐ The one-line thesis
> **"After a governed session finishes, the human should not need to reverse-engineer the next responsible actor."**

---

## What this is / what this is not

**What it is:** a lightweight operational protocol. When a governed AI session completes its responsibility, it MUST produce a **Session Completion Report** that states — deterministically, on the evidence — *what was completed, where the work stands, who should act next, and why*.

**What it is NOT:**

| ⛔ MUST NOT |
|---|
| create a new bounded context |
| create a new governance engine |
| modify workflow authority rules |
| bypass human approval |
| replace `REGISTER` / `HANDOFF` / `START` discipline |
| create automatic actor switching |
| create autonomous governance decisions |

**DDD position:** a **governance-supporting** capability — **NOT** a domain-authority capability.

> ### Coordination ≠ Authority
> The protocol may recommend *"Next actor should be Governance."* It cannot start Governance, approve Governance, assign authority, or close findings.

---

## 1 · Purpose

The protocol exists to **reduce human investigation time while preserving human authority.**

During the `KOS-AIP-GOV-STATE-DURABILITY` work, repeated coordination friction was observed:

- multiple AI sessions worked on related activities;
- sessions completed work, but the next responsible actor was not immediately visible;
- humans had to reconstruct, by investigation: current state · ownership · next responsible role · required authority · the correct session to continue.

**The problem is NOT that human authority is wrong. Human authority MUST remain.**

The problem is narrowly the legibility gap after a session finishes:

```
Current situation:                      Desired situation:

AI Session completes                    AI Session completes
        |                                        |
        v                                        v
Human receives:                         Human receives:
  "Work done"                              Session Completion Report
        |                                        |
        v                                        v
Human investigates:                      Human immediately sees:
  - Which workflow state?                  Next actor:  Governance
  - Who owns next step?                    Reason:      Independent review cannot accept itself
  - Which process is eligible?             Human action: Approve Governance START
  - Which approval is needed?
```

The protocol does not decide who may act. It makes visible who **should** act, so the human's decision is a decision rather than an archaeological dig. It converts *reconstruction* into *recognition* — the human's remaining work is to **approve**, not to **discover**.

---

## 2 · Completion Report Model

Every governed AI session that completes its responsibility MUST produce a report conforming to this mandatory structure. **No field may be omitted.** A field may be answered `NONE` where it is genuinely empty — it may not be left blank.

### Mandatory structure

```
SESSION COMPLETION REPORT

Work Item:                <aggregate>
Current Role:             <role>
Session Identity:         <self declared process identity>
Completed Work:           <summary>
Evidence Produced:        <artifacts>
Current State:            <workflow state>
Remaining Obligations:    <open items>
Recommended Next Actor:   <role>
Reason:                   <why this actor>
Human Decision Required:  YES / NO
Can Current Session Continue:  YES / NO
Continuation Reason:      <explanation>
```

### Field semantics

| Field | Meaning |
|---|---|
| **Work Item** | the aggregate the session acted upon — named by its governed identifier, never by a description |
| **Current Role** | the role the session performed in this responsibility (declared-role vocabulary only — see §3) |
| **Session Identity** | self-declared process identity. ⚠️ **Recorded, never attested** (`INV-ATTR-1`/`INV-ATTR-2`); no gate reads it |
| **Completed Work** | what was finished — a summary of the responsibility, not a justification |
| **Evidence Produced** | the durable artifacts the session created, by path |
| **Current State** | the workflow state **consumed from the authoritative record** (`CREATED` · `ACTIVE` · `HANDED_OFF` · `COMPLETED` · `STOPPED` · `FAILED` · `CANCELLED`) — the report **reads** this state, it does **not author** it |
| **Remaining Obligations** | open items that belong to this work item and are not yet discharged |
| **Recommended Next Actor** | the declared role that should act next — **a recommendation, never an assignment** |
| **Reason** | the rule (independence, authority boundary) that produces the recommendation — a preference is not a reason |
| **Human Decision Required** | `YES` if any human act (`humanAct`, grant, approval, decision, START) is needed before the next actor may act; `NO` only where no human authority boundary is crossed |
| **Can Current Session Continue** | the session's self-answered continuation question (§4) — `YES`/`NO`, never blank |
| **Continuation Reason** | the rule that allows or prohibits continuation |

**The report is an evidence artifact.** It is **advisory**, it registers nothing, it grants nothing, and it is authored under the producer bar: the session that produced a report is subject to the same independence rules as any other producer (§4).

---

## 3 · Actor Recommendation Rules

The recommendation must be produced by **deterministic rules** — fixed mappings from *(completing role, completed obligation)* to *next actor*, derived from the estate's independence and authority boundaries — **not** by the session's preference, convenience, or self-interest.

### The rules

| Completing role | Completed obligation | Recommended next actor | Reason |
|---|---|---|---|
| **Independent Reviewer** | a review | **Governance** | Reviewer cannot accept its own review; acceptance authority remains separate |
| **Architecture / Implementation author** | a correction | **Independent Reviewer** | Author cannot independently verify its own correction |
| **Governance** | a bounded review / registration | **PO/ARB** | Decision authority is reserved to the human PO/ARB |
| Any role | the same responsibility continues with no independence constraint | **same role** (self-continuation `YES`) | continuity; no separation bar applies |
| Any role | no declared next actor fits | **PO/ARB** | no declared actor — decision authority escalates to the human |

### Constraint rules

1. ⛔ **Do not invent additional roles.** The `Recommended Next Actor` MUST be drawn from the estate's declared role vocabulary (PO/ARB · Governance · Independent Reviewer / Architecture · Verification · Knowledge · Communication · Implementation). If no declared role fits the situation, the report MUST say so explicitly and escalate to PO/ARB — it MUST NOT coin a role.
2. **Independence rule (the producer bar):** a producing process is never its own next actor where the next act is **review, acceptance, approval, or decision of its own output**. The report must recommend against such self-routing even when the session is capable of performing the act.
3. **Authority rule:** the recommendation may name a role whose act is **decision or approval** only when that role holds the authority — in this estate that authority is reserved to the human PO/ARB.
4. The recommendation is a **statement about who should act**, not a permission for anyone to act. It assigns nothing.

---

## 4 · Self-Continuation Decision

Every completion report MUST answer: **"Can I continue?"** — and MUST answer it under a rule, never by preference.

### Possible outcomes

| Outcome | Meaning | Example |
|---|---|---|
| **YES** | the same responsibility continues, and no independence constraint applies to the next slice | a multi-part implementation obligation where the session remains in the same bounded responsibility and the next slice is more of the same work — not a review, acceptance, approval, or decision of its own output |
| **NO** | an independence or authority bar forbids this session from performing the next act | — |

### Bars that make continuation `NO`

- a reviewer cannot accept its own review;
- an author cannot independently approve its own correction;
- Governance cannot act as the technical reviewer of a subject it governs;
- the producer of an artifact cannot later approve, verify, or accept that artifact.

> ⛔ **The self-answer is a self-declaration, not an authority decision.** A session answering `YES` does not thereby acquire the right to act — the workflow engine still governs who is allowed to act (§6). A session answering `NO` is stating a bar it must respect; it is not being "blocked by the system," it is being bound by its own independence.

---

## 5 · Human Authority Boundary

The boundary is explicit and non-negotiable. **Human authority remains mandatory.**

| AI (the session) CAN | AI (the session) CANNOT |
|---|---|
| **recommend** a next actor | **create** a `humanAct` — a grant registers a recorded human act by reference; the record never manufactures authority (`G-2`/`R5b`) |
| **explain** the reasoning behind the recommendation | **approve itself** or its own output |
| **summarize** state, obligations, and evidence | **transfer authority** from any role to any role |
| **identify dependencies** and the boundary crossings that require a human act | **bypass** the workflow (`REGISTER` / `HANDOFF` / `START` / `ACCEPTANCE`) |
| answer `Human Decision Required: YES` when a boundary is crossed | decide, adopt, accept, or certify anything itself |

**Consequence:** the completion report is the *last* AI-authored artifact in a slice. Everything after it — the approval, the START, the decision — is the human's. The report's value is that the human's act is now an *informed* act, taken without reverse-engineering.

---

## 6 · Relationship to Existing Workflow Engine

**This protocol consumes workflow information. It does NOT replace — and does NOT add to — the workflow engine.**

| The protocol answers | The workflow engine decides |
|---|---|
| **"Who should act?"** | **"Who is allowed to act?"** |

Specifically:

- **Consumes:** the report reads `Current State` and active-grant context from the single authoritative work-item record (`workflow-state.php` — one authoritative record per work item). It authors none of it.
- **Does not replace:** `REGISTER` · `HANDOFF` · `START` · `ACCEPTANCE` all remain the workflow's transitions. The protocol adds no transition and no new state.
- **Does not pass ownership:** a `Recommended Next Actor` does not transfer ownership. Under the existing engine, ownership is **held for the successor on `HANDOFF` and passes only on `START`** — and `START` requires both a recorded human start act and the predecessor's recorded handoff (`G-3`). The protocol's recommendation is an input to that sequence, never a substitute for it.
- **Presupposes the lifecycle distinction:** the protocol assumes the already-recorded `HANDOFF` (work finished, next role takes over) vs `COMPLETE` (session formally closed) semantics (`EKS-04`). It does not re-litigate them.

**Design consequence:** a session that produces a completion report has done its coordination duty. Whether the recommended actor *may* start remains a workflow-engine and human-authority question the protocol never touches.

---

## 7 · Example — the current migration chain

Illustrative worked example using the migration chain that exposed the friction.

### Worked report

```
SESSION COMPLETION REPORT

Work Item:                KOS-AIP-GOV-STATE-DURABILITY (migration — independent architecture review)
Current Role:             Independent Reviewer
Session Identity:         <self declared process identity — recorded, not attested>
Completed Work:           Independent architecture review delivered (technical review).
Evidence Produced:        <the review artifact, by path>
Current State:            ACTIVE  (review slice delivered; not yet accepted)
Remaining Obligations:    acceptance / decision on the review's findings
Recommended Next Actor:   Governance
Reason:                   Review evidence exists but acceptance authority remains separate —
                          the reviewer cannot accept its own review.
Human Decision Required:  YES
Can Current Session Continue:  NO
Continuation Reason:      Reviewer cannot accept its own review; acceptance authority is separate.
```

### Why this is the correct recommendation

| Step | Actor | Act | Why this actor |
|---|---|---|---|
| Technical review delivered | Independent Reviewer | perform the review | separation requires an independent eye |
| Review evidence exists, not accepted | **Governance** | register the delivered review | **acceptance authority remains separate** from the producing reviewer |
| Decision on the findings | **PO/ARB** | decide | decision authority is reserved to the human PO/ARB |

The human's experience under the protocol: they receive the report, see `Next actor: Governance`, and take the single human act the report flags — approving the Governance step. They do **not** reconstruct the chain.

---

## 8 · Future Relationship to EKS-07

**This protocol is a small operational improvement. It is NOT the full solution.**

`EKS-07` (Multi-Process Coordination & Shared Work-State Integrity) is a **FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM** in the backlog — not commissioned, activation requires a human authorization act. It may later explore:

- multi-process coordination;
- shared work-state integrity;
- process identity;
- automated routing.

| | |
|---|---|
| **This protocol** | an **operational, advisory** practice — a session's obligation to report completion and recommend a next actor. It introduces **no** process-identity attestation, **no** shared-state mechanism, **no** routing automation. |
| **EKS-07 (future)** | an **architecture** exploration of the coordination boundary. If it is ever commissioned, this protocol's experience is an **input** it may consume or supersede — never an implementation of it. |

**Boundary:** if and when EKS-07 is commissioned, it must be authorized and designed on its own. Nothing in this protocol opens, pre-empts, or partially implements it. `Session Identity` remains self-declared and never attested (`INV-ATTR-1`/`INV-ATTR-2`) — this protocol records that fact, it does not build identity infrastructure.

---

## 9 · Verification Requirements

Before this protocol may be considered complete, all six must hold:

| # | Requirement | How this protocol satisfies it |
|---|---|---|
| **1** | Protocol does not create authority | The report is advisory-only; it recommends actors, assigns nothing, registers nothing |
| **2** | Human remains the final decision maker | §5 — the human `humanAct`, approval, and decision are the only acts that open the next step; AI cannot create `humanAct` (`G-2`/`R5b`) |
| **3** | No workflow transition rules changed | §6 — no transition added, removed, or altered; `REGISTER`/`HANDOFF`/`START`/`ACCEPTANCE` and `G-3` unchanged |
| **4** | No migration phase changed | the protocol is a completion-reporting practice; it re-sequences no migration step |
| **5** | No DV-1…DV-7 correction changed | the protocol touches none of the DV corrections; they are read as evidence only |
| **6** | No EKS-07 architecture introduced | §8 — EKS-07 remains a backlogged future exploration, not commissioned or implemented here |

---

## 10 · Test Cases

Four worked cases validating the deterministic rules.

### CASE 1 — Architecture author completes a correction

```
SESSION COMPLETION REPORT

Work Item:                <work item>
Current Role:             Architecture (author)
Session Identity:         <self declared process identity>
Completed Work:           correction delivered
Evidence Produced:        <correction artifact>
Current State:            ACTIVE
Remaining Obligations:    independent verification of the correction
Recommended Next Actor:   Independent Reviewer
Reason:                   Author cannot independently verify its own correction.
Human Decision Required:  YES
Can Current Session Continue:  NO
Continuation Reason:      Author cannot independently approve or verify its own correction.
```

### CASE 2 — Independent reviewer completes a review

```
SESSION COMPLETION REPORT

Work Item:                <work item>
Current Role:             Independent Reviewer
Session Identity:         <self declared process identity>
Completed Work:           review delivered
Evidence Produced:        <review artifact>
Current State:            ACTIVE
Remaining Obligations:    acceptance / decision
Recommended Next Actor:   Governance
Reason:                   Reviewer cannot accept its own review; acceptance authority is separate.
Human Decision Required:  YES
Can Current Session Continue:  NO
Continuation Reason:      Reviewer cannot accept its own review.
```

### CASE 3 — Governance completes a bounded review

```
SESSION COMPLETION REPORT

Work Item:                <work item>
Current Role:             Governance
Session Identity:         <self declared process identity>
Completed Work:           bounded review / registration delivered
Evidence Produced:        <registration artifact>
Current State:            ACTIVE
Remaining Obligations:    decision
Recommended Next Actor:   PO/ARB
Reason:                   Decision authority is reserved to the human PO/ARB.
Human Decision Required:  YES
Can Current Session Continue:  NO
Continuation Reason:      Governance cannot act as decision authority; the decision is the human's.
```

### CASE 4 — Session wants to continue itself

```
SESSION COMPLETION REPORT

Work Item:                <work item>
Current Role:             Implementation (author)
Session Identity:         <self declared process identity>
Completed Work:           next slice of an implementation obligation delivered
Evidence Produced:        <slice artifact>
Current State:            ACTIVE
Remaining Obligations:    remaining slices of the same responsibility
Recommended Next Actor:   Implementation (same session)
Reason:                   Same responsibility continues; no independence constraint applies.
Human Decision Required:  NO
Can Current Session Continue:  YES
Continuation Reason:      The next slice is the same responsibility; it is not a review, acceptance,
                          approval, or decision of the session's own output.
```

**Validation rule for Case 4:** `YES` must carry a rule-grounded `Continuation Reason`. A session may not answer `YES` because it prefers to continue, is already loaded, or would be convenient — only because no independence or authority bar applies.

---

## 11 · Status & Stop Condition

**This document is:** **ADOPTED OPERATIONAL PRACTICE** *(PO/ARB act 2026-08-22 · registration: `2026-08-22-KOS-AIP-GOV-STATE-DURABILITY-ADR-session-completion-handoff-protocol-adoption-registration.md`)*

**It is NOT:** accepted architecture · workflow engine change · migration requirement · EKS-07 implementation.

**STOP.** After producing this protocol:

- ⛔ do not implement automation;
- ⛔ do not modify the workflow engine;
- ⛔ do not modify the migration plan;
- ⛔ do not modify any DV correction;
- ⛔ do not create an EKS-07 implementation.

**Next steps (post-adoption):** ① the producer applies the accepted **F1/F3 amendment** in an authorized next revision (F2 is recorded in the adoption registration) · ② **agent templates** (`.claude`/`.codex`) only after Governance acceptance — *agents may follow a protocol only after Governance has accepted it* · ③ **continue the `KOS-AIP-GOV-STATE-DURABILITY` migration** (next-actor ambiguity operationally solved).

---

## 12 · Traceability

Work item `KOS-AIP-GOV-STATE-DURABILITY-ADR` (operational improvement) · the coordination friction observed during the `KOS-AIP-GOV-STATE-DURABILITY` work · `KOS-AIP-GOV-STATE-DURABILITY-DECISION.md` and `2026-08-19-KOS-AIP-GOV-STATE-DURABILITY-po-arb-position-registration.md` *(producer bar · `G-2`/`R5b` `humanActRef` · decision authority reserved to the human PO/ARB)* · `workflow-state.php` *(states · `REGISTER`/`HANDOFF`/`START`/`COMPLETE` · ownership passes only on `START` · `G-3`)* · `EKS-04` *(HANDOFF vs COMPLETE)* · `EKS-07` *(multi-process coordination — FUTURE ARCHITECTURE EXPLORATION, not commissioned)* · six-role operating model adoption *(declared-role vocabulary; roles ≠ bounded contexts)* · `INV-ATTR-1`/`INV-ATTR-2` *(self-declared identity, recorded not attested)* · `ES-005.4` *(reuse the declared role vocabulary and the existing workflow engine; create no second)* · `scripts/doc-placement.php` *(product-specific · knowledgeos → `docs/knowledgeos`, exit 0)*
