# PO/ARB Authorization **REQUEST** — one bounded Domain slice (proposed ID: `EM-DOM-001`)

**Prepared by:** Session 2 — Governance · **Date:** 2026-08-18
**Status:** 🟡 **REQUEST — NOT AUTHORIZED. NOT GRANTED. NOT STARTED.**

> ⚠️ **This document is a request prepared for the PO/ARB. It confers nothing.** Governance prepares; it does not grant, and it does not clear its own gate. **The ID `EM-DOM-001` is PROPOSED — the PO/ARB assigns or replaces it.**

**Nothing was modified to produce this request.** `app/`, `tests/`, repositories, protocol access and the frozen domain core are unchanged; the core remains byte-identical to `1f4b4c5f`.

---

## 1 · Governing decisions

| | Decision | Status |
|---|---|---|
| **ADR-1** | `ADR_20260817_2145_Aggregate_Absence_Semantics.md` — governed composite absence semantics; **binding constraint ⑥** | ✅ **DECIDED** 2026-08-18 |
| **ADR-2** | `ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` — causal-origin model, provenance ownership | ✅ **DECIDED** 2026-08-18 (a **causal-model** ruling; **no** Option A/B/C/D was selected) |

**Architectural evidence:** the completed Rule-8 gates — `7514f145` (ADR-1) and `74fcf5e5` + `c4828cdd` (ADR-2, incl. the A/B/C matrix). **The gate is accepted as the evidence; this request adds no new analysis.**

## 2 · Domain dependencies requiring resolution — DEP-1 … DEP-6 (all class **B**)

| # | Dependency |
|---|---|
| **DEP-1** | The domain meaning of an **absent `ElectionCommittee`** (required-existence) |
| **DEP-2** | Discrimination of the **two `AcceptanceDecision` meanings** — *not-yet-reached phase* vs *required-but-absent* |
| **DEP-3** | A **policy contract** that accepts or returns an absence concept (P-1…P-7: none does) |
| **DEP-4** | The **recovery-origin** invariant — why a given `RecoveryProcess` exists |
| **DEP-5b** | An **authoritative producer / persistence path for `HaltedAtGate`** (see §3–§4) |
| **DEP-6** | The **restoration-permission** invariant — why Restoration is permitted, **including the `w8` path** (see §5) |

## 3 · DEP-5a — an EXISTING authorized capability, to be CONSUMED, never recreated

**P-7 `ResumptionTarget::resolve(HaltedAtGate $halt): GateDesignation`** already exists in the frozen core and is authorized (`EM-GOV-059(c)`, `060`; `EM-ARCH-001 §2e`).

> ⛔ **The slice must NOT introduce a second mechanism for resolving the resumption target.** No parallel resolver · no duplicate policy · no handler-side equivalent. **P-7 is the answer to *"which gate does restoration return to?"* and the slice consumes it.** *(ADR-2 (g): reference is not ownership; and `ES-005.4` — consume or extend, never create a second.)*

## 4 · DEP-5b — the MISSING piece is the producer, not the concept

**`HaltedAtGate` has no producer in `app/`** (constructed only in `ConditionSemanticsTest` and the RED guard) · **no repository and no port carries one** · **`ResumptionTarget::resolve()` is called nowhere in `app/`.**

**The chain `halt fact → P-7 → GateDesignation` exists, is authorized, and is UNREACHABLE.** The slice must establish the **authoritative** production and persistence of the halt fact so that P-7 becomes reachable **from recorded domain truth**.

⛔ **Not by constructing a `HaltedAtGate` in the Application layer** (that fabricates the halt fact — DEP-9). ⛔ **Not by reading the protocol** (DEP-8).

## 5 · DEP-6 — the missing representation of WHY restoration is permitted, including `w8`

**P-7 answers *where*. Nothing answers *why*.** These are permanently distinct questions and the slice must keep them distinct.

The approved causal model (ADR-2 (a)) has **two paths**, and Path B is not hypothetical:

```
Path A                          Path B  ( = w8 )
  Halt                            Unachievable condition
    ↓                                 ↓
  RecoveryProcess                 condition resolved
    ↓                                 ↓
  Restoration                     Restoration        ← NO RecoveryProcess exists
```

**Therefore `Restoration → RecoveryProcess → originatingGate` cannot be the universal causal model.** Path B must have an **explicit domain meaning** — ⛔ never a fabricated `HaltedAtGate`, a fabricated `RecoveryProcess`, a sentinel `GateDesignation`, or an *"unknown"* value (ADR-2 (b), (h)).

**Registered constraint:** `ElectionRestored.$returnsToGate` is **non-nullable**. Per ADR-2 (h) that incompatibility **is** the domain-model gap, and its representation is decided **together with** this slice's implementation — not deferred.

## 6 · DEP-7 … DEP-12 — prohibited paths, and they REMAIN prohibited

