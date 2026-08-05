# PublicDigit Bounded Context Engineering Protocol

**Date:** 2026-08-01 · **Prepared by:** Engineering AI, under Principal Architect discipline
**Status:** ✅ **FROZEN (2026-08-01)** — **no further changes unless PublicDigit implementation exposes a genuine deficiency.** Still **PROPOSED, not adopted**; adoption is an Engineering-Process act (§Adoption). **Frozen and adopted are different states: the freeze stops refinement, not the absence of authority.**
**Classification:** proposal paper · **Role:** Reference · **Scope:** product-specific · **Domain:** publicdigit · **Maturity:** research
**Applies to:** every future work package — WP-7C · WP-8 · EPIC-005 (Evidence) · EPIC-006 (Voting) · EPIC-007 (Appointment) · EPIC-008 (Read Models) and beyond.

> ## This document POINTS. It does not restate.
>
> **Most phases bind a rule that already has a canonical home.** Restating those rules here would create a second home and violate **ES-005.4 (never a copy)**. **Each phase therefore carries its home** — the *questions* are an operating checklist, not rules; **the rules stay where they live.**
>
> **⭐ marks the genuinely new elements.** The runtime summary is bound in `.claude/CLAUDE.md` § *The Operating Loop*.

---

## Role

The **Engineering AI**, under the **Principal Architect**. Implements approved work within bounded contexts while preserving PublicDigit's architectural integrity. **Does not redesign accepted architecture. Does not exercise governance authority. Continuously protects the Strategic and Tactical DDD model.**

---

## Phase 0 — Business Capability · *Home: Development Discipline standing rule*

| Question | |
|---|---|
| What business capability is being changed? | |
| What is the business objective? | |
| What business rule is being protected? | |
| What constitutional policy applies? | |
| What is the expected business outcome? | |

**If the capability cannot be identified — STOP and request clarification. Do not infer.**

## Phase 1 — Strategic DDD Review · *Home: the standing rule · AIP-14*

### 1.1 Owning bounded context

| Question | |
|---|---|
| Which bounded context **owns** this capability? | |
| Is another bounded context affected? | Yes / No |
| Is this a Published-Language interaction? | Yes / No |
| Is ownership changing? | Yes / No |
| Does the context map change? | Yes / No |

**If ownership or the context map changes — STOP, return for architectural review.**

### 1.2 Published and Ubiquitous Language

| Question | |
|---|---|
| Which Published Language terms are involved? | |
| Which Ubiquitous Language terms are involved? | |
| Is any term renamed · introduced · changed in meaning? | Yes / No |

**Any Yes — STOP, return for architectural review.**

### 1.3 Strategic invariants

| Question | |
|---|---|
| Which strategic invariants must be preserved? | |
| Does this change affect any of them? | Yes / No |
| Which constitutional policies apply? | |

## Phase 2 — Canonical Discovery · *Home: ES-005.4 · ES-004*

**Search before modelling. This is a DDD search, not a documentation search.**

| Search target | Question |
|---|---|
| Capability | does it already exist elsewhere? |
| Aggregate · Entity · Value Object | does one already model this concept? |
| Domain Service · Application Service | does one already handle this coordination? |
| Port · Repository | does one already define this contract or persistence? |
| Domain Event | does one already represent this occurrence? |
| ADR · Engineering Standard | is the decision or practice already recorded? |
| Bounded context | does one already own this? |

**If a canonical artifact exists — reuse or extend it. Never create a second.** **If nothing exists, name what is missing and return the question to governance; invent nothing.**

## Phase 3 — Tactical DDD Review · *Home: `DDD_Tactical_Governance_Principles.md`*

| Element | Status |
|---|---|
| Aggregates · Entities · Value Objects | |
| Domain Services · Application Services | |
| Repositories · Ports · Domain Events | |

| Dependency rule | Status |
|---|---|
| **Infrastructure → Application → Domain** | |
| **Never reversed** | |

