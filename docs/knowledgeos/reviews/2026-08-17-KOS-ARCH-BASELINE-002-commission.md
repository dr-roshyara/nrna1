# KOS-ARCH-BASELINE-002 — Commission

## Architecture Landscape v2 — the strategic model after ADR-AIP-03

**Work item:** `KOS-ARCH-BASELINE-002` · **Workflow:** `architecture-model-update`
**Declared role set:** `governance`, `architecture`, `verification` — implementation deliberately excluded, so the act's *"no implementation changes"* is mechanically enforced, not merely written
**Status:** grant AUTHORIZED · architecture assignment REGISTERED · HANDOFF recorded · **START NOT performed**

---

## 1 · The human act, verbatim

> **"RECORD: Commission KOS-ARCH-BASELINE-002. Purpose: Update the strategic architecture model following ADR-AIP-03. …"** *(full act in the grant, word for word — scope, required analysis, constraints, deliverables, and the closing rule: "Architecture may propose. PO/ARB decides.")*

— PO/ARB, 2026-08-17.

## 2 · Scope (the act's own structure)

**Create Architecture Landscape v2:** update the bounded-context map from 6 to 7 contexts · introduce **BC-7 Governed Session Orchestration** · update the capability map with **CAP-14** · define the relationships between BC-7 and the existing contexts.

**Required analysis 1 — the BC-7 boundary:** owned language · invariants · responsibilities · **exclusions**. The Principal review supplies the starting hypotheses, **to be tested, not inherited**:

| BC-7 plausibly OWNS *(candidates)* | BC-7 plausibly does NOT own *(candidates — the exclusion list matters as much)* |
|---|---|
| Work Item · Assignment · Grant · Handoff · Lifecycle State · Mutation Ownership · Transition · Human Act | Policy · Evidence meaning · Architecture decisions · Implementation · Knowledge lifecycle |

**Required analysis 2 — the two relationship studies.** **The exact relationships must be discovered — do not assume**; the hypotheses on record are inputs, not conclusions:
- **BC-7 ↔ Governance** — the *policy versus process* boundary. Hypothesis: Governance = authority **policy** ("who may approve?"); BC-7 = authority **execution lifecycle** ("was approval recorded?") — the *business rule ≠ business process* distinction.
- **BC-7 ↔ Knowledge Engineering** — the *knowledge identity versus workflow history* boundary. Hypothesis: Knowledge Engineering answers *"what is known?"*; BC-7 answers *"how governed work progresses?"* — do not merge.

## 3 · Constraints (the act's, verbatim)

No implementation changes · no folder movement · no component extraction · **no ADR-AIP-04 decision** · **no role-model decision** (both expressly deferred by ADR-AIP-03's consequence block).

**Why ADR-AIP-04 stays frozen during this work** *(Principal review, recorded so the freeze is understood, not just obeyed)*: BC-7 discovery may itself produce role-ownership evidence — Role, Assignment, Responsibility, Actor, Authority may belong partly to BC-7. Starting ADR-AIP-04 before this landscape lands would mean designing roles before understanding their ownership.

**The roadmap this work sits in** *(context, not additional scope)*: this commission → PO/ARB acceptance of the v2 deliverables → BC-7 Domain Model → ADR-AIP-04 discovery → implementation architecture → implementation. **Implementation remains frozen until the BC-7 model and ADR-AIP-04 have enough evidence.**

## 4 · Deliverables

**Architecture Landscape v2 · Context Map v2 · Capability Map v2.** All three are proposals: **Architecture may propose; the PO/ARB decides** — the deliverables return for decision, they do not self-adopt.

## 5 · Inputs

Accepted Phase A v1.1 baseline (`40026b12`, the authoritative current-state) · **ADR-AIP-03 as accepted, both consequences taken** (BC-7 recognized · CAP-14 assigned · role-model deferred) · ADR-AIP-01 (the frozen six-context model this work amends *by the ADR's authority, not silently*) · Stage-2 Bounded Context Confirmation Report · the Capability Identity Invariant (one identifier = one semantic owner).

## 6 · Placement

Work products go to `docs/knowledgeos/reviews/` — derived from the knowledge subject, never inherited from a predecessor path or this record's `tokenRef`.

**Traceability:** the act verbatim (grant) · ADR-AIP-03 (`1017c044`) · baseline acceptance `378f6eaa` · Stage-2 report · ADR-AIP-01 · `R-37` · `ES-005.4`
