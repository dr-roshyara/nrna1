# P7B-2 — Engineering Allocation Record (and a consequence it produces)

**Date:** 2026-08-01 · **Owner:** Engineering *(allocation within the frozen architecture — not a governance act, not a new architectural decision)*
**Context:** the last engineering allocation question flagged in the Slice 7B Authorization Package, per **R-56**.
**Repository Integrity Gate:** ✅ PASSED.

---

## 1. Allocation — SETTLED

> ### **The "absent anchor ⇒ window open" fallback belongs to the APPLICATION SERVICE, not the Value Object.**

| Candidate | Owns construction | Owns fallback | Verdict |
|---|---|---|---|
| **Value Object** | ✅ models a **valid** window | ❌ **cannot observe its own absence** | ❌ rejected |
| **Application Service** | ❌ orchestrates only | ✅ it is the **caller**, and the only party that sees a missing anchor | ⭐ **OWNS IT** |
| **Domain Service** | ❌ | ❌ | ❌ rejected — indirection with no domain reason |
| **Aggregate** | ❌ | ❌ | ❌ rejected — not an aggregate invariant; would couple the rule to election lifecycle |

**The decisive reasoning:** the construction commission fixed that the VO takes **business values only** and validates itself. **A VO that requires an anchor to exist can never be handed one that is missing** — *"absent anchor"* is not an invalid **state of** the window, it is **the absence of the window**. Making the VO model it would require an "unanchored window", which is not a window.

**And the rule's nature confirms the same home:** *"if there is no anchor, do not delete"* is **use-case policy** — a decision about what to do with insufficient information. **Value Objects model facts; they do not decide what to do when a fact is missing.**

**Effect on the keystones:** **K9** now names the application service. **K5** (*closed before the anchor*) stays with the VO — it tests a **constructed** window, which by definition has one. **K6** is unaffected: the VO stays pure.

## 2. Impact

| Area | Change |
|---|---|
| Architecture | ✅ **none** — allocation inside the frozen model |
| Governance | ✅ **none** — no ruling reinterpreted |
| Business policy | ✅ **none** — the rule is still *absent anchor ⇒ open* |
| Engineering allocation | ✅ **settled** |

---

## 3. ⚠️ **P7B-3 — the consequence this allocation produces**

**Verified against the approved plan, and it is not a quibble.**

| Source | Text |
|---|---|
| Plan §4 | *"**Application service** — the guard decision (**not** the EPW itself)"* |
| Plan §5, slice **7B** | Objective: *"the `EvidencePreservationWindow` **Value Object**"* — **the VO alone** |
| Plan §5, slice **7C** | *"The deletion guard"* |
| Plan §5, **7B tests** | *"the three-term sum · MAD sourced from the port · **anchor-absent ⇒ open**"* |
| Plan §5, **7B acceptance** | *"…**an undecided/absent anchor yields 'window open'**"* |

> **The plan defines exactly ONE application service, and calls it *the guard* — which is 7C's subject. But 7B's test list and acceptance criterion both name the anchor-absent behaviour.**
>
> **So allocating K9 to the application service moves it OUT of 7B's scope** — 7B delivers the Value Object, and the service that would host K9 is not 7B's to deliver.

**The two readings, neither of which is mine to choose:**

| Reading | Consequence |
|---|---|
| **(a)** The application service belongs to **7C** *(what §4 literally says)* | **K9 moves to 7C.** 7B's approved test list and acceptance criterion then name a behaviour 7B cannot deliver, and both need correcting |
| **(b)** **7B** delivers the VO **and** the service that builds it; 7C adds only the guard and the deletion | K9 stays in 7B — **but §4's parenthetical *"not the EPW itself"* has to be read as not excluding it** |

**This is a slice-boundary question in an approved plan — the same class as P7B-1, and therefore the ARB's, not engineering's.** Deciding it here would be engineering redrawing a slice boundary the ARB approved.

**Why it matters before RED rather than during it:** under reading (a), **RED for 7B would be written against a keystone the slice cannot satisfy** — the test would have nowhere to live, and its absence would look like an omission rather than a boundary.

**📝 Recording Note.** P7B-2's *allocation* is settled and stands under either reading — the fallback belongs to the application service in both. **What P7B-3 decides is not the owner but the SLICE.** Recorded as a separate finding so the settled part is not held hostage to the open part.

---

## 4. Readiness

| Prerequisite | Owner | Status |
|---|---|---|
| **P7B-2** — the fallback's owner | engineering | ✅ **SETTLED — application service** |
| **P7B-3** — which slice hosts K9 | **ARB** | ⬜ **NEW — before RED** |
| **P7B-1** — the plan's stale mechanism | **ARB** | ⬜ open (queue 12) |
| **Execution authorization** | **ARB** | ⬜ open (queue 13) |
| EPW anchor value | Q-2 | ⬜ open, **non-blocking** |

**Engineering has no remaining allocation question.** **Three governance items now stand between here and RED** — P7B-1, **P7B-3**, and the execution authorization.

**No implementation has begun. No plan text was edited. No architecture or governance changed.**

---

**Traceability:** **R-56** (which flagged P7B-2 and made it engineering's) · construction commission (business values only; the VO validates itself) · ownership commission (Election owns EPW) · WP-7 plan §4 and §5 (the texts P7B-3 rests on) · **R-44** (the mechanism P7B-1 must restore). **No architecture redesigned · no ruling reinterpreted · no slice boundary redrawn · P7B-1 and P7B-3 not resolved.**