**Consume what Phase 2 found, or what governance has approved. Invent no tactical concept.**

## Phase 4 — Architectural Stewardship · *Home: ES-002.1 · ES-002.2*

| Question | |
|---|---|
| What must remain **stable**? | |
| What may **evolve**? | |
| Does the evidence justify revisiting an accepted decision? | Yes / No |

**Implementation is the default — only a NO carrying implementation evidence of insufficiency opens an ADR/ARB discussion.** **Do not go looking for new bounded contexts, aggregates or abstractions.**

## Phase 5 ⭐ — Architectural Impact Classification

**Each finding takes exactly one primary classification, before any recommendation:**

`Strategic Architecture` · `Tactical Architecture` · `Governance` · `Engineering` · `Repository` · `Documentation` · `Operational` · `PKS Observation` · `KnowledgeOS Candidate`

### The Model Integrity Rule — ⚠️ **OBSERVATION, NOT PROMOTED**

> **Status: one use. Promotion requires repeated operational evidence.** It is recorded here as an observation, **not** as an adopted element of the protocol — the ⭐ marker is withdrawn. *(Its own necessity test would reject it: a single application is not evidence of general applicability.)*

> **Whenever a new attribute or dimension is proposed, first determine whether it is a *new dimension*, an *overloaded existing dimension*, or *merely another value*. Introduce a new dimension only if it is orthogonal · necessary · sufficient — all three, or the change does not proceed. If an existing dimension is found to contain mixed concepts, that is a modelling defect, to be surfaced before architectural evolution rather than as part of it.**

**Applied once (2026-08-01), it falsified a proposal that had already been recommended.** **One occurrence. Not promoted.**

**Placement note:** the rule is cross-product at research maturity, which resolves to **PENDING** — the fourth arrival at that unruled cell. **It is recorded here, inside a document that has a home, rather than given one of its own.**

## Phase 6 — Engineering Readiness · *Home: EP-01 · EP-01-Light · EP-03*

| Criterion | Status |
|---|---|
| Authorization status | |
| Entry criteria | |
| Required tests | |
| Architectural invariants | |
| Dependency rules | |
| Verification gates | |
| Expected RED scope | |

**If authorization is absent — STOP. Engineering never begins without it.**

## Phase 7 ⭐ — Engineering Execution Contract

| | |
|---|---|
| **Engineering MAY** | the approved implementation changes, named |
| **Engineering MUST NOT** | the protected architectural boundaries, named |
| **MUST STOP AND REFER IF it needs** | a new **bounded context · aggregate · repository · port · domain term · context crossing · architectural policy change** |

**The enumerated trigger list is the new part**, and its value is that it is enumerable in advance. **A slice that cannot name its MAY and MUST-NOT sets has not completed Phase 6.**

## Phases 8–10 — RED → GREEN → VERIFY · *Home: the standing rule · ER-08*

| | |
|---|---|
| **8 RED** | failing tests · implementation boundary · expected verification · acceptance criteria. **Do not implement.** |
| **9 GREEN** | approved scope only. **No opportunistic refactoring, no scope expansion, no redesign.** |
| **10 VERIFY** | tests pass · merge gate passes · architectural boundaries preserved · repository health preserved · **developer guide updated (DoD)** |

**Reviews record deviations; implementation repairs them in a later authorized slice (ER-08).**

## Phase 11 — ACCEPT · *Home: EP-02 · R-34*

> **Acceptance is a governance act. Engineering supplies evidence and never accepts its own work.** **Keep evidence · recommendation · authority rigorously separate.**

**EP-02's report format is mandatory:** changes made · **changes deliberately NOT made** · verification/evidence · commit refs · next recommended action.

## Phases 12–14 ⭐ — Operational Evidence → PKS Classification → Promotion Check

**Home for the ladder: ES-006.1. The evaluation gate at ACCEPT is the new part.**

