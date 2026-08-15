# KOS-GOV-ATTRIBUTION-001 — Architecture commission registered

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance) · **Work item:** `KOS-GOV-ATTRIBUTION-001` (`architecture-decision`, four canonical roles)
**Status: lane ACTIVE — the human START was registered as the commission directed. Architecture may begin.**

---

## 1 · The commissioning act, registered verbatim

> **"Commission KOS-GOV-ATTRIBUTION-001.**
>
> *Authorize Architecture to investigate, without implementing or modifying any mechanism:*
> *— how Governance acts can be authoritatively attributed to the process that performed them;*
> *— whether the existing Engineering → Governance overlap convention should remain, be strengthened, restricted, or replaced by a stronger independence invariant;*
> *— how any resulting independence invariant could be machine-verifiable rather than declaration-based.*
>
> *The investigation must preserve the existing principle that process or terminal identity is evidential, never authorising.*
>
> *No implementation, AST-015/AST-016 modification, SESSION_START modification, hook, lock, workflow-semantic change, or startup wiring is authorized.*
>
> *Architecture must produce a bounded Architecture Decision Proposal with alternatives, consequences, recommendation, and explicit dependencies.*
>
> *Governance shall register the assignment, grant, predecessor relationship, and human START before Architecture begins."*
> — **PO/ARB, 2026-08-15**

## 2 · What was registered

| Act | Value |
|---|---|
| **Assignment** | seq **1** — `S4-architecture-attribution`, role `architecture`, predecessor `null` |
| **Grant** | **`G-KOS-ATTR-ARCH`** — `AUTHORIZED`, registered at assignment time (E-14) |
| **Predecessor relationship** | seq **2** — bootstrap `HANDOFF` (`from: null`; ownership was unheld), token = the DEC-2 intake (`c9915445`) + this registration |
| **Human START** | seq **3**, `recordedBy: human` — **as the commission directed.** `G-3` complete: recorded handoff ∧ recorded human act |

**Verified after registration:** `identity` → role `architecture`, **ACTIVE**, linkage `G-KOS-ATTR-ARCH` · `AST-016` → **RESOLVED · operable: true · ACTIVE (mutation owner)**.

## 3 · 🆕 First application of `C-3` — and an honest caveat

`A-4`'s condition `C-3` (adopted 2026-08-15) requires `executionContext` to distinguish processes well enough to evidence `R-34`, **from the next assignment onward. This is that assignment.**

```
previous convention :  "shared-worktree"                        ← a LOCATION; cannot distinguish processes
this assignment     :  "claude-code-session:fbc084f0 (shared-worktree; …)"   ← names the PROCESS
```

> **⚠️ The caveat matters more than the improvement, and it is this work item's own subject.** The label is **self-declared**: Governance wrote what the acting process asserts about itself. **The record cannot verify it.** A process could write any string.
>
> **So `C-3` improves *legibility*, not *verifiability*.** That is precisely the distinction `Q-3` exists to examine, and Architecture should treat this first application as **evidence of the gap, not as a solution to it.**

**No earlier record was rewritten.** The log is append-only, and prior assignments keep `shared-worktree` as recorded.

## 4 · Grant scope — `G-KOS-ATTR-ARCH`

**Deliverable: a bounded Architecture Decision Proposal with alternatives, consequences, recommendation and explicit dependencies. Investigation only.**

**IN SCOPE**

- **`Q-1`** How Governance acts can be **authoritatively attributed** to the process that performed them.
- **`Q-2`** Whether the existing Engineering→Governance overlap convention (`A-4.3`, permit-with-disclosure) should **REMAIN · be STRENGTHENED · be RESTRICTED · or be REPLACED** by a stronger independence invariant.
- **`Q-3`** How any resulting invariant could be **machine-verifiable rather than declaration-based**.

> **🔒 BINDING CONSTRAINT:** the investigation **must preserve the principle that process or terminal identity is EVIDENTIAL, NEVER AUTHORISING** (`INV-DISC-2` · accepted principle 5 · `A-4` `C-1`).

