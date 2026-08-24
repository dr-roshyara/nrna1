# EKS-09 — Activation write atomicity: a multi-append governed sequence has no transaction boundary

**Status:** **BACKLOG · ARCHITECTURE** — registered 2026-08-24 on the PO/ARB act *"RV-F1 → open follow-up … the next actor is therefore Governance, but only for follow-up disposition"*. ⛔ **Not commissioned; starting requires a human authorization act.**
**Class:** architecture problem — transaction boundary / write atomicity (AI Engineering Platform, `CMP-004`).
**Not:** an ADR · an adopted rule · a defect in the adopted `AST-019` · an immediate implementation task.
**Registered by:** Governance (`5928b9f9`). ⛔ **Approves no architecture, commissions nothing, and does not reopen `AST-019`.**
**Source finding:** `RV-F1`, independent re-verification of `REPAIR-001`, 2026-08-24. **Classified NON-BLOCKING by the verifier and carried by the adoption/authorization of the same date.**

> ### ⭐ The insight this ticket holds
> **`AST-019` correctly *derives* ownership and correctly *fails closed*. What it cannot do is guarantee that the world it measured is still the world it writes into.** The gap is not a bug in the capability — it is the **absence of a transaction boundary** around a governed sequence of three separate appends.

---

## 1 · Problem

An activation performs **three separate, non-atomic `AST-015` appends**: `REGISTER` → `HANDOFF` → `START`. Ownership is derived from the fold observed during **analysis**. If ownership moves between that analysis and the write, `REGISTER` lands carrying a now-stale `predecessor`, the `HANDOFF` is correctly refused by `Inv C`, and an **orphan assignment remains**.

```
analyse  → owner = OWNER
                        ← another process moves ownership to THIEF
write    → REGISTER (predecessor=OWNER)      accepted
         → HANDOFF  (from=OWNER)             REFUSED — Inv C
         → orphan lane stranded CREATED, permanently
```

**The consequence is permanent.** `ASD-001` established there is **no un-`REGISTER`**: the log is append-only, role is immutable (`R8`), and no rollback edge exists (`R1`). Resolution requires an explicit Governance workaround.

## 2 · Measured evidence — forced, not theorised

The re-verifier drove it deliberately (`rv-race.php`) by stealing ownership to a third lane immediately before the capability's first append:

| Observed | |
|---|---|
| exit | `65` · `INCOMPLETE_SEQUENCE` |
| written | `["REGISTER"]` |
| refusal | *"only the current mutation owner can hand off (Inv C)"* |
| record | `REGISTER session=RACER predecessor='OWNER'` while `mutationOwner=THIEF`; lane `RACER` stranded `CREATED` |

## 3 · What is explicitly **not** wrong here

- **Not a regression — a strict improvement.** Pre-repair the identical orphan occurred **deterministically on every live-owner activation**. Post-repair it requires a **genuine concurrent ownership mutation**.
- **Fail-closed is now tested, not asserted:** no false activation, no mis-attributed handoff, and honest reporting (`transitionWritten: true`, `whoMustActNext: governance`).
- **Not a defect in the adopted capability.** The non-atomicity is a **pre-existing architectural property** of a three-append sequence with no transaction boundary — outside `F-1`…`F-4`/`O-1` and outside `REPAIR-001`'s authorized scope.

## 4 · Why this is its own item and not folded into an existing one (`ES-005.4` check, performed)

| Existing item | Relationship | Why not merged |
|---|---|---|
| **`EKS-07`** Multi-Process Coordination & Shared Work-State Integrity | **Adjacent, and this is corroborating first-hand evidence for it** — the trigger is precisely a concurrent process | **Different remedy space.** `EKS-07` concerns synchronization, notification and provenance *awareness* between processes. This item concerns **atomicity of one capability's write sequence** — a transaction boundary. Fixing `EKS-07`'s awareness problem would not remove this race, and vice versa. |
| **`F-5`** `KOS_MECHANISM_PATH` redirects the sole writer | Adjacent | Different mechanism: **where** the writer points, versus **whether the write sequence is atomic**. The verifier stated it is *"adjacent to, but distinct from, `F-5`"*. |
| **`ASD-001`** | Supplies the load-bearing fact | It established there is no reverse edge, which is *why* the orphan is permanent. Not a duplicate. |

## 5 · Candidate requirement (a problem statement, not a design)

A governed multi-append sequence should either **complete or leave no trace** — or, if atomicity is genuinely unavailable in an append-only store, the estate should hold a **named, recorded compensating rule** for resolving an orphan assignment. Candidate directions, none chosen: re-checking ownership immediately before the first append (narrowing, not closing, the window) · a lease/claim on the mutation owner · a recorded `CANCEL`-based compensation with a defined governance procedure. **The absence of a reverse edge may itself be the thing to revisit.**

## 6 · Non-actions

Nothing implemented · `AST-019` **not** reopened, **not** modified, and its `ADOPTED`/`AUTHORIZED` states are untouched · no ADR · no adopted rule · no capability created · `EKS-07` not modified · `F-5` not addressed.

**Traceability:** `RV-F1` in `../reviews/2026-08-24-KOS-OPERATING-MODEL-001-AMENDMENT-001-REPAIR-001-INDEPENDENT-RE-VERIFICATION.md` §14 · authorization `../governance/2026-08-24-AST-019-AUTHORIZATION-DECISION.md` §4 · `ASD-001` `../reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-APPOINTMENT-SEQUENCING-DEFECT-001.md` · `EKS-07` · `Inv C` · `R1`/`R8` · `ES-005.4`
