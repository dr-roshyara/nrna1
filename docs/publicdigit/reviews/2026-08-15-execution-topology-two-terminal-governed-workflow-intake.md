# Execution Topology — Two-Terminal Governed Workflow

**Date:** 2026-08-15 · **Recorded by:** Session 2 (Governance) · **Status: PROPOSED OPERATING MODEL — not adopted, not mandatory, not a platform requirement**
**Nature:** governance/architecture **intake** only. **No work item created. No grant issued. No machine transition appended. No code, mechanism or registry changed.**

> **This artifact records a proposal and the evidence bearing on it. It decides nothing.**
> **Proposed next actor: ARCHITECTURE — not Implementation.** Architecture is *not* commissioned by this artifact.

---

## 0 · Evidence discipline — two statements in the commissioning prompt that the record does not support

Per the evidence rule, I inspected the machine record and the approved architecture **before** recording. **Two statements are reported rather than repaired, and neither has been written into the proposal as fact.**

### 0.1 `V-4` has no referent in this workstream — **NOT RECORDED as a finding**

The prompt cross-references *"V-4 — workflow-state handoff warning observation."* **No such finding exists.** The `KOS-SESSION-DISCOVERY-001` finding series is **`V-1`, `V-2`, `V-3` only** (Session 1's two verification reports, `beb26177` and `b6b8b0bd`). Every `V-4` string in the repository belongs to unrelated documents — Round-38C capture vectors and PKS Phase-II vocabulary risks — with no connection to workflow state or handoffs.

> **Governance has not invented a `V-4`, has not renumbered anything, and has not quietly dropped the reference.** If a handoff-warning observation was genuinely made, it exists **outside the governed record** and must be submitted before it can be cross-referenced. **Until then there is nothing to relate this proposal to.**

### 0.2 The single-mutation-owner rule is an **approved invariant**, not a discovered limitation

The prompt presents this as *"Session 4 discovered that the current workflow mechanism permits only one ACTIVE mutation owner per work item"*, to be recorded as a limitation. **The record says otherwise, and the difference matters for what Architecture is being asked to decide.**

It is a **deliberate, approved, contract-pinned architectural invariant** (`2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md`):

> **Inv C:** *"ONE work item → one governed mutable execution context → **exactly one mutation owner at a time**; **reads concurrent**; ownership moves only by explicit governed transition."*
> **R1** (contract, Increment 1): *"ownership must be **unrepresentable** as two owners."*

**Consequence for this proposal — and it is favourable:** *"reads concurrent"* is **already approved**. A read-only assurance lane running concurrently is **not** in tension with Inv C. The obstacle the proposal faces is narrower than "the mechanism forbids concurrency", and §6 states what it actually is.

**On the attribution:** I can neither corroborate nor refute that Session 4 made this observation — no Session 4 activity is recorded on any current work item (`S4-architecture-discovery` is `COMPLETED` from the architecture stage). **I have assessed the observation on the evidence I gathered myself, not on its attribution.**

---

## 1 · Problem / motivation

Across `KOS-AI-ORCH-001` and `KOS-SESSION-DISCOVERY-001`, role independence has been maintained by **convention and by session identity**, not by execution topology. The corrective increment made the cost visible: implementation and verification ran in the **same execution context** (`executionContext: shared-worktree` on both `S3-impl-discovery-corrective` and `S1-verify-discovery-corrective`).

Governance recorded this at review as finding **`E-3`**: independence there rested on distinct session identities, immutable roles (R8), the recorded handoff, the human START, and Session 1's own `R-34` authorship self-check — **not on process isolation.** That was judged *sufficient* for that review, but it is **weaker than the phrase "independent verification" ordinarily implies**, and the weakness is structural rather than incidental.

**The question this proposal raises:** should process independence for the assurance lane be a matter of topology rather than of discipline?

## 2 · Observed operating model (today, as evidenced)

- One execution environment has hosted Governance, Architecture, Implementation **and** Verification, sequentially, across a work item's life.
- Separation has been achieved by governed assignment, immutable role, handoff and human START — **all record-level, none process-level.**
- `R-34` independence has held **because each session checked its own authorship**, not because the environment made violation impossible.

## 3 · Proposed two-terminal topology — **PROPOSED, NOT MANDATORY**

### Production lane
One execution environment may host, **sequentially**: **Governance → Architecture → Implementation.**
Each role remains independently governed: registered assignment · appropriate grant · human authorization where required · immutable role and assignment boundaries.

> **The terminal NEVER grants authority.**

### Assurance lane
A **separate** execution environment hosts **Independent Verification**.

> **The process that implemented a change must not subsequently serve as its independent verifier, even under a new assignment.** This is the topological form of `R-34`.

### Governance
**Governance is an authority function, not a terminal identity.** It may operate from either environment. **Location and process confer no authority whatsoever.**

## 4 · Role independence rules

**Co-location is not authority.** A process hosting three roles sequentially does **not** thereby hold three authorities. Authority continues to derive **solely** from the governed assignment / grant / human-start model.

> **Therefore the process must consult the machine record before acting in each role — every time.** This is exactly what `AST-016` exists to answer, and it is the strongest available argument that the two-terminal model needs **no new mechanism**: the check already has a qualified, adopted instrument.

## 5 · Verification independence

Recorded as the stronger principle the proposal asks for:

> **The implementation process and the independent verification process must remain process-independent, even when they operate against the same repository and work item.**

**Not to be weakened for convenience.** Governance notes the precedent honestly: this principle was **not** met in the corrective increment (`E-3`), and the work was nonetheless accepted — so adopting it now would **raise** the standard rather than restate current practice. That is a real change and belongs to Architecture, not to a governance recording.

## 6 · The actual concurrency constraint — measured, not assumed

Governance probed `AST-015` directly in an isolated scratchpad (`--dir`; the authoritative estate was never written to). **The result is not what the prompt anticipated, and it is material.**

```
two bootstrap handoffs while no owner exists   →  BOTH ACCEPTED
START impl                                     →  ACCEPTED
START verif                                    →  ACCEPTED

folded result:   impl  ACTIVE
                 verif ACTIVE          ← TWO simultaneously ACTIVE sessions
                 mutationOwner: 'verif'  ← single-valued; the LAST START took it
```

**Findings, stated exactly:**

1. **The mechanism already permits two concurrently `ACTIVE` assignments in one work item.** "Only one active lane" is **false**. Inv C constrains the **mutation owner**, which remains single-valued — the invariant holds exactly as approved.
2. **⚠️ `START` silently transfers mutation ownership.** It has **no precondition on the current owner**. The second `START` moved ownership away from `impl` **without any refusal**, while `impl` remained `ACTIVE` and would still report itself active. **A lane can therefore lose mutation ownership without being told.**
3. **A read-only lane acquires mutation ownership merely by starting.** There is no way to start a session read-only.

> **Finding 3 is not new — it is the already-recorded `O-1`/`O-4` gap surfacing in a new context.** `AST-016` states it in every report: *"readOnlyParticipation: NOT EXPRESSIBLE by the current record — governed by convention (O-1/O-4). Vocabulary cure is dependency `D-6`, separately governed."*
>
> **This is the true obstacle to the two-terminal model — not Inv C.** An assurance lane is conceptually read-only, and *"reads concurrent"* is already approved; but the record cannot express a read-only participant, so starting the assurance lane makes it the mutation owner and silently disowns the production lane.

**Classification:** finding 2 is recorded as an **OBSERVATION requiring architectural judgment**, deliberately **not** as a defect. Inv C is not violated — ownership never becomes two-valued. Whether unguarded ownership transfer is *intended* (ownership passes to whoever is most recently human-started) or *an oversight* is a question about approved semantics, and **Governance does not rule on it.**

## 7 · Alternatives — recorded, **not** chosen

| | Alternative | What it would mean |
|---|---|---|
| **A** | **Separate work item per concurrent lane**, with explicit cross-reference | Uses the mechanism as approved and as already proven: *"Between work items — isolation, free concurrency."* Needs **no** change to the qualified mechanism. Cost: the relationship between lanes lives in cross-references rather than in one record |
| **B** | **Extend the workflow model** to support concurrent assignments/activity within one work item | **Would require a separate architecture/governance decision, because it changes the QUALIFIED workflow mechanism `AST-015`** — which is operationally qualified and carries `KOS-AI-ORCH-001`'s ruling that mechanism *evolution* requires separate future analysis and authorization. Would also have to confront `D-6` (read-only vocabulary) and finding 2 above |

**Governance chooses neither, and recommends neither.** Both are recorded so Architecture inherits the option space rather than a preference. Governance notes only that **A and B are not equivalent in cost**: A is a convention over an unchanged qualified mechanism; B reopens a qualified mechanism.

## 8 · What is explicitly NOT decided

- ❌ The two-terminal model is **not** adopted, **not** mandatory, **not** a platform or implementation requirement, **not** a new architectural constraint, **not** a new capability.
- ❌ **No** permission to modify the orchestration mechanism, and **no** conversion of this proposal into code or a binding mechanism rule.
- ❌ **Not chosen:** alternative A or B · any remedy for `V-3`, `E-1`, `O-CLOSURE-VOCAB` or the bootstrap gap · any disposition of §6 finding 2 · whether §5's stronger independence principle is adopted.
- ❌ **Not created:** a work item, an assignment, a grant, a session, a hook, `SESSION_START` wiring, concurrent-session support.
- ❌ **This artifact is not a human approval and confers no implementation authority.**

## 9 · Relationship to existing findings — cross-referenced, none resolved or reopened

| Finding | Relationship | Status |
|---|---|---|
| **`V-3`** activation/reporting specification gap | Separate. Its work item `KOS-ACTIVATION-REPORTING-001` remains OPEN and uncommissioned | 🔴 unresolved · **not combined** |
| **`V-4`** | **Has no referent — see §0.1** | ⛔ **not recorded** |
| **`E-1`** authoritative runtime record untracked | Separate. Compounds generally, not specifically | 🔴 unresolved · **not combined** |
| **successor-registration / bootstrap gap** | Separate | 🔴 unresolved · **not combined** |
| **`O-CLOSURE-VOCAB`** machine representation of closure | Separate. Shares a *theme* with §6 (the record cannot express states the governance model relies on) — **theme noted, items not merged** | 🔴 unresolved · **not combined** |
| **`E-3`** shared execution context at the corrective increment | **This is the motivating evidence** (§1) — already recorded at review, not a new finding | ✅ recorded · motivating |
| **`O-1`/`O-4`/`D-6`** read-only participation not expressible | **Materially load-bearing here** (§6 finding 3) — the actual obstacle | 🔴 pre-existing · **surfaced, not resolved** |
| **`KOS-SESSION-DISCOVERY-001`** | **NOT reopened.** Governance/documentary closure: CLOSED · machine state: `OPEN` | 🔒 closed |

## 10 · Proposed next actor — **ARCHITECTURE**

Architecture should determine whether this operating model requires:

1. **no platform change** — it is an operating convention, discharged by alternative A and by the already-approved *"reads concurrent"*; or
2. **documentation / governance only** — the model is recorded as a convention with `R-34` restated in topological terms; or
3. **a new platform capability** — which would engage `D-6` and, if alternative B were taken, the qualified mechanism itself.

**Architecture should also dispose of §6 finding 2** (unguarded ownership transfer at `START`): intended semantics, or an oversight requiring a separate work item?

> **Architecture is NOT commissioned by this artifact.** Commissioning is a separate PO/ARB act. **Governance stops here.**

---

## Traceability

Approved invariant Inv C / R1 (`2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md` §§38, 55, 158) · `E-3` and the `shared-worktree` evidence (governance review `3884d81d`) · `O-1`/`O-4`/`D-6` via the `READ_ONLY_PARTICIPATION` constant in `session-resolve.php` · concurrency probe (scratchpad `--dir`, authoritative estate untouched): two bootstrap handoffs accepted, both `START`s accepted, two `ACTIVE`, `mutationOwner` single-valued and reassigned by the later `START` · `V-1`/`V-2`/`V-3` series (`beb26177`, `b6b8b0bd`) — **no `V-4`** · `KOS-SESSION-DISCOVERY-001` closure (`95c90a68`) and PO/ARB vocabulary ruling (`5f83cbe0`) · `KOS-ACTIVATION-REPORTING-001` (OPEN, 0 sessions, 0 grants)
