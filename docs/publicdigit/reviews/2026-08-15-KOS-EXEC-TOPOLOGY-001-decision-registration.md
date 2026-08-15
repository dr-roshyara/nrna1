# KOS-EXEC-TOPOLOGY-001 — PO/ARB decisions registered

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance) · **Acts:** `DEC-1` and `DEC-2`, PO/ARB, 2026-08-15
**Registration only.** No implementation · no mechanism change · nothing invented beyond the ruling.

> **⚠️ Disclosure, per the duty this very ruling creates (`A-4.3`).** This process acted as **Session 4 (Architecture)** producing the proposal under review (`86b2e536`), and as **Session 2 (Governance)** reviewing it (`e77fa724`) and registering these decisions. **`DEC-2` permits this with disclosure.** Stated here so the overlap is on the record at the point of registration, not only in the earlier review.

---

## 1 · Decisions, registered verbatim

### `DEC-1` — **ADOPT WITH CONDITIONS**

> *"I adopt the two-terminal model as an operating convention/documentation only, subject to C-1–C-4. It is explicitly not an authority mechanism, platform requirement, or encoded architectural rule. Authority remains determined by the governed model: assignment, grant, human START and workflow state. R-34 remains the actual independence rule: a process that implemented a work item may not independently verify that same implementation. Architecture interruptions shall use Option A (separate work item) or Option C (sequential governed handoff). Option B — concurrent same-work-item lanes — is not adopted. No change to AST-015 or AST-016 is authorized by this decision."*

### `DEC-2` — **PERMIT WITH DISCLOSURE**

> *"I permit a process to act in a Governance capacity on evidence it previously produced in an Engineering capacity under the current governance model, because no adopted rule currently prohibits that sequence and the authoritative record cannot presently attribute Governance acts to a process identity. However, such overlap must be explicitly disclosed in the Governance review artifact whenever the reviewing process also produced the evidence under review. This decision does not establish that self-review is desirable or permanently acceptable. It establishes an auditable interim convention until process attribution and the stronger independence question are separately examined."*
>
> *"Create a separate follow-up work item to investigate: authoritative process attribution for Governance acts; whether the stronger Engineering→Governance independence invariant should be adopted; how such an invariant could be machine-verifiable rather than declaration-based."*
>
> *"No implementation is authorized under this decision. No modification to AST-015, AST-016, SESSION_START, hooks, locks, or workflow semantics is authorized."*

## 2 · What Governance registered

| # | Act | Where | Result |
|---|---|---|---|
| 1 | **Amendment `A-4`** — the convention (`DEC-1`), conditions `C-1`–`C-4`, the interrupt rule, and the `DEC-2` disclosure duty | `KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` (the **canonical home**, alongside `A-1`–`A-3`) | registered |
| 2 | Header pointer to `A-4` | same document, header block | registered |
| 3 | **Follow-up work item `KOS-GOV-ATTRIBUTION-001`** (`architecture-decision`, four canonical roles) + versioned intake artifact | mechanism record + `…-KOS-GOV-ATTRIBUTION-001-intake.md` | **OPEN · 0 sessions · 0 grants — uncommissioned** |
| 4 | `S4-architecture-topology` `COMPLETE` | machine record seq 4 | recorded earlier by Governance (G-1); ownership released |

**Extended, not copied.** The convention was registered as an amendment to the document that already holds `A-1.4`/`D-5` and accepted principle 5. Creating a separate "execution topology" rule document would have produced a second home for one rule (`ES-005.4`).

**Dual record for the follow-up**, deliberately: under `E-1` the runtime record is untracked, so a record-only work item would be invisible to git.

## 3 · What the decisions do NOT do — recorded so it cannot drift

- ❌ The two-terminal model is **not** an authority mechanism, platform requirement, or encoded architectural rule. **`C-1` is binding.**
- ❌ **No** `AST-015` / `AST-016` / `SESSION_START` / hooks / locks / workflow-semantics change is authorized. **No implementation** is authorized.
- ❌ `DEC-2` does **not** establish that self-review is desirable or permanently acceptable — it is an **interim, auditable convention.**
- ❌ Option **B** (concurrent same-work-item lanes) is **not adopted.**
- ❌ **Not resolved and not folded in:** `V-3` (`KOS-ACTIVATION-REPORTING-001`) · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the successor-registration bootstrap gap.
- ❌ The standing prohibition on wiring `AST-016` into `SESSION_START` while `V-3` is unresolved **stands, unweakened.**
- ❌ **No terminals were created.** Physical terminal separation is **not required** by the governing model; the convention is a habit, not a control.

## 4 · Effective immediately

**The `A-4.3` disclosure duty is in force now.** Whenever a process acts in a Governance capacity on evidence it produced in an Engineering capacity — `verification→governance`, `architecture→governance`, or any other such pairing — **the Governance review artifact must disclose the overlap explicitly. Non-disclosure is a governance defect.**

**`C-3` applies from the next assignment onward.** `executionContext` should distinguish processes well enough to evidence `R-34`. **No existing record is rewritten** — the log is append-only, and repairing sound history is exactly the failure mode this programme has twice declined to commit.

## 5 · Work-item status

**`KOS-EXEC-TOPOLOGY-001`: every commissioned question is answered and both decisions are registered. The authorized scope is discharged.**

- Machine record: **`OPEN`** · `mutationOwner: NULL` · `S4-architecture-topology` `COMPLETED`.
- **Governance/documentary closure: NOT YET DECLARED.**

> **Closure is deliberately left to an explicit act.** Nothing remains in scope, and Governance could close it — but every prior lifecycle act in this programme (qualification, adoption, closure) has been an explicit PO/ARB commission, and Governance does not assume that authority unbidden. **One word closes it.**
>
> *Reminder of the binding vocabulary ruling (2026-08-15): when it is closed, it must be stated with its plane named — **governance/documentary closure: CLOSED · authoritative workflow-machine state: `OPEN`**, because `AST-015` has no closure transition (`O-CLOSURE-VOCAB`).*

---

## Traceability

`DEC-1`/`DEC-2` (PO/ARB 2026-08-15, §1 verbatim) · Amendment `A-4` (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · ADP `86b2e536` · governance review `e77fa724` · `S4-architecture-topology` seq 1–4 · `G-KOS-TOPO-ARCH` · new work item `KOS-GOV-ATTRIBUTION-001` (OPEN, 0/0) · `A-1.4`/`D-5` · accepted principle 5 · `INV-ORCH-1` · `R-34` · `R8` · `Inv C`/`R1` · `D-6` · `E-1` · `V-3` · `O-CLOSURE-VOCAB` · `ES-005.4`
