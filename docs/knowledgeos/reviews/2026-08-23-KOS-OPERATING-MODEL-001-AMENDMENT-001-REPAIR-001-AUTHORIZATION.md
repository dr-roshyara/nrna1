# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — **FAIL ACCEPTED · CONTINUATION · `REPAIR-001` AUTHORIZED**

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject:** `AST-019` · **Grant:** `G-REPAIR-001` (`AUTHORIZED`)
**Document type:** record of four PO/ARB decisions and the two governed acts that carry them
**Date:** 2026-08-23 · **Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; `P-3` responsibility, not a workflow role; holds no lane)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **Authorization is not adoption and is not a verdict.** `AST-019` remains **IMPLEMENTED · VERIFICATION COMPLETED WITH RESULT FAIL · NOT ADOPTED · NOT AUTHORIZED**. No code was changed by this record.

---

## 1 · The human act (G-3), verbatim

> *"Accept the FAIL verdict. Continue the work item for REPAIR-001. Authorize REPAIR-001 for F-1, F-2, F-3, F-4 and O-1. Keep F-5 outside this repair pending a separate Architecture decision. Preserve ASD-001 as a recorded sequencing defect; do not reopen or rewrite the verification history."*

PO/ARB, in-session 2026-08-23. Carried as the `humanAct` on `seq 5` and as the `humanActRef` of grant `G-REPAIR-001`.

## 2 · Decision 1 — the FAIL verdict is **ACCEPTED**

The independent verification's verdict stands as the authoritative outcome. `F-1` is a **reachable correctness defect on the production write path**, confirmed by Governance at source independently of the verifier's testimony (`analyze()` `:377–385` returns no `fold` key; `writeActivation()` `:401–402` therefore forces `$owner` to `null`, feeding both `REGISTER.predecessor` and `HANDOFF.from`).

**Accepting the FAIL closes the verification, not the finding.** The report is **final**: not reopened, not re-run, not rewritten, and `84c0f6f6` is asked for no further work.

## 3 · Decision 2 — `CONTINUATION` recorded (`seq 5`)

`AST-015 append` · `type: CONTINUATION` · `session: 84c0f6f6-795e-4c89-a382-733f2c7b7caf` · `recordedBy: human` · `humanAct` = §1 verbatim. **Accepted, `exit 0`.** Fold: `workItemState: OPEN` · `mutationOwner: 84c0f6f6` · lane `verification` `ACTIVE`.

**Why this names the verification session, stated precisely so it is not misread as reopening the verification.** `CONTINUATION` requires a **registered** session, and `84c0f6f6` is the only one on this item. Reactivating the mutation owner is the **only governed way** to restore a predecessor from which the repair lane can receive its `HANDOFF` — identical to the parent item's **`seq 10` precedent**, where the STOPped verification lane was reactivated solely to hand off to its successor.

**What it reopens:** the **work item**. **What it does not:** the verification, the verdict, or the report. `84c0f6f6` performs no further work; its lane is a handoff predecessor and nothing more.