**EXCLUDED:** implementation of any kind · modifying `AST-015` or `AST-016` · `SESSION_START` modification or startup wiring · hooks · locks · leases · workflow-semantic change · resolving `V-3`, `D-6`, `E-1`, `O-CLOSURE-VOCAB` or the bootstrap gap · reopening `KOS-SESSION-DISCOVERY-001` or `KOS-EXEC-TOPOLOGY-001` · choosing on the PO/ARB's behalf · self-certification.

**Where a change to a qualified mechanism would be required: record it as an EXPLICIT DEPENDENCY requiring separate authorization — do not design it.**

## 5 · Evidence Architecture inherits

**The gap being investigated, stated as measured facts:**

1. **Governance is not a registered session.** Its acts appear only as `recordedBy: "governance"` on transitions and `registeredBy: "governance"` on grants. **The record cannot say *which process* performed a governance act** — only that a governance-role actor did.
2. **`executionContext` was, until this assignment, a location** (`shared-worktree` on both lanes of the corrective increment), so `R-34` had to be evidenced by **commit authorship** rather than by the record.
3. **`A-4.3`'s disclosure duty is therefore declaration-based** — honest and auditable in prose after the fact, but **not machine-verifiable**.
4. **`R-34`'s actual text** (`A-1.4`/`D-5`, adopted): *a process that implemented a work item may not independently verify that same implementation.* It prohibits **only** `implementation→verification`.
5. **Two live instances of the class already exist**, both disclosed: `architecture→governance` at `KOS-EXEC-TOPOLOGY-001` (`e77fa724` §4, and again at decision registration).

**Two tensions Architecture must confront rather than resolve by assumption:**

- **`Q-1`'s hard part:** attribution must be **evidential, never authorising**. Making Governance a registered session would be a significant model change — Governance is today an authority *function*, deliberately not a session — and would risk turning identity into authority, which is forbidden. **Reconciling those is the question.**
- **`Q-2` is not obviously "yes".** With a small number of processes, a blanket Engineering→Governance prohibition could make ordinary governance work **impossible**, since Governance routinely reviews evidence produced within the same programme. **The investigation must not start from the assumption that the stronger invariant is desirable.**

## 6 · ⚠️ A structural observation Governance owes the PO/ARB now

**If this same process performs the Architecture investigation, then the eventual Governance review of its proposal will itself be another `A-4.3` instance — on the very work item examining whether that should be permitted.**

That is not a defect and it is not disqualifying: `A-4.3` **permits** it, provided the overlap is disclosed, and the disclosure duty is already being honoured. **But the PO/ARB may prefer to route this particular investigation to a different process**, so that the conclusions about self-review are not themselves produced under self-review.

> **Governance raises this as a routing option, not a recommendation, and has not acted on it.** The lane is registered and ACTIVE as commissioned. **If the PO/ARB wants a different process to hold it, say so and Governance will register the reassignment** as a new `SessionAssignment` under `R8`. *(Also worth noting: `R-34` itself is **not** engaged here — nothing was implemented, so there is no implementer to exclude.)*

## 7 · Next actor

> **Session 4 (Architecture)** — run the startup check, then investigate `Q-1`–`Q-3` within `G-KOS-ATTR-ARCH` only, and deliver the bounded ADP.

**Carried into the lane:** `operable ≠ authorized` · mechanism change is a **recorded dependency requiring separate authorization**, never designed · the deliverable is a **PROPOSED** boundary for human approval · **no self-certification** · **Architecture does not complete its own assignment** — that is a Governance act (`G-1`).

**Sessions 1 and 3 remain stopped. `V-3`, `D-6`, `E-1`, `O-CLOSURE-VOCAB` and the bootstrap gap are untouched and not folded in. `KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed and are not reopened.**

---

## Traceability

PO/ARB commission 2026-08-15 (§1 verbatim) · `KOS-GOV-ATTRIBUTION-001` seq 1 (REGISTER) · `G-KOS-ATTR-ARCH` (AUTHORIZED, E-14 pattern) · seq 2 (bootstrap HANDOFF) · seq 3 (human START) · origin `DEC-2` and intake (`c9915445`) · `A-4.3` disclosure duty and `C-3` (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · `A-1.4`/`D-5` and `R-34` · `INV-DISC-2` · accepted principle 5 · `G-2` · disclosed instances `e77fa724` §4 and `c9915445` · `E-3` (`3884d81d`)