| # | Prohibited path | Note |
|---|---|---|
| **DEP-7** | UC-3 substituting **`$decision->gate()`** for P-7's answer | **quarantined, NOT repaired** — ADR-2 (f) requires the committed UC-3 behaviour to stay **unchanged** until this slice is verified |
| **DEP-8** | Reading the **protocol** to recover the halt gate | *"the protocol contains it"* is not authorization |
| **DEP-9** | Constructing **`HaltedAtGate`** in the Application layer | a reachable constructor is not an ownership grant |
| **DEP-10** ⚠️ | — **NOT prohibited. Class A.** | see §7 |
| **DEP-11** | Inferring provenance from **timestamps or ordering** | |
| **DEP-12** | Taking causal meaning from a **command supplied by the appointment body** | |

⛔ **The slice may not discharge any B dependency by enabling a C path.**

## 7 · DEP-10 must survive the slice untouched

`if ($restoration === null) { return; // nothing to pause }` is **class A** — grounded in **`EM-GOV-062`** (the restoration clock accrues only while the condition holds). The code **consumes an adopted governance meaning** rather than inventing one.

> ## ⛔ The slice must NOT make absence handling uniform. **Normalization means semantic conformity, not syntactic uniformity.**

Making `RecoveryProcess` absence behave like Committee or AcceptanceDecision absence **merely for symmetry would violate ADR-1**, whose §6 assigns each governing condition its own meaning.

## 8 · The slice is scoped to the INVARIANT, not to the handler

**Governance requests explicitly that the authorization NOT be phrased as *"fix `FillCommitteeSeatHandler`."***

The convergence on `FillCommitteeSeatHandler.php:106/140/156` is **evidence that the gaps are one gap** — it is **not the definition of the work**. Scoping the slice to a handler would let the handler's current shape determine the new domain model, which is the failure this whole gate exists to prevent.

**Requested phrasing:** *resolve the approved ADR-1 and ADR-2 semantic dependencies DEP-1…DEP-6 in the domain model.*

## 9 · Required sequence inside the slice — decision map BEFORE code

**Obligation 1 — a domain decision map, produced and reviewed BEFORE any design or code.** It must answer, for each of DEP-1…DEP-6:

1. What is the invariant? 2. Which bounded context **owns** it? 3. What domain concept expresses it? 4. What are the **legitimate** states? 5. What is **impossible**? 6. What must be **captured at the transition**? 7. What representation makes those states **explicit**? 8. What does UC-3 **consume** afterwards?

The map must render at least these two discriminations explicitly:

```
        ABSENT REFERENCE                            RESTORATION
               │                                         │
     ┌─────────┴─────────┐                    ┌──────────┴──────────┐
 required by         legitimate           Recovery existed      No RecoveryProcess
 invariant           lifecycle                  │                    │
     │                   │                 provenance path    Unachievable resolved
 domain violation    normal state               │                    │
     │                                    originating cause   explicit domain meaning
 domain-owned
 decision
```

**Obligation 2 — domain RED tests before domain implementation**, as separate commits, in the same two-phase discipline as `EM-IMPL-002` (RED committed and verified failing-by-absence *before* GREEN), so the ordering is git-provable.

**Obligation 3 — `Domain RED → domain GREEN → independent domain verification`.** Engineering supplies evidence and never accepts its own work (`EP-02`, `R-34`).

**Obligation 4 (requested) — a FRESH Domain lane, not the current implementation lane**, so the Application handler's present shape cannot unconsciously shape the domain model.

## 10 · What remains stopped

⛔ **No Application normalization.** ⛔ **No change to UC-1, UC-2, UC-3 or UC-4.** ⛔ **`AbsentAggregateReferenceRedTest` stays RED and keeps reporting its two UC-3 sites — correctly.** ⛔ **GREEN-5 remains STOPPED** until the authorized domain slice is **verified**.

---

## Statements this request makes explicitly

> ## **An ADR signature is not layer-wide implementation authorization.**

> ## **The authorization is for ONE bounded domain slice to resolve DEP-1 through DEP-6; it is NOT authorization to invent a technical mechanism.**

---

## Authorization block

**⬜ LEFT BLANK — the Product Owner / ARB decides.** Requested form:

> *"I authorize one bounded Domain slice (`EM-DOM-001`) to resolve the approved ADR-1 and ADR-2 semantic dependencies DEP-1 through DEP-6 in the domain model, with its own domain decision map first, then domain RED tests, then domain implementation, then independent domain verification. No Application-layer normalization and no GREEN-5 work is authorized until that slice is verified. START is a separate act."*

**Traceability:** ADR-1 §6 · ADR-2 §6 (a)–(h) · `7514f145` · `74fcf5e5` · `c4828cdd` · P-7 `ResumptionTarget` · `HaltedAtGate` · `Q-RESTORE` · A-9 · `EM-GOV-059(c)` · `EM-GOV-060` · `EM-GOV-062` · W-8 · `EP-01` · `EP-02` · `ES-005.4` · `R-34`.
