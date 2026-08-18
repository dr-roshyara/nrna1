# `EM-DOM-001` — Domain lane briefing (Phase 1 only)

**Prepared by:** Session 2 — Governance · 2026-08-18 · **Authorization:** PO/ARB, recorded verbatim in `2026-08-18-EM-DOM-001-authorization-request.md`
**Lane status:** ⏸️ **NOT DESIGNATED, NOT STARTED.** This briefing waits for the lane the PO/ARB designates. **It is not a START.**

> ⚠️ **Governance authored this briefing and deliberately authored NO MODEL.** No candidate concept · no class name · no aggregate proposal · no field · no signature · no enum. **If you find one in this document, treat it as a defect and report it** — a Governance-suggested shape would pre-empt the very ownership analysis the slice exists to perform.

## Read these first, in this order

| # | Artifact | Why |
|---|---|---|
| 1 | `docs/publicdigit/adr/ADR_20260817_2145_Aggregate_Absence_Semantics.md` §6 | ADR-1, DECIDED — governed composite absence semantics; **binding constraint ⑥** |
| 2 | `docs/publicdigit/adr/ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` §6 | ADR-2, DECIDED — the **causal-model** ruling, (a)–(h). **It is not an Option A/B/C/D choice; do not read it as one** |
| 3 | `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr2.md` | the gate + the **A/B/C matrix** (A=2 · B=6 · C=6). This is the evidence, already accepted |
| 4 | `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md`-equivalent record at `7514f145` | the ADR-1 gate |
| 5 | the authorization request/record, §§1–10 + both annotations | your scope and your obligations |
| 6 | `ELECTION_MANIFESTO.md` — `EM-GOV-059(c)`, `060`, `062`, `EM-GOV-064…066`, `EM-GOV-068` | the adopted business rules the invariants must serve |

## Your mission

**Resolve DEP-1 … DEP-6 as ONE bounded domain-model slice.** All six are class **B** — missing domain concepts or missing domain reachability. **None of them has a legitimate Application-layer solution**; that was the gate's finding, and it is settled.

## Phase 1 deliverables — and Phase 1 stops

**Deliverable 1 — the domain decision map.** For each of DEP-1…DEP-6 answer, in this order: ① what is the invariant? ② which bounded context **owns** it? ③ what domain concept expresses it? ④ what are the **legitimate** states? ⑤ what is **impossible**? ⑥ what must be **captured at the transition**? ⑦ what representation makes those states **explicit**? ⑧ what does UC-3 **consume** afterwards?

It must render the two discriminations in §9 of the authorization record explicitly — absent-reference (*required by invariant* vs *legitimate lifecycle*) and restoration (*Recovery existed* vs *no `RecoveryProcess`*).

**Deliverable 2 — the minimum RED-test slice** that proves the missing invariants.

**Then STOP and report.** ⛔ **No GREEN. No implementation. Reporting the map and the RED proposal is where Phase 1 ends** — review precedes implementation.

## Hard constraints

⛔ **Do not modify:** the Application layer · UC-1/UC-2/UC-3/UC-4 · repositories · protocol access · anything under `app/Contexts/Election/Application/`.
⛔ **Do not add a protocol read.** ⛔ **Do not reconstruct provenance.** ⛔ **Do not invent a technical mechanism.**
⛔ **Do not design around `FillCommitteeSeatHandler`'s current structure.** Its shape is the symptom, not the specification.
⛔ **Do not aim at `AbsentAggregateReferenceRedTest`.** It goes green later as a **consequence**, never as your target (Obligation 5). **You are not asked to make existing tests pass; you are asked to make the approved invariants explicit and testable.**
⛔ **Do not recreate or duplicate P-7** `ResumptionTarget::resolve(HaltedAtGate): GateDesignation` — it exists, it is authorized, you **consume** it (`ES-005.4`; ADR-2 (g): *reference is not ownership*).
⛔ **Do not touch** `if ($restoration === null) { return; }` — **DEP-10 is class A**, grounded in `EM-GOV-062`, and Annotation A of the authorization record governs. **Normalization means semantic conformity, not syntactic uniformity.**
⛔ **New scope discovered mid-slice → a backlog item and a report, never an extension** (Obligation 6).
⛔ **The domain core is currently byte-identical to `1f4b4c5f`.** Phase 1 changes none of it.

## The three facts most likely to be mis-modelled

1. **DEP-5b is a producer gap, not a concept gap.** `HaltedAtGate` exists; nothing in `app/` produces or persists one; no port or repository carries one; P-7's only call site in the repository is a domain unit test. **The chain exists, is authorized, and is unreachable.**
2. **"Where does restoration return to?" and "why is restoration permitted?" are different questions.** P-7 answers the first. **Nothing answers the second.** Keeping them distinct is a permanent boundary (ADR-2 (a)).
3. **`w8` is not hypothetical.** Restoration can follow an unachievable condition being resolved **with no `RecoveryProcess` ever having existed**, so `Restoration → RecoveryProcess → originatingGate` **cannot be the universal causal model**. `ElectionRestored.$returnsToGate` being **non-nullable** is, per ADR-2 (h), **the domain-model gap itself** — decided *with* this slice, not deferred.

## Process obligations

Domain **RED committed and verified failing-by-absence BEFORE** GREEN, as **separate commits**, so ordering is git-provable (the `EM-IMPL-002` two-phase discipline) · **independent verification** — you supply evidence and never accept your own work (`EP-02`, `R-34`) · a **developer guide** with the step (repo Definition of Done) · **one story → one commit**, subject carrying its ID.

**Traceability:** the `EM-DOM-001` authorization record · ADR-1 §6 · ADR-2 §6 · `7514f145` · `74fcf5e5` · `c4828cdd` · `b78c50ab` · `f53469cb` · `1f4b4c5f`.
