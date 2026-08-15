# Open Findings Register — KnowledgeOS platform track

**Created:** 2026-08-15 by Session 2 (Governance), on PO/ARB authorization · **Living document — updated in place**
**Purpose:** give findings a durable home. Nothing more.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #2
 (illustrative format, NOT an adopted standard — trial to 2026-08-30)
 Responsibility : governance
 Operator       : Session 2 (Governance)                    [declared]
 Approver       : PO/ARB — register authorization 2026-08-15 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2).
                  Evidence only. NOT proof of authority,
                  correctness, independence or approval.
─────────────────────────────────────────────────────────────
```

> # ⚖️ THIS REGISTER RECORDS. IT DOES NOT COMMISSION.
> **An observation is not automatically a requirement, and a finding is not automatically a work item.** Listing something here creates **no** obligation, priority, grant, assignment or schedule. **Only the PO/ARB decides what gets commissioned.**
>
> **Nothing here reopens `AST-016`.** Its implementation is **complete, qualified, adopted and closed**. Findings discovered *through* its use do not reopen it.

---

## A · Open findings with **NO work item** — the reason this register exists

**These existed only as prose scattered across many artifacts.** That is the failure mode the register closes.

| Finding | What it is | Where it lives today | Work item | Next decision |
|---|---|---|---|---|
| **`O-CLOSURE-VOCAB`** | **The mechanism cannot express closure.** `{"type":"CLOSE"}` → *"no such edge exists in the machine"* (exit 65). `workItemState` is only `OPEN`\|`STOPPED`; `STOP` means *halted* and would corrupt Inv E if misused. ⇒ **qualification, adoption and closure are ALL documentary states**; closed items read `OPEN` in the record | ~16 review artifacts | **none** | **Decide whether to commission** |
| **`E-1/record`** | **The authoritative runtime record is untracked and gitignored** (`.gitignore:25,:32`). The governance *narrative* is versioned; the *record it describes* is not ⇒ current content is attestable, **historical content is not — by anyone** | ~20 review artifacts | **none** | **Decide whether to commission** |
| **`BOOTSTRAP-GAP`** | **Creating the next governed session still needs a manual Governance intervention** — the successor-registration/bootstrap gap, recorded on at least two separate occasions | ~21 review artifacts | **none** | **Decide whether to commission** |
| **`OBS-1`** ⭐ *newly added — not on the PO's list* | **The "Governance is never a session" model has been applied inconsistently at least once:** exactly one session estate-wide carries `role: governance` (`S2-governance-2026-08-14-oq`, `KOS-OQ-001`, `HANDED_OFF`). Direct evidence for the attribution question | 1 artifact — **the most at-risk entry here** | **none** | **Decide whether to commission** *(or strike, if out of scope)* |

## B · Open findings **with** a work item

| Finding | Work item | State | Next decision |
|---|---|---|---|
| **`V-3`** — the resolver reports a missing predecessor `HANDOFF` without verifying it against the record; an **architecture/specification gap**, not an implementation defect. **Three real-world occurrences**, twice inducing repair requests against a *sound* record | **`KOS-ACTIVATION-REPORTING-001`** | **OPEN · 0 sessions · 0 grants — uncommissioned** | Separate architecture decision. **Gates any `SESSION_START` wiring of `AST-016`** |
| **`DEP-3` / `ALT-2`** — whether clearer identification of working responsibilities improves transparency and traceability | **`KOS-ROLE-IDENTITY-001`** | **OPEN · `S4` COMPLETED · bounded trial running 2026-08-15 → 2026-08-30** | **PO/ARB at the 2026-08-30 review:** adopt · amend · abandon · commission implementation |

## C · Open items already governed inside an **adopted amendment** — listed for completeness, not for action

**Not prose-only; these are named in adopted rule text and need no rescue.**

| Item | Home | Constraint |
|---|---|---|
| **`DEP-1`** (actor field) · **`D-2`** (grant↔session linkage) · **`D-6`** (read-only participation) | `A-5.6` / `P-6` — the **mechanism-evolution family** | 🔴 **All reopen qualified `AST-015`.** Separately commissioned; **must not be batched without a new architecture boundary** |
| **`DEP-3.1`–`DEP-3.4`** | `DEP-3` ADP §8 | `3.1`/`3.2` belong to the family above · `3.3` signing needs **enforced key isolation** · `3.4` needs `AST-016` and is **reporting-only** (`A-5.5`) |
| **`E-T1`–`E-T6`, `E-T-BIAS`** | `ALT-2` trial protocol | Evidence definitions for the 30 Aug review — **not findings** |

## D · Closed — recorded so nothing looks quietly dropped

| Finding | Disposition |
|---|---|
| **`V-1`** | Nonexistent `--dir` → `UNASSIGNED`, STOP-shaped and safe. **Accepted observation; never a blocker** |
| **`V-2`** | `KOS_MECHANISM_PATH` undocumented as runtime-selectable. **CURED by `C-2`** — every report now names its interpreter |
| **`E-2/verification`** | Verification not recorded `COMPLETE`. **CORRECTED** — `KOS-SESSION-DISCOVERY-001` seq 18 |
| **`E-3/context`** | Shared `executionContext` at the corrective increment. **Closed as stated:** `R-34` *was* satisfied (authorship check). Residual — the **shared-worktree risk class** — is governed by `INV-ORCH-1`, not by this finding |
| **`OB-1`** | `A5` escalation trigger undefined. **DISCHARGED** by `A-6` (`T-3` adopted) |
| **`OB-2`** | No evaluation-period endpoint. **DISCHARGED** by `A-6.8` — **2026-08-30** |

## E · ⚠️ Finding-ID hygiene — a collision this register surfaced

**`E-1` currently denotes two different findings:**

| Where | `E-1` means |
|---|---|
| Corrective-verification governance review (`3884d81d`) | **the authoritative record is untracked/gitignored** |
| `DEP-3` ADP (`a5b571df`) | **every attribution channel names a role, never a person** |

**Resolved here by qualification, not by rewriting:** this register uses **`E-1/record`** for the first and refers to the second as **`ADP E-1`**. **Source artifacts are NOT edited** — they are append-only history, and their finding text stands as written.

> **Root cause, recorded as a process observation (not a work item):** finding IDs are minted **per artifact** with no shared namespace, so collisions are inevitable as artifacts accumulate. **A shared namespace would prevent recurrence** — noted, **not proposed**, and certainly not commissioned.

## F · What this register deliberately does **not** do

- ❌ **Commission nothing.** No work item created, no grant, no assignment, no session, no schedule, no priority.
- ❌ **No implementation**, no architecture session, no `AST-015`/`AST-016` change, no hooks, no `SESSION_START` wiring, no authorization enforcement.
- ❌ **`AST-016` is not reopened.** Complete · verified · qualified · adopted · closed.
- ❌ **The `ALT-2` trial is not expanded.** Per §10 of its protocol, the correct response to a trial limitation is to **record it and leave the experiment alone** — including the limitation that CONTEXT/session-log-only turns generate no `E-T1` data.
- ❌ **No finding is promoted, ranked, or aged.** Sitting in section A implies **no** claim that something should be done.

## G · Maintenance

**Single home.** When a finding opens, closes, or gains a work item, **this register is updated in place** — new rows appended, dispositions annotated. **Closed entries move to §D; they are not deleted**, so the register shows what was decided as well as what is pending.

**Next scheduled touch: 2026-08-30**, when the `ALT-2` trial reports and `KOS-ROLE-IDENTITY-001`'s row changes. **No other cadence is implied.**

---

## Traceability

PO/ARB register authorization 2026-08-15 · `O-CLOSURE-VOCAB` (closure artifact §6, `95c90a68`) · `E-1/record` (corrective-verification governance review, `3884d81d`) · `BOOTSTRAP-GAP` (recorded 2026-08-14/15) · `OBS-1` (`4ad09fa1` §1) · `V-3` / `KOS-ACTIVATION-REPORTING-001` · `DEP-3`/`ALT-2` / `KOS-ROLE-IDENTITY-001` + trial protocol (`32c125b4`, `c0c08429`) · `A-5.6`/`P-6` family · `A-6`/`A-6.8` · `V-1`/`V-2` (`beb26177`) · `E-2` (seq 18) · `E-3` correction (`a5b571df` §6, upheld `4ad09fa1`) · `AST-016` adoption + closure (`95c90a68`)
