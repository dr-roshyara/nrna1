# KOS-GOV-ATTRIBUTION-001 — Process attribution for Governance acts, and the Engineering→Governance independence question

**Created:** 2026-08-15 by Governance · **Workflow:** `architecture-decision` · **Roles:** governance · architecture · implementation · verification
**State:** OPEN · **NOT commissioned** — 0 sessions, 0 grants, nothing started.
**Origin:** PO/ARB ruling **DEC-2**, 2026-08-15 (`KOS-EXEC-TOPOLOGY-001`), which permitted the overlap *with disclosure* as an **auditable interim convention** and commissioned this investigation.

> **This work item holds three open questions. It decides none of them, and it authorizes nothing.**

---

## 1 · The commissioning act, verbatim

> *"Create a separate follow-up work item to investigate:*
> *— authoritative process attribution for Governance acts;*
> *— whether the stronger Engineering→Governance independence invariant should be adopted;*
> *— how such an invariant could be machine-verifiable rather than declaration-based.*
> *No implementation is authorized under this decision. No modification to AST-015, AST-016, SESSION_START, hooks, locks, or workflow semantics is authorized."*
> — **PO/ARB, 2026-08-15**

## 2 · Why this exists — the measured gap

**FACT.** `A-1.4`/`D-5` (adopted) prohibits `implementation→verification` of the same work. It is **silent** on a process acting in a **Governance** capacity on evidence it produced in an **Engineering** capacity.

**FACT.** That sequence is not hypothetical. It occurred, and was disclosed, in the governance review that produced `DEC-2`: the same process acted as Architecture (`86b2e536`) and then as Governance reviewing that proposal (`e77fa724` §4).

**FACT — the enforceability gap, and the reason `DEC-2` chose disclosure over prohibition.** A prohibition would be **unauditable from the record today**, for two independent reasons:

1. **Governance is not a registered session.** Its acts appear only as `recordedBy: "governance"` on transitions and `registeredBy: "governance"` on grants. **The record cannot say *which process* performed a governance act** — only that a governance-role actor did.
2. **`executionContext` does not distinguish processes.** At the corrective increment both the implementation and verification lanes recorded the identical literal `shared-worktree`, so `R-34` had to be evidenced by **commit authorship** rather than by the record. *(`C-3` of `A-4` begins to address this by convention, from the next assignment onward.)*

> **Consequence:** the `A-4.3` disclosure duty is **declaration-based**. It is honest and auditable *after the fact* in prose, but **not machine-verifiable**. That is precisely what this work item exists to examine.

## 3 · The three questions

**Q-1 · Authoritative process attribution for Governance acts.**
Should the record be able to attribute a governance act to a process identity, and if so how? Sub-questions the investigation must confront: does Governance become a **registered session** (a significant model change — today it is an authority *function*, deliberately not a session); or does attribution attach to the **transition** rather than to a session; or is `executionContext` (+ `C-3`) sufficient? **Note the tension:** `INV-DISC-2` and the accepted principle 5 both hold that **process/terminal identity must not become the authority mechanism** — so attribution must be *evidential*, never *authorising*. **Reconciling those two is the heart of Q-1.**

**Q-2 · Should a stronger Engineering→Governance independence invariant be adopted?**
Does `R-34`'s purpose — *engineering never accepts its own work* — extend from **acceptance** (already reserved to the PO/ARB) to **review**? Consider the cost honestly: with a small number of processes, a blanket prohibition could make ordinary governance work impossible, since Governance routinely reviews evidence produced in the same programme. **The answer is not obviously "yes".**

**Q-3 · How could such an invariant be machine-verifiable rather than declaration-based?**
Only meaningful if Q-1 yields attribution. Any mechanism change is a **dependency requiring separate authorization** — `AST-015` is qualified, and `KOS-AI-ORCH-001` reserves mechanism evolution to separate analysis and authorization.

## 4 · Binding constraints on this work item

- **No implementation** is authorized. **No modification** to `AST-015`, `AST-016`, `SESSION_START`, hooks, locks or workflow semantics is authorized.
- Any required mechanism change is **recorded as a dependency**, never designed here.
- **`DEC-2`'s interim convention stands** until this work item concludes and its outcome is separately decided: the overlap is **permitted with mandatory disclosure** (`A-4.3`).
- **`DEC-2` explicitly did not establish that self-review is desirable or permanently acceptable.** This work item must not treat the interim permission as a settled position.

## 5 · Relationship to existing findings — cross-referenced, none resolved here

| Item | Relationship |
|---|---|
| **`C-3`** of `A-4` (`executionContext` distinguishes processes) | **Directly relevant** — a partial, convention-level down payment on Q-1. Adopted; not a solution |
| **`V-3`** / `KOS-ACTIVATION-REPORTING-001` | Separate. OPEN, uncommissioned |
| **`E-1`** (authoritative record untracked/gitignored) | **Thematically adjacent** — attribution is worth little if the record carrying it has no history. Still a separate item |
| **`O-CLOSURE-VOCAB`** | Separate. Shares the theme *"the record cannot express states the governance model relies on"* — **theme noted, items not merged** |
| **`D-6`** (read-only participation) | Separate, but in the same family: the record cannot express a participation *mode* |
| **successor-registration / bootstrap gap** | Separate |

## 6 · Proposed next actor

**The PO/ARB** — to commission (or defer) this investigation. **Architecture would be the natural first role**, as with `KOS-EXEC-TOPOLOGY-001`.

**Nothing is commissioned by this artifact. No assignment, no grant, no session.**

---

## Traceability

PO/ARB `DEC-2` (2026-08-15, verbatim §1) · `A-4.3` disclosure duty and its recorded limitation · disclosed instance `e77fa724` §4 · ADP `86b2e536` · `A-1.4`/`D-5` and accepted principle 5 · `INV-DISC-2` · `R-34` · `G-2` (Governance sole Authority-State writer) · `E-3` and the `shared-worktree` evidence (`3884d81d`) · parent work item `KOS-EXEC-TOPOLOGY-001`
