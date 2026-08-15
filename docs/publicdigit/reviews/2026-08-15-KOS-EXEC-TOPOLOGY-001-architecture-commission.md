# KOS-EXEC-TOPOLOGY-001 — Architecture commission registered

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance) · **Work item:** `KOS-EXEC-TOPOLOGY-001` (`architecture-decision`, four canonical roles)
**Status:** lane established · **NOT STARTED — the START is the PO's act.**

---

## 1 · The commissioning act, registered verbatim

> **"I commission Architecture to determine whether the two-terminal model should be adopted and what, if anything, must change to support it."**
> — PO/ARB, 2026-08-15

Registrable under A-3: the act is present in the delivered text in the PO's own voice, and it authorizes a determination rather than describing one.

## 2 · What was registered

| Act | Value |
|---|---|
| **Work item created** | `KOS-EXEC-TOPOLOGY-001` — workflow `architecture-decision`, roles `governance · architecture · implementation · verification` |
| **Assignment registered** | seq **1** — `S4-architecture-topology`, role `architecture`, predecessor `null`, context `shared-worktree` |
| **Grant registered at assignment time** | **`G-KOS-TOPO-ARCH`** — `AUTHORIZED`. **E-14 lesson applied**: the role's authorization is pre-established at registration so the verification-gate stall cannot recur. **It authorizes nothing until a human START.** |
| **Handoff recorded** | seq **2** — bootstrap (`from: null`, ownership was unheld); token = the execution-topology intake (`ef19e21c`) + this registration |
| **START** | ⛔ **NOT performed — the PO's act** |

**Resulting state:** `S4-architecture-topology` = `CREATED` · `mutationOwner` = `NULL` · `G-KOS-TOPO-ARCH` = `AUTHORIZED` · work item `OPEN`.

## 3 · Grant scope — `G-KOS-TOPO-ARCH`

**Deliverable: a PROPOSED boundary/determination for human approval. Design only — no code, no tests, no registry change.**

**IN SCOPE**

- **(a)** Recommend **ADOPT / ADOPT-WITH-CHANGES / DO-NOT-ADOPT** for the two-terminal model.
- **(b)** Determine which of the three outcomes applies: **no platform change** · **documentation/governance only** · **a new platform capability**.
- **(c)** Assess and recommend between **alternative A** (separate work item per concurrent lane) and **alternative B** (extend the workflow model), **stating the cost asymmetry honestly — B reopens the QUALIFIED `AST-015`.**
- **(d)** **Dispose of the unguarded-ownership-transfer observation**: `START` has no precondition on the current owner and silently reassigns mutation ownership while the prior lane remains `ACTIVE`. **Intended semantics, or oversight?**
- **(e)** Assess the relationship to **`O-1`/`O-4`/`D-6`** (read-only participation not expressible) — which Governance *measured* to be the actual obstacle, **rather than Inv C**.
- **(f)** Determine whether the **stronger process-independence principle** should be adopted, noting honestly that it was **not** met at the corrective increment (`E-3`) and the work was nonetheless accepted — so adopting it **raises** the standard.

**EXCLUDED:** implementation of any kind · modification of `workflow-state.php` (`AST-015`) or `session-resolve.php` (`AST-016`) · `SESSION_START`/startup wiring · hooks, locks, leases · concurrent-active-session semantics · Increment-2 · any remedy for `V-3`, `E-1`, `O-CLOSURE-VOCAB` or the bootstrap gap · reopening `KOS-SESSION-DISCOVERY-001` · declaring the two-terminal model mandatory · self-certification.

> **🔒 BINDING CONSTRAINT.** Where a determination would require changing the **qualified** mechanism, **record it as a dependency requiring separate authorization — do not design the mechanism change.** `AST-015` is operationally qualified, and `KOS-AI-ORCH-001`'s ruling reserves mechanism *evolution* to separate future analysis and authorization.

## 4 · Evidence Architecture inherits

The intake (`2026-08-15-execution-topology-two-terminal-governed-workflow-intake.md`, `ef19e21c`) carries the measured findings. **Two correct the framing that prompted this commission, and Architecture must not re-inherit the incorrect version:**

1. **Single-mutation-owner is an APPROVED invariant** (Inv C / R1), **not** a discovered limitation. *"Reads concurrent"* is **already approved** — so a concurrent read-only assurance lane is **not** in tension with Inv C.
2. **Two sessions CAN be simultaneously `ACTIVE`** in one work item (measured). *"Only one active lane"* is **false**; only the **owner** is single-valued.
3. **`START` silently transfers ownership** (measured) — item (d).
4. **The actual obstacle is `D-6`**, not Inv C: a read-only lane acquires mutation ownership merely by starting, and the record cannot express read-only participation.
5. **`E-3`** — implementation and verification shared `executionContext: shared-worktree` at the corrective increment. This is the motivating evidence.

## 5 · ⚠️ V-3 observed in the wild — on the adopted capability's first live use

Resolving this very lane with `AST-016`, immediately after registering it:

```
verdict: RESOLVED   operable: false
  KOS-EXEC-TOPOLOGY-001 :: S4-architecture-topology [architecture] = CREATED
  missing for activation: a recorded predecessor HANDOFF carrying its token   ← FALSE: seq 2 recorded it
  missing for activation: a recorded human START act                          ← TRUE
```

> **The handoff at seq 2 exists. The report says it is missing.** This is exactly `V-3`, reproducing on the **first live use** of the newly qualified and adopted asset — no longer a laboratory result.
>
> **It also confirms the scope narrowing:** the human-START line is **correct** (this lane genuinely has not been started), and only the **handoff** line is false. Precisely one of the two items is wrong, as recorded.

**Nothing was repaired.** This is **live evidence for `KOS-ACTIVATION-REPORTING-001`** (OPEN, uncommissioned), recorded here and cross-referenced there. It **strengthens, and does not alter,** the existing classification and the standing prohibition on `SESSION_START` wiring — had `AST-016` been wired into startup, every session on this lane would now be told, automatically, that a handoff it possesses is missing.

## 6 · Outstanding, unaffected by this commission

- **`V-4`** — still **no referent in the governed record**. The PO has neither supplied nor retracted it. **Not cross-referenced anywhere**, and not a dependency of this commission.
- Separate and uncombined: **`V-3`** (`KOS-ACTIVATION-REPORTING-001`) · **`E-1`** · **`O-CLOSURE-VOCAB`** · the successor-registration/bootstrap gap · branch/publishing questions · the Election queue.
- **`KOS-SESSION-DISCOVERY-001`** — governance/documentary closure **CLOSED**; authoritative workflow-machine state **`OPEN`** (no closure transition exists). **Not reopened.**

## 7 · Next actor

> **The PO/ARB — the human START act for `S4-architecture-topology`.**
> Then **Session 4 (Architecture)** delivers the determination as a **PROPOSED** boundary for human approval.

**Governance has issued no implementation grant, no verification grant, and has started nothing.** Sessions 1 and 3 remain stopped.

---

## Traceability

PO/ARB commission 2026-08-15 (§1 verbatim) · `KOS-EXEC-TOPOLOGY-001` seq 1 (REGISTER) · `G-KOS-TOPO-ARCH` (AUTHORIZED, E-14 pattern) · seq 2 (bootstrap HANDOFF) · intake `ef19e21c` · Inv C / R1 (`2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md`) · `O-1`/`O-4`/`D-6` · `E-3` (governance review `3884d81d`) · live `V-3` reproduction §5 · `KOS-ACTIVATION-REPORTING-001` (OPEN, 0 sessions, 0 grants) · closure `95c90a68` · vocabulary ruling `5f83cbe0`
