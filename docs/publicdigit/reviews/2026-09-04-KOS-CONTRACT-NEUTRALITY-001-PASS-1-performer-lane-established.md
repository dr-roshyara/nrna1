# `KOS-CONTRACT-NEUTRALITY-001` — Pass-1 performer lane **ESTABLISHED** (Option 1) · Pass 1 **still not started**

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; holds no lane here)*

> ### ⛔ **V-3 IS NOT CLOSED, NOT ACCEPTED, NOT AMENDED, NOT ADJUDICATED.** Nothing here touches the substantive question. **Pass 1 is authorized and attributable but NOT started** — the human `START` has not been recorded.

---

## 1 · The authorization (verbatim)

> *"Create a new lane for the fresh performer, amend the existing Pass-1 grant to name that lane, and perform the necessary governed handoff from the currently open V-3 lane. **Do not close, accept, amend, or adjudicate the V-3 determination. Preserve the V-3 lane's unresolved state and provenance.**"*

Recorded as **separate acts**, on the PO/ARB's explicit instruction not to combine them into one vague takeover — *"that separation is important because it lets you prove later exactly what was decided, by whom, and what was not decided."*

## 2 · The acts, separately recorded

| # | Act | Result |
|---|---|---|
| **1** | `REGISTER` `seq 41` — new lane **`S5-architecture-pass1-evidence-reconciliation`**, role `architecture`, predecessor `S4-architecture-v3-determination`, `recordedBy: governance` | accepted |
| **2** | Grant **`G-KOS-CONTRACT-PASS1-RECONCILE-AMD1`** — **performer clause only** | accepted |
| **3** | `HANDOFF` `seq 42` — `from: S4-architecture-v3-determination` → `to: S5-…`, token `T-KOS-CONTRACT-PASS1-RECONCILE` | accepted |
| **4** | *(the performer's act)* re-run the authority resolver | **gate verified below** |
| **5** | *(a human act)* `START` | **NOT DONE — deliberately** |

**Resulting state:** `workItemState: OPEN` · `mutationOwner: null` · `S4` **`HANDED_OFF`** · `S5` **`CREATED`** · **42 transitions** · four grants in force.

## 3 · The gate, verified rather than asserted

The fresh performer now resolves against the record:

```
verdict RESOLVED · attribution MATCH · role architecture · authorized_to_act FALSE
```

**This is precisely the intended outcome and worth stating plainly: the performer is now legitimately *attributable* but is *not permitted to work*.** Acts 1–3 solved identity; they conferred no permission. Permission requires the human `START` (`G-3`), which is act 5 and has not happened.

**`mutationOwner` is `null`, not `S5`.** Ownership is *released* by a handoff and passes to the successor **only at `START`** — so the work item is presently held by nobody. That is the honest intermediate state, not an anomaly.

## 4 · What was preserved — the PO/ARB's binding condition

| | |
|---|---|
| **V-3 substantive question** | **OPEN · UNRESOLVED.** Untouched by every act above. |
| **`S4` lane attribution and provenance** | **Preserved.** Its `executionContext` is immutable and unaltered; the append-only record retains its full history. Its state is `HANDED_OFF` — a **mechanical consequence of `Inv C`** (only the current mutation owner may hand off), **not a resolution, closure, acceptance or adjudication.** |
| **`G-KOS-CONTRACT-V3-ARCH` · `-AMD1`** | **Unchanged, both still `AUTHORIZED`**, still bounded to V-3a/V-3b, `PROPOSAL ONLY`. Verified after the writes. |
| **`G-KOS-CONTRACT-PASS1-RECONCILE`** | **Unchanged and still `AUTHORIZED`.** `AMD1` changes **only** the named performer — not the scope, deliverable, evidence set, V-3 ranking rule, containment rule, not-authorized list, vocabularies or stop conditions, and it grants no `START`. |
| **Neither V-3 determination** | **Still unaccepted.** Nothing here alters that. |

**Handing off ownership of the work item is not resolution of V-3.** That distinction is written onto the handoff transition itself, so it cannot be misread later from the record alone.

## 5 · One choice Governance had to make, disclosed

`REGISTER` requires a **role**, and **role is immutable once recorded** (`R8`). Governance chose **`architecture`** because it continues the assignment family the Pass-1 grant originally named (the `S4` architecture lane) and is the closest declared role.

**Role is not authority.** The grant is the authority, and this grant's deliverable is an **evidence determination, explicitly not an architecture decision** — architecture redesign, contract change, LCOM4 modification, Python implementation, target-language choice and `KOS-ARCH-BASELINE-001` modification all remain out of scope. **If the PO/ARB prefers a different role, it must be raised before `START`**, because the fix afterwards is a new assignment, not an edit.

## 6 · What must happen next, in order

1. **The performer re-runs the authority resolver against itself** — as the PO/ARB required — and confirms `RESOLVED / MATCH / authorized_to_act: false`, i.e. that it is attributable but must not begin.
2. **A human records `START`.** Only then does ownership pass to `S5` and Pass 1 become permitted.
3. **Pass 1 runs** to the direction already prepared and unchanged: `…-PASS-1-evidence-reconciliation-direction.md`.

**Until step 2, no Pass-1 work is permitted**, and the direction's containment rule stands: Pass 1 must not silently become the implementation or contract-correction activity.

## 7 · Non-actions

No `START` · no Pass-1 work · **no V-3 closure, acceptance, amendment or adjudication** · no change to the V-3 grants or to the base Pass-1 grant · no edit of any `executionContext` (immutable by construction) · no independence assessment of the performer (none was commissioned) · nothing in the do-not-modify list touched · `KOS-LCOM4-CONTRACT-001` not reopened.

**Traceability:** PO/ARB act 2026-09-04 (Option 1) · re-attribution determination `…-PASS-1-reattribution-determination.md` · fresh performer's stop record `…-PASS-1-fresh-performer-registration-attempt-STOP.md` · Pass-1 authorization `2026-08-24-…-PASS-1-AUTHORIZATION.md` · direction `…-PASS-1-evidence-reconciliation-direction.md` · `AST-015` `seq 41`–`42` + `G-KOS-CONTRACT-PASS1-RECONCILE-AMD1` · `Inv C`/`Inv F` · `G-3` · `R1`/`R8` · `N-16` · `EKS-07`
