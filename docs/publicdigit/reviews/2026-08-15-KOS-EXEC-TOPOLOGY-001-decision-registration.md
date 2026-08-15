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

## 5 · Closure — declared by the PO/ARB, 2026-08-15

**The closing act, registered verbatim:**

> *"I declare KOS-EXEC-TOPOLOGY-001 CLOSED. Its authorized scope is discharged: DEC-1 and DEC-2 have been registered, the architectural investigation is complete, and the resulting governance decisions are recorded. This closure does not mean that KOS-GOV-ATTRIBUTION-001, V-3, D-6, E-1, O-CLOSURE-VOCAB, or the bootstrap gap are resolved. Those remain separate follow-up matters and are not reopened or included in this closure. … No implementation, mechanism change, AST-015/AST-016 change, or startup wiring is authorized by this closure."*

Stated in the ruled form (binding vocabulary ruling, 2026-08-15):

> ## `KOS-EXEC-TOPOLOGY-001`
> ### **Governance / documentary closure: CLOSED**
> ### **Authoritative workflow-machine state: `OPEN`**
>
> `OPEN` because **`AST-015` has no closure transition** — `{"type":"CLOSE"}` is refused with *"no such edge exists in the machine"*, and `workItemState` is only ever `OPEN` or `STOPPED` (`O-CLOSURE-VOCAB`). **`STOP` was not misused**: `STOPPED` means *halted*, is sticky, and exits only via `CONTINUATION`.

**⚠️ Both lines must be quoted together. No document may state or imply that the machine record says `CLOSED`. It does not, and it cannot.**

### Closure evidence

| Requirement | Evidence | Status |
|---|---|---|
| Commission answered in full | ADP `86b2e536` — all eight commissioned questions (Q1–Q8) addressed | ✅ |
| Architecture within grant, no self-certification | design-only; `S4` neither completed itself nor created a handoff | ✅ |
| Governance review independent of the PO decision | `e77fa724`; load-bearing premise (`A-1.4` = adopted rule) verified at source | ✅ |
| Human decisions obtained and registered verbatim | `DEC-1`, `DEC-2` — §1 above | ✅ |
| Decisions given effect | Amendment `A-4` in the canonical home + header pointer | ✅ |
| Follow-up carried, not absorbed | `KOS-GOV-ATTRIBUTION-001` created — OPEN, 0 sessions, 0 grants | ✅ |
| Assignment lifecycle correct | `S4-architecture-topology` `COMPLETE` by **Governance** (seq 4, G-1); `mutationOwner: NULL` | ✅ |
| Qualified mechanisms unharmed | `AST-015` sha256 `e19705ce` · `AST-016` sha256 `00c68cc9` — unchanged throughout | ✅ |
| Engineering did not accept its own work | Architecture proposed · Governance reviewed · **PO/ARB decided and closed** | ✅ |

### What this closure does NOT do

**Explicitly not resolved, not reopened, not included:** `KOS-GOV-ATTRIBUTION-001` (OPEN, uncommissioned) · `V-3` / `KOS-ACTIVATION-REPORTING-001` (OPEN, uncommissioned) · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the successor-registration bootstrap gap.

**No implementation, mechanism change, `AST-015`/`AST-016` change or startup wiring is authorized by this closure.** The prohibition on wiring `AST-016` into `SESSION_START` while `V-3` is unresolved **stands unweakened**. The `A-4.3` disclosure duty **remains in force**; `C-1`–`C-4` **remain binding on the convention**.

---

## Traceability

`DEC-1`/`DEC-2` (PO/ARB 2026-08-15, §1 verbatim) · Amendment `A-4` (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · ADP `86b2e536` · governance review `e77fa724` · `S4-architecture-topology` seq 1–4 · `G-KOS-TOPO-ARCH` · new work item `KOS-GOV-ATTRIBUTION-001` (OPEN, 0/0) · `A-1.4`/`D-5` · accepted principle 5 · `INV-ORCH-1` · `R-34` · `R8` · `Inv C`/`R1` · `D-6` · `E-1` · `V-3` · `O-CLOSURE-VOCAB` · `ES-005.4`