**⚠️ Observation `O-4` (reporting imprecision, no defect claimed).** `AST-018 next-actor` now reads **`LANE ACTIVE` — *"the verification actor currently holds this work and simply continues. No new actor is needed."*** That advice is **misleading in this state**: the lane is `ACTIVE` as a mechanical consequence of the `CONTINUATION`, not because the verifier has work. The fold is correct; the *advisory* interpretation of it is not. **Verified as harmless to the plan:** `appointReviewer()` gates on `STOPPED`, human act, candidate count, declared role, candidate-already-holds-a-lane, and eligibility — **there is no active-lane refusal** (unlike `AST-019`'s `GO-13`). So the appointment in §6 is unaffected. Recorded because a human reading `next-actor` here would be told no actor is needed, which is false. **RECORDED · NOT PROMOTED** (`ES-006.1`, single occurrence; methodology FROZEN).

## 4 · Decision 3 — `REPAIR-001` **AUTHORIZED** (grant `G-REPAIR-001`)

Registered through `AST-015 grant --writer-role=governance` (`Inv H`/`G-2`: the Authority State has exactly one writer, and a grant registers a recorded human act **by reference** — the record never manufactures authority). **Accepted, `exit 0`.** `grants: 1 · G-REPAIR-001 · AUTHORIZED · registeredBy: governance`.

**In scope — four findings and one test gap, one file plus its contract test:**

| Item | |
|---|---|
| **`F-1`** *(blocking)* | `analyze()` omits `fold`; `writeActivation()` forces `mutationOwner` to `null` and mis-derives `REGISTER.predecessor` / `HANDOFF.from` |
| **`F-3`** | failure path returns the `refusal()` shape, lacking the `ok` the caller tests at `:620` |
| **`F-4`** | `transitionWritten` hard-coded `true` when nothing was written |
| **`F-2`** | PHP warnings on STDOUT break the `--json` contract under `display_errors=On`. **Closes only when BOTH `F-1` and `F-3` are fixed** — the `:401` warning is `F-1`'s, the `:620` warning is `F-3`'s. **`F-2` is not a corollary of `F-1` alone**, and the acceptance criterion is an asserted clean STDOUT **and** STDERR on the success path **and** the partial-write path. |
| **`O-1`** | **RED FIRST** — a contract test reaching the write path with a **live non-null `mutationOwner`**, asserting `REGISTER.predecessor === owner` **and** `HANDOFF.from === owner`, and **failing on today's code** before any fix lands |

**Touchable:** `.claude/scripts/activate-commissioned-fresh-session.php` and its contract test **only**.

## 5 · Decision 4 — exclusions, held rather than forgotten

- **`F-5` is HELD OUTSIDE this repair**, pending a **separate Architecture decision**: whether `KOS_MECHANISM_PATH` may redirect the "sole writer", and why `AST-017`/`AST-018` use hard constants while `AST-019` does not. **It must not be fixed, mitigated, or documented away inside `REPAIR-001`** — a correctness slice silently settling an architecture question is precisely the category slippage this estate forbids. **OPEN.**
- **`ASD-001` is PRESERVED as a recorded sequencing defect.** Not reopened, not remedied, not erased. The verifier's independent assessment stands on the record in both directions: it does **not** invalidate the lane or the verdict, and using `AST-019` would have produced an **identical** record (owner `null`, first lane) so the bypass concealed nothing about `F-1` — while `O-3` finds `ASD-001` and `F-1` to be **the same defect class from opposite directions**. Both halves stand.
- **The verification history is neither reopened nor rewritten.** Append-only; `seq 1–4` are untouched.
- **The adopted parent layers `L1`+`L2`+`L3`** remain `ADOPTED` · `AUTHORIZED` · `STOPPED` · **not reopened**; their **byte-integrity is an acceptance criterion** of `REPAIR-001`, not an assumption.
- Also excluded: any change to `AST-015`/`016`/`017`/`018` or `operating-model.php` · adoption or authorization of `AST-019` (`§38`, PO/ARB, not automatic) · any new capability, engine, or identity mechanism (`ES-005.4`) · `Q-1` · `Q-2` · `REVIEW_INDEPENDENCE_POLICY §22`.

## 6 · Remaining gates — authorization is not permission to start typing

| Gate | Status |
|---|---|
| Human `CONTINUATION` | ✅ recorded (`seq 5`) |
| PO/ARB authorization of the slice | ✅ recorded (`G-REPAIR-001`) |
| **Fresh implementation candidate declares its own identity** | ⬜ **the next act** — declaration-only prompt: `…-AMENDMENT-001-REPAIR-001-IMPLEMENTER-CANDIDATE-DECLARATION-prompt.md` |
| **Appointment via `AST-018 appoint`** | ⬜ `--role=implementation --candidate=<declared id> --human-act='<verbatim>'` — **NOT hand-composed appends; that was `ASD-001`** |
| **Human `START`** (`G-3`) | ⬜ never automated, never inferred |
| **Approved `EP-01` plan** | ⬜ plan → **explicit human approval of the plan** → implement only the approved plan |

**Then:** RED (`O-1` fails on today's code) → GREEN (`F-1`, `F-3`, `F-4`) → `F-2` asserted clean on both paths → full `GO` suite + WorkflowEngine regression → byte-integrity of `L1/L2/L3` and `AST-015/016/017/018` re-proven → `STOP` reporting **IMPLEMENTED, never VERIFIED** (`R-34`/`EP-02`).

### Independence requirements differ by role — kept separate deliberately

| Role | Barred | Reasoning |
|---|---|---|
| **`REPAIR-001` implementer** | `84c0f6f6` — the verifier must not repair what it verified (`R-34`/`EP-02`) · `5928b9f9` — the governance process that scoped the slice must not execute it | The **producer `1899d8bf` is NOT barred from implementing**: repairing one's own code is ordinary engineering, and no rule bars it. A genuinely fresh session is *preferred*, not required. |
| **Re-verification afterwards** | `84c0f6f6` (first verifier) · `1899d8bf` (producer) · `5928b9f9` (this process) · **and whoever implements `REPAIR-001`** | Independence from the work under check is what makes the check worth anything. |

Stating these separately matters: a bar that belongs to *verification* is not a bar on *implementation*, and collapsing the two would shrink the eligible pool for no governed reason.

## 7 · Next actor

```yaml
session_completion:
  status: FAIL ACCEPTED · item OPEN (CONTINUATION seq 5) · REPAIR-001 AUTHORIZED
          (G-REPAIR-001) · no lane appointed · no code changed
  completed_work: four PO/ARB decisions recorded · CONTINUATION seq 5 · grant
                  G-REPAIR-001 AUTHORIZED · F-5 held for Architecture · ASD-001
                  preserved · verification history untouched · O-4 observation ·
                  declaration-only implementer prompt prepared
  evidence: fold (OPEN, mutationOwner 84c0f6f6, grants 1 AUTHORIZED) · seq 5
            accepted exit 0 · grant accepted exit 0 · appoint preflight read at
            source (no active-lane refusal)
  open_items: F-5 (Architecture decision) · ASD-001 remedy · Q-1 · Q-2 · §22

next_actor:
  recommended_role: human
  reason: An actor identity cannot be invented (INV-ATTR-1/2). AST-018 refuses to
          appoint without a candidate because "a fresh actor has no identity until
          its own process reports one". The person must start a fresh session with
          the declaration-only prompt; Governance then appoints the declared id.
  blocking_condition: a fresh implementation session declaring its own runtime
                      identity, then AST-018 appoint, then human START, then an
                      approved EP-01 plan.

authorization:
  current_session_can_continue: false   # capability, NOT authorization (F1)
  authorized_to_act: false              # this process holds no lane
  requires_human_decision: true
```

**Traceability:** PO/ARB act 2026-08-23 (§1 verbatim) · verification `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL, `seq 4`) · repair scoping `…-AMENDMENT-001-FAIL-DISPOSITION-AND-REPAIR-PATH.md` · `ASD-001` `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` · `AST-015` `seq 5` + grant `G-REPAIR-001` · `next-actor-orchestration.php:316-378` (appoint preflight) · source `:377-385`, `:401-402`, `:620`, `:200`, `:91` · `Inv E`/`Inv H` · `G-2`/`G-3` · `§38` · `EP-01`/`EP-02` · `R-34` · `ES-005.4` · `ES-006.1`
