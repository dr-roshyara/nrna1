# KOS-ARCH-BASELINE-002 — Principal DDD Architect Review: Registration

**Work item:** `KOS-ARCH-BASELINE-002` · **Session registering:** `S4-architecture-landscape-v2` (the producing terminal) · **Date:** 2026-08-17

> ## What this registration is, and is not
> It registers a review **received in this thread** from the Principal DDD Architect on the three v2 deliverables (`f278dc54`). Under `R-34` it enters the record as **evidence and recommendation only**:
> * It is **NOT the PO/ARB acceptance act.** The deliverables' status is unchanged: **PROPOSED, awaiting PO/ARB decision.** The review's own words gate its notes "before PO/ARB acceptance."
> * The registering session is the **producer** of the reviewed work. Registering a received review is bookkeeping; it is not self-verification and confers nothing. The `R-34`/P-2 forward constraint stands: **this process must not independently verify the v2 model**, and any independent verification the PO/ARB routes must run in a different lane.

---

## 1 · Verdict, verbatim in substance

**VERIFIED WITH NOTES.** The reviewer confirms: correct task performed against the commission (all three deliverables present; the four prohibited classes untouched) · correct DDD sequence preserved (ADR-AIP-03 → strategic model → PO/ARB decision → domain model → implementation architecture) · evidence-based boundary discovery endorsed, with the **Grant record-vs-authority split** and the **Human Act correction** singled out as the strongest parts · the two relationship findings endorsed (the boundary sentence *"Governance decides whether an act was authorized; BC-7 answers whether it was recorded"* quoted with approval; the knowledge seam *"meaning vs movement / adjudicated identity vs folded occurrence"* named the most important discovery for the future KES) · **ADR-AIP-04 confirmed undecided** — the deferral held · no implementation leakage · no self-acceptance. Recommendation: **do not reject.**

## 2 · The two review notes, registered

* **Note 1 — BC-7 name.** The reviewer concurs with the deliverable's §3.6 observation ("Session" names one entity of a language centred on Work Item) **and with its recommendation: do NOT rename now.** The future domain model tests the name (*Governed Work Orchestration* a candidate). No action arises today.
* **Note 2 — next step after acceptance is the DOMAIN MODEL, never implementation.** The reviewer names the next commission: `KOS-ARCH-BASELINE-003 — BC-7 Domain Model` (aggregates, entities, value objects, domain events, policies, invariants). Registered as the reviewer's recommendation for the PO/ARB's sequencing — commissioning it is a PO/ARB + Governance act, not performed here.

## 3 · Prepared startup prompt for the future BC-7 Domain Model terminal — carried, gated

The reviewer supplied a ready-to-use prompt for the next Architecture terminal. It is **preserved verbatim below** so it is not lost, and is **inert until three gates pass, in order**:

1. **PO/ARB acceptance** of the v2 set (Landscape v2 · Context Map v2 · Capability Map v2) — the prompt's own evidence base requires "accepted" artifacts;
2. **Commissioning acts** for `KOS-ARCH-BASELINE-003`: work-item record init · assignment REGISTER · grant · HANDOFF · human START — none exists yet;
3. **A fresh terminal** performs it; whichever lane independently verifies the v2 model must not be this producer.

**Registration note on the prompt's example event names** (`WorkItemRegistered`, `SessionAssigned`, …): read as **non-canonical placeholders** per standing discipline — the domain model proposes names from the discovered language; examples in a commission never pre-decide vocabulary.

```
You are the Senior Principal DDD Architect responsible for KOS-ARCH-BASELINE-003.

Context:

ADR-AIP-03 is accepted.
KOS-ARCH-BASELINE-002 strategic model is accepted by PO/ARB.

Your responsibility is NOT implementation.
Your responsibility is domain discovery and tactical modeling.

Operating principles:

- Observation ≠ Interpretation ≠ Decision.
- Architecture proposes; PO/ARB decides.
- Do not create code.
- Do not move components.
- Do not redesign infrastructure.
- Do not resolve ADR-AIP-04.

Task:

Design the BC-7 Governed Session Orchestration domain model.

Evidence base:

Use only:
- accepted Architecture Landscape v2
- accepted Context Map v2
- accepted Capability Map v2
- existing AST-015/AST-016 behavior evidence

Discover:

1. Aggregate boundaries

Determine:

- Aggregate root
- Entities
- Value Objects
- Domain Events
- Invariants

Especially investigate:

Work Item:
- identity
- lifecycle
- ownership
- transitions

Session Assignment:
- lifecycle
- role immutability
- mutation ownership


2. Domain language

Validate:

- Work Item
- Session
- Assignment
- Transition
- Grant Record
- Human Act Reference
- Handoff
- Mutation Owner

Do not rename without evidence.

3. Boundary rules

Document:

BC-7 owns:
?

BC-7 preserves:
?

BC-7 references:
?

BC-7 never owns:
?

4. Domain events

Propose events only.

Examples:

- WorkItemRegistered
- SessionAssigned
- HandoffRecorded
- SessionStarted
- WorkCompleted

Do not implement.

5. Relationship validation

Reconfirm:

BC-7 ↔ Governance

Policy versus lifecycle record.

BC-7 ↔ Knowledge Engineering

Meaning versus occurrence.

6. Deliverables:

Create:

- BC-7 Domain Model Proposal
- Aggregate Design
- Domain Event Catalog
- Invariant Catalog
- Open Questions

Final output:

Architecture proposal only.

No acceptance.
No implementation.
No ADR decisions.
```

## 4 · Standing state after this registration

`KOS-ARCH-BASELINE-002` remains **OPEN**; `S4-architecture-landscape-v2` remains ACTIVE mutation owner; the three deliverables remain **PROPOSED**. Implementation remains where it has always been on this track: **not authorized** — the reviewer's closing recommendation ("do not start implementation yet") matches the record, which never authorized any. **The single next decision is the PO/ARB's: accept, amend, or decline the v2 set** (routing independent verification first if desired). Everything in §3 waits behind that act.

---

**Traceability:** the Principal review (this thread, 2026-08-17) · v2 deliverables `f278dc54` · commission `2026-08-17-KOS-ARCH-BASELINE-002-commission.md` · grant `G-KOS-ARCHBASE2-MODEL` · START seq 3 · `R-34`/P-2 · ADR-AIP-03 (accepted) · ADR-AIP-04 (deferred, untouched).
