# Delivery Governance — evidence package: PB-006's accepted scope vs the current implementation boundary

**Date:** 2026-08-04
**Author:** Engineering
**Owning authority:** **Delivery Governance** (PB-006 was closed by the ARB; the progress artifact records that acceptance)
**Type:** engineering verification finding — **evidence only, no recommendation**
**Status:** **DISPOSED — ANNOTATE (R-94, ARB Chief, 2026-08-04).** Executed at `IMPLEMENTATION_PROGRESS.md:58`

---

## 1. Why this package exists

It was **not** produced by a sweep. It surfaced while applying an ARB wording refinement — narrowing a conversational claim about the correction loop to the path actually verified. The search for that phrasing returned a row in a **live program artifact**, and the row was inspected before being escalated.

**Engineering did not modify the artifact when reporting this.** The row records a **Delivery-Governance acceptance**, and only the owning authority may dispose of it. **The annotation now present at line 58 was executed AFTER and UNDER `R-94`, not by engineering initiative** — see §7.

---

## 2. The fact

`docs/implementation/IMPLEMENTATION_PROGRESS.md` line 58, verbatim:

```
| End-to-end correction loop (IT-1..IT-8 over the REAL path) | ✅ Proven (PB-006 6B-2) | 100% |
```

## 3. What PB-006 accepted

| | |
|---|---|
| Ticket | **PB-006** — Integration Validation IT-1..IT-8 + `IntegrationEventDispatcher` |
| Accepted scope | **the eight Blueprint §9 scenarios over the REAL path** (`PB-006_Integration_Validation_Implementation_Design.md` §6B) |
| Closed by | **ARB, 2026-07-10** |
| Status | `CLOSED (ARB)` · 100% (6A · 6B-1 · 6B-2 · 6C) |

## 4. Chronology — the whole finding rests on this

| Event | Introduced | Relative to PB-006's closure |
|---|---|---|
| **PB-006 closed by ARB** | **2026-07-10** | — |
| `AdjudicationExpired` (`app/Contexts/Adjudication/Domain/Events/`) | **2026-07-31** (`22d604844`) | **+21 days** |
| `AdjudicationFailureDeclared` (same namespace) | **2026-08-04** (`939e35dcb`) | **+25 days** |

**Both terminal exits postdate the acceptance.** They could not have been in IT-1..IT-8's scope, because they did not exist.

Corroborating check: `grep -rln "AdjudicationExpired\|AdjudicationFailureDeclared" tests/` returns eight files — all Adjudication-local unit/feature tests plus two architecture-registry tests. **No IT-suite file names either event.**

---

## 5. The finding, stated at the strength the evidence supports

> **The row remains accurate for the implementation boundary accepted under PB-006.** IT-1..IT-8 proved what IT-1..IT-8 was defined to prove, and that acceptance stands.
>
> **Subsequent implementation introduced behaviour outside that accepted boundary.** **Whether programme reporting should now distinguish historical acceptance scope from current implementation scope is a Delivery Governance decision.**

*(Wording set by the ARB, 2026-08-04. An earlier version read "historically accurate but no longer reflects the current implementation boundary" — accurate, but it carried an unintended implication that the row **had become incorrect**. It has not. The correction removes the implication and leaves the engineering finding unchanged.)*

**Explicitly NOT claimed:**

- **Not** that the claim is wrong. It is not.
- **Not** that PB-006's acceptance should be reopened, revoked, or re-verified.
- **Not** that a defect exists in the delivered code. None was found; this is an artifact-scope question.
- **Not** that the row must change. That determination belongs to Delivery Governance.

**A historical acceptance does not automatically update when the system grows.** That is the governance distinction this package turns on, and it is why the row is reported rather than corrected.

---

## 6. Why it is worth an authority's attention

An independent artifact already assumes the boundary has moved. `engineering/verification/reports/2026-08-02-wp8-definition-finding.md` line 40 states WP-8's business capability as:

> *"the correction loop proven end to end over the real path, **including the failure-declared branch**"*

Read together, the two artifacts describe different boundaries: one records the loop as 100% proven, the other defines outstanding work to prove a branch of it. **Both are individually accurate for their own scope.** A reader consulting only line 58 for programme status would not learn that WP-8 exists to validate exits the IT suite does not cover.

**The risk is a reading risk, not a correctness defect** — and it lands on a progress artifact used for programme-level status.

---

## 7. Disposition — **DECIDED: O-1 (annotate), per `R-94`, ARB Chief, 2026-08-04**

| | Outcome | What it would mean |
|---|---|---|
| **O-1** ✅ **ADOPTED** | **Annotate the row with its accepted scope** | line 58 keeps its ✅ and gains an explicit "as accepted at PB-006 closure, 2026-07-10" boundary marker |
| **O-2** | **Leave the row unchanged** | the parenthetical `(IT-1..IT-8 over the REAL path)` is judged sufficient scoping already |
| **O-3** | **Refer the boundary question to WP-8** | resolve it when WP-8 validates the failure-declared branch, rather than now |

**Engineering supplied the evidence and selected no outcome** (EP-02 · R-34). **The authority selected O-1**, on the recorded grounds that *the historical acceptance remains correct, superseding would blur chronology, and leaving it unchanged risks future readers assuming today's implementation was the accepted scope.*

---

## 8. Authorization boundary

| | |
|---|---|
| Artifact modified by engineering **on its own initiative** | **none** |
| Artifact modified **under `R-94`'s disposition** | **`IMPLEMENTATION_PROGRESS.md:58`** — annotated, ✅ and 100% retained |
| New governance ruling **proposed by engineering** | **none** — `R-94` was issued by the authority, not requested |
| Implementation proposed | **none** |
| Engineering status | **complete; evidence supplied, disposition executed as directed** |

---

**Traceability:** `docs/implementation/IMPLEMENTATION_PROGRESS.md:58` · `docs/implementation/backlog/EPIC-001_Greenfield_Core.md:18,38` · `docs/implementation/backlog/PB-006_Integration_Validation_Implementation_Design.md` §6B · Blueprint §9 (IT-1..8) · `2026-08-02-wp8-definition-finding.md:40` · `22d604844` · `939e35dcb` · `2026-08-04-wp4c2-discovery.md`