| Outcome | Criterion |
|---|---|
| **No reusable knowledge** | **The default — and a result, not a failure** |
| **PKS Observation** | revealed something about **how engineering knowledge behaves**, not about the product |
| **Repeated Operational Evidence** | the **Nth** occurrence. **Recurrence is the trigger, not novelty** |
| **KnowledgeOS Candidate** | **cross-product** — and **one corpus is one observation; promotion needs another repository** |

**Phase 13 — for a PKS Observation:** what happened · what the pattern was · is it a recurrence · should it be captured as operational evidence.
**Phase 14 — for a KnowledgeOS Candidate:** is it cross-product · has it occurred in multiple repositories · is there **repeated** operational evidence.

**Promotion is never automatic and never engineering's act (R-34).**

---

## The workflow

```
0 Business Capability → 1 Strategic DDD → 2 Canonical Discovery → 3 Tactical DDD
→ 4 Stewardship → 5 Impact Classification → 6 Readiness → 7 Execution Contract
→ 8 RED → 9 GREEN → 10 VERIFY → 11 ACCEPT → 12 Operational Evidence
→ 13 PKS Classification → 14 KnowledgeOS Promotion Check
```

> **FROZEN.** Phases 0–14 are fixed in their current state. **Phase 5, Phase 7's trigger list and Phases 12–14 remain PROPOSED and unadopted; the Model Integrity Rule remains an unpromoted observation.** **The protocol is sufficient for the work ahead — it is not to be optimised further.**

## What is actually new

| Element | Status |
|---|---|
| Phases 0–4, 6, 8–11 | **already canonical** — this document points and adds an operating checklist |
| **Phase 5** — impact classification | ⭐ **NEW** |
| **The Model Integrity Rule** | ⚠️ **OBSERVATION — not promoted** (one use) |
| **Phase 7** — enumerated stop-and-refer triggers | ⭐ **NEW** — principle canonical, enumeration new |
| **Phases 12–14** — the evaluation gate | ⭐ **NEW** — ladder canonical, gate new |

## Adoption

**An Engineering-Process act**, whose home is the EP section of `docs/implementation/Implementation_Process_v1.1_Draft.md`. **Not an architecture act, and not an ARB ruling unless the ARB chooses to rule it.**

| Option | Effect |
|---|---|
| **Adopt the ⭐ elements into the EP section** | rules stay in one home; this becomes a pointer-only operating aid |
| **Adopt this document as the protocol** | one place to read; **the pointer discipline must be maintained, or it silently becomes a second home** |

**Architecture prefers neither.**

## Worked instance

**WP-7C, Phases 0–7 completed: `.claude/plans/WP-7C-engineering-readiness.md`.** **It stops at Phase 6 — authorization absent.** **The instance is not duplicated here: the protocol is the template, the work plan is the instance.**

---

## Guiding principles

Business capability before implementation · Strategic DDD before Tactical DDD · **search before modelling** · architecture before engineering · governance before execution · engineering before optimization · **evidence before architectural evolution** · stable architecture, evolving implementation · **PublicDigit remains the primary product** · KnowledgeOS evolves only through validated operational evidence · PKS preserves engineering knowledge · **the strongest claim made must never exceed the available evidence.**

---

**Traceability:** `.claude/CLAUDE.md` § Development Discipline + § The Operating Loop (runtime binding) · `docs/implementation/Implementation_Process_v1.1_Draft.md` (EP-01 · EP-01-Light · EP-02 · EP-03 · ER-08) · **ES-002.1 · ES-002.2** (Phase 4) · **ES-004 · ES-005.4** (Phase 2) · **ES-006.1** (Phases 12–14) · **R-34** · **AIP-14** · `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` (Phase 3) · `2026-08-01-classification-inputs-three-quality-test.md` (first application of the Model Integrity Rule). **No rule restated · nothing adopted.**
